<?php
namespace App\Controllers;

use App\Models\LessonModel;
use App\Models\SessionModel;
use App\Models\StudentModel;
use App\Models\AssignmentModel;
use App\Models\CoachModel;

class DashboardController extends BaseController {

    public function dashboard(): void {
        $userRole = $_SESSION['user']['role'] ?? 'user';

        if ($userRole === 'admin') {
            $this->index();
        } else {
            $this->studentDashboard();
        }
    }

    private function index(): void {
        $lessonModel = new LessonModel();
        $sessionModel = new SessionModel();
        $studentModel = new StudentModel();
        $assignmentModel = new AssignmentModel();
        $coachModel = new CoachModel();

        $studentModel->generateStatistics();

        $this->renderAdmin('dashboard', [
            'totalLessons' => count($lessonModel->getLessons()),
            'totalSessions' => count($sessionModel->getSessions()),
            'totalStudents' => $studentModel->totalStudents,
            'totalAssignments' => count($assignmentModel->getAllAssignments()),
            'totalCoaches' => count($coachModel->getCoaches())
        ]);
    }

    private function studentDashboard(): void {
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

        $this->renderStudent('studentDashboard', [
            'user' => $_SESSION['user'],
            'mySessions' => $mySessions,
            'enrolledSessions' => $enrolledSessions,
            'completedSessions' => $completedSessions,
            'totalSpent' => $totalSpent,
            'recommendedLessons' => (new LessonModel())->getLessons()
        ]);
    }

    protected function renderStudent(string $template, array $data = []): void {
        $data['baseUrl'] = $this->baseUrl;
        extract($data);
        include '../app/Views/student/' . $template . '.php';
        exit;
    }
}
