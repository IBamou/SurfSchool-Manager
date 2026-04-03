<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AuthModel;
use App\Models\StudentModel;
use App\Helpers\ValidationHelper;

class AuthController{
    public $baseUrl;
    private $authModel;
    private $validationModel;
    private $userModel;
    private $studentModel;

    public function __construct() {
        $this->baseUrl = $this->getBaseUrl();
        $this->authModel = new AuthModel();
        $this->validationModel = new ValidationHelper();
        $this->userModel = new UserModel();
        $this->studentModel = new StudentModel();
        if (!$this->authModel->hasRun) {
            // Default admin credentials - CHANGE THESE IN PRODUCTION
            $this->authModel->setAdmin('Admin', 'admin@surfmanager.com', 'admin123');
        }
    }
    
    private function getBaseUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        return $protocol . $host . '/surfManager/';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (!isset($_POST['csrf_token']) || !$this->validationModel->verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid request. Please try again.";
                header('Location: ' . $this->baseUrl . 'login');
                exit;
            }
            
            $email = $this->validationModel->cleanInput($_POST['email']);
            $password = $_POST['password'];

            if (empty($email) || empty($password)) {
                $_SESSION['error'] = "Please enter both email and password.";
                header('Location: ' . $this->baseUrl .'login');
                exit;
            }

            $user = $this->authModel->verifyLogInData($email, $password);
            
            if ($user && $user['success']) {
                $userInfo = $this->userModel->getUser(0, $email);
                if ($userInfo) {
                    $this->authModel->createSession($userInfo);
                    session_regenerate_id(true);
                    unset($_SESSION['csrf_token']);
                    header('Location: ' . $this->baseUrl . 'dashboard?success=Login successful');
                    exit;
                }
            } else {
                $_SESSION['error'] = "Invalid email or password.";
                header('Location: ' . $this->baseUrl . 'login?error=Invalid email or password');
                exit;
            }
        }
        $csrfToken = $this->validationModel->generateCSRFToken();
        $this->render_template('login', ['baseUrl' => $this->baseUrl,'csrfToken' => $csrfToken]); 
    }

    public function signup() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['csrf_token']) || !$this->validationModel->verifyCSRFToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Invalid request. Please try again.";
                header('Location: ' . $this->baseUrl . 'signup');
                exit;
            }
            
            $name = $this->validationModel->cleanInput($_POST['name']);
            $email = $this->validationModel->cleanInput($_POST['email']);
            $level = $this->validationModel->cleanInput($_POST['level']);
            $password = $_POST['password'];

            $validationErrors = $this->validationModel->verifyInputs($name, $email, $password);
            if (!empty($validationErrors)) {
                $_SESSION['error'] = implode(' ', $validationErrors);
                $_SESSION['form_data'] = ['name' => $name, 'email' => $email];
                header('Location: ' . $this->baseUrl . 'signup');
                exit;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $result = $this->userModel->addUser($name, $email, $hashedPassword);
            
            if ($result === true) {
                $user_id = $this->userModel->last_added_user_id;
                $this->studentModel->addStudent($user_id, $level);
                header('Location: ' . $this->baseUrl . 'login?success=Account created successfully! Please sign in.');
                exit;
                
            } else {
                $_SESSION['error'] = "Email already exists or something went wrong.";
                $_SESSION['form_data'] = ['name' => $name, 'email' => $email];
                header('Location: ' . $this->baseUrl . 'signup');
                exit;
            }
        }
        $csrfToken = $this->validationModel->generateCSRFToken();
        $this->render_template('signup', ['baseUrl' => $this->baseUrl, 'csrfToken' => $csrfToken]);
    }

    public function logout() {
        $this->authModel->closeSession();
        header("Location: " . $this->baseUrl . "home");
        exit;
    }

    private function render_template(string $template = '', array $data = []) {
        if ($template) {
            extract($data);
            include '../app/Views/auth/' . $template . '.php';
            exit;
        }
    }
}