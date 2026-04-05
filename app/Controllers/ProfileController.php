<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\StudentModel;

class ProfileController extends BaseController {

    public function profile(): void {
        $userId = $_SESSION['user']['id'] ?? null;
        $userRole = $_SESSION['user']['role'] ?? 'user';

        if (!$userId) {
            $this->redirect('login');
        }

        $userModel = new UserModel();
        $user = $userModel->findById($userId);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->handleFormSubmission($userModel, $userId);
        }

        if ($userRole === 'admin') {
            $this->renderAdmin('profile', ['user' => $user]);
        } else {
            $studentModel = new StudentModel();
            $student = $studentModel->getStudentByUserId($userId);
            
            $this->render('student/profile', [
                'user' => $user,
                'student' => $student,
                'totalSessions' => 0,
                'totalSpent' => 0
            ]);
        }
    }

    private function handleFormSubmission(UserModel $userModel, int $userId): void {
        $action = $_POST['action'] ?? '';
        $isAjax = $this->isAjaxRequest();
        
        if ($action == 'updateProfile') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            
            if (!empty($name) && !empty($email)) {
                $result = $userModel->updateProfile($userId, $name, $email);
                if ($result) {
                    $_SESSION['user']['name'] = $name;
                    $_SESSION['user']['email'] = $email;
                    $this->jsonResponse($isAjax, true, 'Profile updated successfully!');
                } else {
                    $this->jsonResponse($isAjax, false, 'Failed to update profile.');
                }
            } else {
                $this->jsonResponse($isAjax, false, 'Please fill in all required fields.');
            }
        }
        
        if ($action == 'changePassword') {
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                $this->jsonResponse($isAjax, false, 'Please fill in all password fields.');
            }
            
            if ($new_password !== $confirm_password) {
                $this->jsonResponse($isAjax, false, 'New passwords do not match.');
            }
            
            if (strlen($new_password) < 6) {
                $this->jsonResponse($isAjax, false, 'New password must be at least 6 characters.');
            }
            
            $result = $userModel->changePassword($userId, $current_password, $new_password);
            $this->jsonResponse($isAjax, $result, $result ? 'Password changed successfully!' : 'Current password is incorrect.');
        }
        
        if (!$isAjax) {
            $this->redirect('profile');
        }
    }

    private function isAjaxRequest(): bool {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ||
                !empty($_POST['ajax']);
    }

    private function jsonResponse(bool $isAjax, bool $success, string $message): void {
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success, 'message' => $message]);
            exit;
        }
        if ($success) {
            $_SESSION['success'] = $message;
        } else {
            $_SESSION['error'] = $message;
        }
        $this->redirect('profile');
    }
}
