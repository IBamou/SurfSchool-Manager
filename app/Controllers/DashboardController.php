<?php
namespace Ilyas\SurfManager\Controllers;

use Ilyas\SurfManager\Models\LessonModel;
use Ilyas\SurfManager\Models\SessionModel;
use Ilyas\SurfManager\Models\StudentModel;
use Ilyas\SurfManager\Models\AssignmentModel;
use Ilyas\SurfManager\Models\CoachModel;

class DashboardController {
    public $baseUrl;

    public function __construct() {
        $this->baseUrl = 'http://localhost/surfManager/';
    }

    public function dashboard() {
        $userRole = $_SESSION['user']['role'] ?? 'user';

        if ($userRole === 'admin') {
            $this->index();
        } else {
            $this->studentDashboard();
        }
    }

    public function index() {
        $lessonModel = new LessonModel();
        $sessionModel = new SessionModel();
        $studentModel = new StudentModel();
        $assignmentModel = new AssignmentModel();
        $coachModel = new CoachModel();

        $totalLessons = count($lessonModel->getLessons());
        $totalSessions = count($sessionModel->getSessions());
        $studentModel->generateStatistics();
        $totalStudents = $studentModel->totalStudents;
        $totalAssignments = count($assignmentModel->getAllAssignments());
        $totalCoaches = count($coachModel->getCoaches());

        $this->render_template('dashboard', [
            'baseUrl' => $this->baseUrl,
            'totalLessons' => $totalLessons,
            'totalSessions' => $totalSessions,
            'totalStudents' => $totalStudents,
            'totalAssignments' => $totalAssignments,
            'totalCoaches' => $totalCoaches
        ], 'admin');
    }

    private function studentDashboard() {
        $assignmentModel = new AssignmentModel();
        $sessionModel = new SessionModel();

        $mySessions = [];
        $enrolledSessions = 0;
        $completedSessions = 0;
        $totalSpent = 0;
        $studentId = $_SESSION['student']['id'];
        
        $assignments = $assignmentModel->getStudentAssignments($studentId);
        
        foreach ($assignments as $assignment) {
            $session = $sessionModel->getSession($assignment['session_id']);
            if ($session) {
                $session['payment_status'] = $assignment['payment_status'];
                $mySessions[] = $session;
            }
        }

        $enrolledSessions = count($mySessions);
        
        foreach ($mySessions as $session) {
            if ($session['payment_status'] === 'paid') {
                $completedSessions++;
                $totalSpent += $session['price'] ?? 0;
            }
        }

        $lessonModel = new LessonModel();
        $recommendedLessons = $lessonModel->getLessons();

        $this->render_template('studentDashboard', [
            'baseUrl' => $this->baseUrl,
            'user' => $_SESSION['user'],
            'mySessions' => $mySessions,
            'enrolledSessions' => $enrolledSessions,
            'completedSessions' => $completedSessions,
            'totalSpent' => $totalSpent,
            'recommendedLessons' => $recommendedLessons
        ], 'student');
    }

    private function render_template(string $template = '', array $data = [], string $folder = '') {
        if ($template) {
            extract($data);
            include '../app/Views/' . $folder . '/' . $template . '.php';
            exit;
        }
    }
}