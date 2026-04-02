<?php
namespace Ilyas\SurfManager\Controllers;

use Ilyas\SurfManager\Models\SessionModel;
use Ilyas\SurfManager\Models\LessonModel;
use Ilyas\SurfManager\Models\CoachModel;
use Ilyas\SurfManager\Models\AssignmentModel;

class SessionsController {
    public $baseUrl;

    public function __construct() {
        $this->baseUrl = 'http://localhost/surfManager/';
    }

    public function sessions() {
        $userRole = $_SESSION['user']['role'] ?? 'user';

        if ($userRole === 'admin') {
            $this->index();
        } else {
            $this->studentSessions();
        }
    }

    public function studentSessions() {
        $assignmentModel = new AssignmentModel();
        $sessionModel = new SessionModel();
        
        $studentId = $_SESSION['student']['id'];
        
        $assignments = $assignmentModel->getStudentAssignments($studentId);
        $sessions = [];
        
        foreach ($assignments as $assignment) {
            $session = $sessionModel->getSession($assignment['session_id']);
            if ($session) {
                $session['payment_status'] = $assignment['payment_status'];
                $sessions[] = $session;
            }
        }

        $this->render_template('studentSessions', [
            'baseUrl' => $this->baseUrl,
            'sessions' => $sessions
        ], 'student');
    }

    public function index() {
        $model = new SessionModel();
        $lessonModel = new LessonModel();
        $coachModel = new CoachModel();
        
        $model->generateStatistics();
        $lessons = $lessonModel->getLessons();
        $coaches = $coachModel->getCoaches();
        
        $search = $_GET['search'] ?? '';
        $level = $_GET['level'] ?? '';
        $status = $_GET['status'] ?? '';
        $coachId = $_GET['coach'] ?? '';

        if (!empty($search) || !empty($level) || !empty($status) || !empty($coachId)) {
            $sessions = $model->searchSessions($search, $level, $status, $coachId);
        } else {
            $sessions = $model->getSessions();
        }

        $data = [
            'siteName' => 'Surf Sessions',
            'baseUrl' => $this->baseUrl,
            'totalSessions' => $model->totalSessions,
            'availableSessions' => $model->availableSessions,
            'completedSessions' => $model->completedSessions,
            'sessions' => $sessions,
            'lessons' => $lessons,
            'coaches' => $coaches
        ];

        $this->render_template('sessions', $data, 'admin');
    }

    public function show(int $id = 0) {
        if (!empty($id) && is_numeric($id) && $id > 0) {
            $model = new SessionModel();
            $assignmentModel = new AssignmentModel();
            
            $session = $model->getSession($id);
            $assignments = $assignmentModel->getSessionAssignments($id);
            
            if ($session) {
                $this->render_template('session', [
                    'baseUrl' => $this->baseUrl,
                    'session' => $session,
                    'assignments' => $assignments
                ], 'admin');
            } else {
                header("Location: " . $this->baseUrl . "sessions");
                exit;
            }
        } else {
            $this->index();
        }
    }

    public function add() {
        $lessonModel = new LessonModel();
        $coachModel = new CoachModel();
        $lessons = $lessonModel->getLessons();
        $coaches = $coachModel->getCoaches();
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $model = new SessionModel();
            
            $data = [
                "lesson_id" => (int)($_POST["lesson_id"] ?? 0),
                "coach_id" => (int)($_POST["coach_id"] ?? 0),
                "datetime" => $_POST["datetime"] ?? date('Y-m-d H:i:s'),
                "duration" => (int)($_POST["duration"] ?? 60),
                "location" => trim($_POST["location"] ?? ''),
                "price" => (float)($_POST["price"] ?? 0),
                "requirements" => trim($_POST["requirements"] ?? ''),
                "max_spots" => (int)($_POST["max_spots"] ?? 8),
                "spots_available" => (int)($_POST["max_spots"] ?? 8),
                "status" => 'available',
            ];
            
            if (empty($data["lesson_id"]) || empty($data["coach_id"])) {
                header("Location: " . $this->baseUrl . "sessions/add?error=Lesson and Coach are required");
                exit;
            }
            
            $model->addSession($data);
            unset($_SESSION["lesson_id"]);
            header("Location: " . $this->baseUrl . "sessions");
            exit;
        }
        if (isset($_GET["lesson_id"])) {
            $_SESSION["lesson_id"] = $_GET["lesson_id"];
        }
        $this->render_template('sessionForm', [
            'baseUrl' => $this->baseUrl,
            'lessons' => $lessons,
            'coaches' => $coaches,
            'isEditing' => false,
        ], 'admin');
    }

    public function edit(int $id) {
        $model = new SessionModel();
        $lessonModel = new LessonModel();
        $coachModel = new CoachModel();
        $assignmentModel = new AssignmentModel();
        
        $lessons = $lessonModel->getLessons();
        $coaches = $coachModel->getCoaches();
        $session = $model->getSession($id);
        $assignments = $assignmentModel->getSessionAssignments($id);
        
        if (!$session) {
            header("Location: " . $this->baseUrl . "sessions");
            exit;
        }
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $data = [
                "lesson_id" => (int)($_POST["lesson_id"] ?? 0),
                "coach_id" => (int)($_POST["coach_id"] ?? 0),
                "datetime" => $_POST["datetime"] ?? date('Y-m-d H:i:s'),
                "duration" => (int)($_POST["duration"] ?? 60),
                "location" => trim($_POST["location"] ?? ''),
                "price" => (float)($_POST["price"] ?? 0),
                "requirements" => trim($_POST["requirements"] ?? ''),
                "max_spots" => (int)($_POST["max_spots"] ?? 8),
                "spots_available" => (int)($_POST["spots_available"] ?? 8),
                "status" => $_POST["status"] ?? 'available',
            ];
            
            if (empty($data["lesson_id"]) || empty($data["coach_id"])) {
                header("Location: " . $this->baseUrl . "sessions/" . $id . "/edit?error=Lesson and Coach are required");
                exit;
            }
            
            $model->updateSession($id, $data);
            header("Location: " . $this->baseUrl . "sessions/" . $id);
            exit;
        }

        $this->render_template('sessionForm', [
            'baseUrl' => $this->baseUrl,
            'lessons' => $lessons,
            'coaches' => $coaches,
            'isEditing' => true,
            'session' => $session,
            'assignments' => $assignments
        ], 'admin');
    }

    public function delete(int $id) {
        $model = new SessionModel();            
        $result = $model->deleteSession($id);
        if ($result) {
            header("Location: " . $this->baseUrl . "sessions?success=Session deleted successfully");
        } else {
            header("Location: " . $this->baseUrl . "sessions?error=Failed to delete session");
        }
        exit;
    }

    private function render_template(string $template = '', array $data = [], string $folder = '') {
        if ($template) {
            extract($data);
            include '../app/Views/' . $folder . '/' . $template . '.php';
            exit;
        }
    }
}