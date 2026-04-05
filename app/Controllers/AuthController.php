<?php
namespace App\Controllers;

use App\Services\AuthServices;
use App\Helpers\Sanitizer;
use App\Models\AuthModel;

/**
 * AuthController - Handles authentication HTTP requests
 * 
 * Thin controller - delegates all logic to AuthServices
 */
class AuthController extends BaseController {
    private AuthServices $authService;

    public function __construct() {
        parent::__construct();
        $this->authService = new AuthServices();
        
        // Create default admin user if not exists
        $authModel = new AuthModel();
        if (!$authModel->hasRun) {
            $authModel->setAdmin('Admin', 'admin@surfmanager.com', 'admin123');
        }
    }

    /**
     * Display login form or process login
     */
    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->authService->login();
            
            if ($result) {
                $this->redirectWithSuccess('dashboard', 'Welcome back!');
            } else {
                $this->redirectWithError('login', $_SESSION['error'] ?? 'Login failed');
            } 
        }
        
        $csrfToken = $this->authService->getValidationModel()->generateCSRFToken();
        $this->renderAuth('login', ['csrfToken' => $csrfToken]); 
    }

    /**
     * Display signup form or process registration
     */
    public function signup(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->authService->signup();
            
            if ($result['success']) {
                $this->redirect($result['redirect']);
            } else {
                $_SESSION['error'] = $result['error'] ?? 'Signup failed';
                $this->redirect($result['redirect']);
            }
        }
        
        $csrfToken = $this->authService->getValidationModel()->generateCSRFToken();
        $this->renderAuth('signup', ['csrfToken' => $csrfToken]);
    }

    /**
     * Verify email with token
     */
    public function verify(): void {
        $token = Sanitizer::sanitizeString($_GET['token'] ?? '');
        
        if (empty($token)) {
            $this->redirectWithError('login', 'Invalid verification link.');
        }
        
        $result = $this->authService->verifyEmail($token);
        
        if ($result['success']) {
            $this->redirectWithSuccess('login', 'Email verified! You can now log in.');
        } else {
            $this->redirectWithError('signup', 'Email verification failed: ' . ($result['error'] ?? 'Unknown error'));
        }
    }

    /**
     * Process logout
     */
    public function logout(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->authService->logout();
            $this->redirect($result['redirect']);
        }
    }
}
