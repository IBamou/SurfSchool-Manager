<?php
namespace Ilyas\SurfManager\Controllers;

use Ilyas\SurfManager\Models\UserModel;
use Ilyas\SurfManager\Models\AssignmentModel;
use Ilyas\SurfManager\Models\SessionModel;
use Ilyas\SurfManager\Models\LessonModel;
use Ilyas\SurfManager\Models\StudentModel;
use Ilyas\SurfManager\Models\CoachModel;

class ProfileController {
    public $baseUrl;

    public function __construct() {
        $this->baseUrl = 'http://localhost/surfManager/';
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

        if ($userRole === 'admin') {
            $this->render_template('profile', [
                'baseUrl' => $this->baseUrl,
                'user' => $user
            ], 'admin');
            
        } else {
            $studentModel = new StudentModel();
            $student = $studentModel->getStudentByUserId($userId);
            
            $this->render_template('studentprofile', [
                'baseUrl' => $this->baseUrl,
                'user' => $user,
                'student' => $student,
                'totalSessions' => 0,
                'totalSpent' => 0
            ], 'student');
        }
    }


    public function editProfile() {
        $this->render_template('editProfile', ['baseUrl' => $this->baseUrl], 'student');
    }

    public function changePassword() {
        $this->render_template('changePassword', ['baseUrl' => $this->baseUrl], 'student');
    }

    private function render_template(string $template = '', array $data = [], string $folder = 'student') {
        if ($template) {
            extract($data);
            include '../app/Views/' . $folder . '/' . $template . '.php';
            exit;
        }
    }
}
