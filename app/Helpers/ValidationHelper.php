<?php 
namespace App\Helpers;

class ValidationHelper {
    public $errors;

    public function __construct() {
        $this->errors = [];
    }

    function generateCSRFToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    function verifyCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    function cleanInput($data) {
        return htmlspecialchars(trim($data));
    }

    function verifyInputs($name, $email, $password) {
        
        if (empty($name)) {
            $this->errors[] = "Name is required.";
        } elseif (strlen($name) > 100) {
            $this->errors[] = "Name cannot exceed 100 characters.";
        }
        
        if (empty($email)) {
            $this->errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Please enter a valid email address.";
        }
        
        if (empty($password)) {
            $this->errors[] = "Password is required.";
        } elseif (strlen($password) < 6) {
            $this->errors[] = "Password must be at least 6 characters.";
        }
        
        return $this->errors;
    }

}

