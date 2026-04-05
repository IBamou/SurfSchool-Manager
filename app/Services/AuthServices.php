<?php
namespace App\Services;

use App\Models\UserModel;
use App\Models\AuthModel;
use App\Models\StudentModel;
use App\Helpers\ValidationHelper;
use App\Helpers\Sanitizer;
use App\Middleware\RateLimiter;

/**
 * AuthServices - Handles all authentication business logic
 * 
 * Responsibilities:
 * - User login with rate limiting
 * - User registration with email verification
 * - Password reset functionality
 * - Session management
 */
class AuthServices {
    public  string $baseUrl;
    private UserModel $userModel;
    private AuthModel $authModel;
    private StudentModel $studentModel;
    private ValidationHelper $validationModel;
    private EmailService $emailService;
    private RateLimiter $rateLimiter;
    private Logger $logger;

    public function __construct(
        ?UserModel $userModel = null,
        ?AuthModel $authModel = null,
        ?StudentModel $studentModel = null,
        ?ValidationHelper $validationModel = null,
        ?EmailService $emailService = null,
        ?RateLimiter $rateLimiter = null
    ) {
        $this->baseUrl = $this->getBaseUrl();
        $this->userModel = $userModel ?? new UserModel();
        $this->authModel = $authModel ?? new AuthModel();
        $this->studentModel = $studentModel ?? new StudentModel();
        $this->validationModel = $validationModel ?? new ValidationHelper();
        $this->emailService = $emailService ?? new EmailService();
        $this->rateLimiter = $rateLimiter ?? new RateLimiter();
        $this->logger = Logger::getInstance();
    }
    
    private function getBaseUrl(): string {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        return $protocol . $_SERVER['HTTP_HOST'] . '/surfManager/';
    }

    /**
     * Handle user login
     * Includes rate limiting to prevent brute force attacks
     */
    public function login(): bool {
        // Check rate limit first (5 attempts per minute)
        if (!$this->rateLimiter->limitLogin()) {
            $_SESSION['error'] = "Too many login attempts. Please wait a minute and try again.";
            $this->logger->warning("Login blocked due to rate limit", [
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            return false;
        }

        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !$this->validationModel->verifyCSRFToken($_POST['csrf_token'])) {
            $_SESSION['error'] = "Invalid request. Please try again.";
            return false;
        }
        
        // Sanitize and get input
        $email = Sanitizer::sanitizeEmail($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Basic validation
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Please enter both email and password.";
            return false;
        }

        // Attempt login through auth model
        $user = $this->authModel->verifyLogInData($email, $password);
        
        if ($user && $user['success']) {
            $userInfo = $this->userModel->getUser(0, $email);
            
            if ($userInfo) {
                // Clear rate limit on successful login
                $this->rateLimiter->clear($email, 'login');
                
                // Create session
                $this->authModel->createSession($userInfo);
                session_regenerate_id(true);
                unset($_SESSION['csrf_token']);
                
                $this->logger->auth('LOGIN', true, $email);
                $_SESSION['success'] = "Login successful.";
                return true;
            }
        }
        
        $this->logger->auth('LOGIN', false, $email, [
            'reason' => 'invalid_credentials'
        ]);
        $_SESSION['error'] = "Invalid email or password.";
        return false;
    }

    /**
     * Handle user registration with email verification
     */
    public function signup(): array {
        // Check rate limit (3 attempts per 5 minutes)
        if (!$this->rateLimiter->limitSignup()) {
            $this->logger->warning("Signup blocked due to rate limit");
            return [
                'success' => false,
                'redirect' => $this->baseUrl . 'signup',
                'error' => "Too many signup attempts. Please wait a few minutes and try again."
            ];
        }

        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !$this->validationModel->verifyCSRFToken($_POST['csrf_token'])) {
            $_SESSION['error'] = "Invalid request. Please try again.";
            return ['success' => false, 'redirect' => $this->baseUrl . 'signup'];
        }
        
        // Sanitize all input
        $name = Sanitizer::sanitizeString($_POST['name'] ?? '');
        $email = Sanitizer::sanitizeEmail($_POST['email'] ?? '');
        $level = Sanitizer::sanitizeString($_POST['level'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validate inputs
        $validationErrors = $this->validationModel->verifyInputs($name, $email, $password);
        if (!empty($validationErrors)) {
            $_SESSION['error'] = implode(' ', $validationErrors);
            $_SESSION['form_data'] = ['name' => $name, 'email' => $email];
            return ['success' => false, 'redirect' => $this->baseUrl . 'signup'];
        }

        // Check if email already exists
        if ($this->userModel->getUser(0, $email)) {
            $_SESSION['error'] = "Email already exists.";
            $_SESSION['form_data'] = ['name' => $name, 'email' => $email];
            $this->logger->auth('SIGNUP', false, $email, ['reason' => 'email_exists']);
            return ['success' => false, 'redirect' => $this->baseUrl . 'signup'];
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Create user
        $result = $this->userModel->addUser($name, $email, $hashedPassword);
        
        if ($result === true) {
            $user_id = $this->userModel->last_added_user_id;
            $this->studentModel->addStudent($user_id, $level);
            
            // Generate email verification token
            $token = $this->emailService->generateVerificationToken($user_id, $email);
            
            // Send verification email
            $this->emailService->sendVerificationEmail($email, $name, $token);
            
            unset($_SESSION['csrf_token']);
            $this->logger->auth('SIGNUP', true, $email, ['user_id' => $user_id]);
            
            return [
                'success' => true,
                'redirect' => $this->baseUrl . 'login?success=Account created! Please check your email to verify your account.'
            ];
        }
        
        $this->logger->auth('SIGNUP', false, $email, ['reason' => 'db_error']);
        $_SESSION['error'] = "Something went wrong. Please try again.";
        $_SESSION['form_data'] = ['name' => $name, 'email' => $email];
        return ['success' => false, 'redirect' => $this->baseUrl . 'signup'];
    }

    /**
     * Verify user email with token
     */
    public function verifyEmail(string $token): array {
        $result = $this->emailService->verifyToken($token);
        
        if (!$result['success']) {
            $this->logger->warning("Email verification failed", [
                'error' => $result['error']
            ]);
            return $result;
        }
        
        // Mark user as verified in database
        $this->userModel->markEmailVerified($result['user_id']);
        
        return [
            'success' => true,
            'user_id' => $result['user_id'],
            'error' => null
        ];
    }

    /**
     * Handle user logout
     */
    public function logout(): array {
        $userId = $_SESSION['user']['id'] ?? null;
        $email = $_SESSION['user']['email'] ?? 'unknown';
        
        $this->authModel->closeSession();
        
        if ($userId) {
            $this->logger->auth('LOGOUT', true, $email, ['user_id' => $userId]);
        }
        
        return ['success' => true, 'redirect' => $this->baseUrl . 'home'];
    }

    /**
     * Get validation helper instance for CSRF token generation
     */
    public function getValidationModel(): ValidationHelper {
        return $this->validationModel;
    }
}
