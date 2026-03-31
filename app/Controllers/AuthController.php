<?php
namespace Ilyas\SurfManager\Controllers;

use Ilyas\SurfManager\Models\LessonModel;
use Ilyas\SurfManager\Models\SessionModel;
use Ilyas\SurfManager\Models\AssignmentModel;

class AuthController {
    public $baseUrl;

    public function __construct() {
        $this->baseUrl = 'http://localhost/surfManager/';
    }

    public function login() {
        $this->render_template('login', ['baseUrl' => $this->baseUrl]);
    }

    public function signup() {
        $this->render_template('signup', ['baseUrl' => $this->baseUrl]);
    }

    public function logout() {
        // LOGIC HERE: session_destroy() and redirect
        header("Location: " . $this->baseUrl . "home");
        exit;
    }

    public function dashboard() {
        // TODO: Get current user from session
        $user = [
            'name' => 'Demo Student',
            'email' => 'demo@surf.com',
            'level' => 'Intermediate',
            'created_at' => '2024-01-15'
        ];

        $assignmentModel = new AssignmentModel();
        $sessionModel = new SessionModel();

        // TODO: Get real data based on logged in user
        $mySessions = [];
        $enrolledSessions = 0;
        $completedSessions = 0;
        $totalSpent = 0;

        $lessonModel = new LessonModel();
        $recommendedLessons = $lessonModel->getLessons();

        $this->render_template('studentDashboard', [
            'baseUrl' => $this->baseUrl,
            'user' => $user,
            'mySessions' => $mySessions,
            'enrolledSessions' => $enrolledSessions,
            'completedSessions' => $completedSessions,
            'totalSpent' => $totalSpent,
            'recommendedLessons' => $recommendedLessons
        ]);
    }

    public function lessons() {
        $lessonModel = new LessonModel();
        $lessons = $lessonModel->getLessons();

        $this->render_template('studentLessons', [
            'baseUrl' => $this->baseUrl,
            'lessons' => $lessons
        ]);
    }

    public function profile() {
        // TODO: Get current user from session
        $user = [
            'name' => 'Demo Student',
            'email' => 'demo@surf.com',
            'level' => 'Intermediate',
            'created_at' => '2024-01-15'
        ];

        // TODO: Get real stats from database
        $totalSessions = 5;
        $totalSpent = 250.00;

        $this->render_template('profile', [
            'baseUrl' => $this->baseUrl,
            'user' => $user,
            'totalSessions' => $totalSessions,
            'totalSpent' => $totalSpent
        ]);
    }

    public function editProfile() {
        $this->render_template('editProfile', ['baseUrl' => $this->baseUrl]);
    }

    public function changePassword() {
        $this->render_template('changePassword', ['baseUrl' => $this->baseUrl]);
    }

    private function render_template(string $template = '', array $data = []) {
        if ($template) {
            extract($data);
            include '../app/Views/' . $template . '.php';
            exit;
        }
    }
}
