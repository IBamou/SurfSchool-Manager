<?php
namespace App\Models;

use App\Configs\Model;
use App\Helpers\ValidationHelper;
use App\Models\StudentModel;
use PDO;
use PDOException;
use Exception;

class AuthModel extends Model {

    public $error = false;
    public $validationModel;

    public $hasRun = false;

    public function __construct() {
        parent::__construct();   
        $this->validationModel = new ValidationHelper();
    }

    public function setAdmin($name, $email, $password) {
        if ($this->hasRun) {
            return true;
        }

        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $this->db->beginTransaction();

            $stmt = $this->db->prepare("UPDATE users SET role='user' WHERE role IN ('admin', 'mainadmin')");
            $stmt->execute();

            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $userExists = $stmt->fetch() !== false;

            if ($userExists) {
                $stmt = $this->db->prepare("UPDATE users SET role='admin' WHERE email = ?");
                $stmt->execute([$email]);
            } else {
                $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
                $stmt->execute([$name, $email, $hashedPassword]);
            }

            $this->db->commit();
            $this->hasRun = true;
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            return "Error: " . $e->getMessage();
        }
    }

    public function verifyLogInData($email, $password) {
        try {
            // fetch the user by email
            $query = "SELECT id, email, password FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->db   ->prepare($query);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // check if blocked
                if ($user['status'] === 'blocked') {
                    return [
                        'success' => false,
                        'email' => true,
                        'password' => false,
                        'blocked' => true
                    ];
                }

                // verify password
                if (password_verify($password, $user['password'])) {
                    return [
                        'success' => true,
                        'user_id' => $user['id'],
                        'email' => true,
                        'password' => true,
                        'blocked' => false
                    ];
                } else {
                    return [
                        'success' => false,
                        'email' => true,
                        'password' => false,
                        'blocked' => false
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'email' => false,
                    'password' => false,
                    'blocked' => false
                ];
            }

        } catch (PDOException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'blocked' => false
            ];
        }
    }
    public function createSession($userInfo) {
        $_SESSION['user'] = $userInfo;
        $studentModel = new StudentModel();
        $_SESSION['student'] = $studentModel->getStudentByUserId($_SESSION['user']['id']);
    }
    public function closeSession() {
        session_destroy();
    }
}
