<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AssignmentModel;
use App\Models\SessionModel;
use App\Models\LessonModel;
use App\Models\StudentModel;
use App\Models\CoachModel;

class ProfileController {
    public $baseUrl;

    public function __construct() {
        $this->baseUrl = $this->getBaseUrl();
    }
    
    private function getBaseUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        return $protocol . $host . '/surfManager/';
    }

    public function profile() {
        $userId = $_SESSION['user']['id'] ?? null;
        $userRole = $_SESSION['user']['role'] ?? 'user';

        if (!$userId) {
            header('Location: ' . $this->baseUrl . 'auth/login');
            exit;
        }

        $userModel = new UserModel();
        $user = $userModel->findById($userId);

        // Handle form submissions
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $action = $_POST['action'] ?? '';
            $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ||
                      !empty($_POST['ajax']);
            
            if ($action == 'updateProfile') {
                // Update profile logic
                $name = $_POST['name'] ?? '';
                $email = $_POST['email'] ?? '';
                
                if (!empty($name) && !empty($email)) {
                    $result = $userModel->updateProfile($userId, $name, $email);
                    if ($result) {
                        // Update session data
                        $_SESSION['user']['name'] = $name;
                        $_SESSION['user']['email'] = $email;
                        
                        if ($isAjax) {
                            header('Content-Type: application/json');
                            echo json_encode(['success' => true, 'message' => 'Profile updated successfully!']);
                            exit;
                        }
                        $_SESSION['success'] = "Profile updated successfully!";
                    } else {
                        if ($isAjax) {
                            header('Content-Type: application/json');
                            echo json_encode(['success' => false, 'message' => 'Failed to update profile.']);
                            exit;
                        }
                        $_SESSION['error'] = "Failed to update profile.";
                    }
                } else {
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
                        exit;
                    }
                    $_SESSION['error'] = "Please fill in all required fields.";
                }
                
                if (!$isAjax) {
                    header('Location: ' . $this->baseUrl . 'profile');
                    exit;
                }
            }
            
            if ($action == 'changePassword') {
                // Change password logic
                $current_password = $_POST['current_password'] ?? '';
                $new_password = $_POST['new_password'] ?? '';
                $confirm_password = $_POST['confirm_password'] ?? '';
                
                if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
                    if ($new_password === $confirm_password) {
                        if (strlen($new_password) >= 6) {
                            $result = $userModel->changePassword($userId, $current_password, $new_password);
                            if ($result) {
                                if ($isAjax) {
                                    header('Content-Type: application/json');
                                    echo json_encode(['success' => true, 'message' => 'Password changed successfully!']);
                                    exit;
                                }
                                $_SESSION['success'] = "Password changed successfully!";
                            } else {
                                if ($isAjax) {
                                    header('Content-Type: application/json');
                                    echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
                                    exit;
                                }
                                $_SESSION['error'] = "Current password is incorrect.";
                            }
                        } else {
                            if ($isAjax) {
                                header('Content-Type: application/json');
                                echo json_encode(['success' => false, 'message' => 'New password must be at least 6 characters.']);
                                exit;
                            }
                            $_SESSION['error'] = "New password must be at least 6 characters.";
                        }
                    } else {
                        if ($isAjax) {
                            header('Content-Type: application/json');
                            echo json_encode(['success' => false, 'message' => 'New passwords do not match.']);
                            exit;
                        }
                        $_SESSION['error'] = "New passwords do not match.";
                    }
                } else {
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'Please fill in all password fields.']);
                        exit;
                    }
                    $_SESSION['error'] = "Please fill in all password fields.";
                }
                
                if (!$isAjax) {
                    header('Location: ' . $this->baseUrl . 'profile');
                    exit;
                }
            }
        }

        if ($userRole === 'admin') {
            $this->render_template('profile', [
                'baseUrl' => $this->baseUrl,
                'user' => $user
            ], 'admin');
        } else {
            $studentModel = new StudentModel();
            $student = $studentModel->getStudentByUserId($userId);
            
            $this->render_template('profile', [
                'baseUrl' => $this->baseUrl,
                'user' => $user,
                'student' => $student,
                'totalSessions' => 0,
                'totalSpent' => 0
            ], 'student');
        }
    }

    private function render_template(string $template = '', array $data = [], string $folder = 'student') {
        if ($template) {
            extract($data);
            include '../app/Views/' . $folder . '/' . $template . '.php';
            exit;
        }
    }
}