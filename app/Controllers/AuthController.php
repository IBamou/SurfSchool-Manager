<?php
namespace Ilyas\SurfManager\Controllers;

use Ilyas\SurfManager\Models\SessionModel;
use Ilyas\SurfManager\Models\AssignmentModel;
use Ilyas\SurfManager\Models\UserModel;
use Ilyas\SurfManager\Models\AuthModel;
use Ilyas\SurfManager\Models\StudentModel;
use Ilyas\SurfManager\Helpers\ValidationHelper;

class AuthController{
    public $baseUrl;
    private $authModel;
    private $validationModel;
    private $userModel;

    private $studentModel;

    public function __construct() {
        $this->baseUrl = 'http://localhost/surfManager/';
        $this->authModel = new AuthModel();
        $this->validationModel = new ValidationHelper();
        $this->userModel = new UserModel();
        $this->studentModel = new StudentModel();
        $this->authModel->setAdmin('Ilyas', 'ilyas0bmp@gmail.com', 'hellohello');
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
            
            // if (isset($user['blocked']) && $user['blocked']) {
            //     $_SESSION['error'] = "Your account has been blocked. Please contact an administrator.";
            //     header('Location: ' . $this->baseUrl .'login');
            //     exit;
            // }

            if ($user && $user['success']) {
                $userInfo = $this->userModel->getUser(0, $email);
                if ($userInfo) {
                    $this->authModel->createSession($userInfo);
                    session_regenerate_id(true);
                    unset($_SESSION['csrf_token']);
                    header('Location: ' . $this->baseUrl . 'dashboard');
                    exit;
                }

            } else {
                echo '2';
                $_SESSION['error'] = "Invalid email or password.";
                header('Location: ' . $this->baseUrl . 'login');
                exit;
            }
        }
        $csrfToken = $this->validationModel->generateCSRFToken();
        $this->render_template('login', ['baseUrl' => $this->baseUrl,'csrfToken' => $csrfToken]); 
    }

    public function signup() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = new UserModel();
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
                $_SESSION['success'] = "Account created successfully! Please sign in.";
                header('Location: ' . $this->baseUrl . 'login');
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
