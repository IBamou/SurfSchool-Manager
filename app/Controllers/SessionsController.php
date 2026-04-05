<?php
namespace App\Controllers;

use App\Models\SessionModel;
use App\Models\LessonModel;
use App\Models\CoachModel;
use App\Models\AssignmentModel;

class SessionsController extends BaseController {

    public function sessions(): void {
        $userRole = $_SESSION['user']['role'] ?? 'user';

        if ($userRole === 'admin') {
            $this->index();
        } else {
            $this->studentSessions();
        }
    }

    private function studentSessions(): void {
        $assignmentModel = new AssignmentModel();
        $sessionModel = new SessionModel();
        
        $studentId = $_SESSION['student']['id'];
        $sessions = [];
        
        foreach ($assignmentModel->getStudentAssignments($studentId) as $assignment) {
            $session = $sessionModel->getSession($assignment['session_id']);
            if ($session) {
                $session['payment_status'] = $assignment['payment_status'];
                $sessions[] = $session;
            }
        }

        $this->renderStudent('studentSessions', ['sessions' => $sessions]);
    }

    private function index(): void {
        $model = new SessionModel();
        $lessonModel = new LessonModel();
        $coachModel = new CoachModel();
        
        $model->generateStatistics();
        
        $search = $_GET['search'] ?? '';
        $level = $_GET['level'] ?? '';
        $status = $_GET['status'] ?? '';
        $coachId = $_GET['coach'] ?? '';
        $noCoachOnly = ($coachId === 'none');
        $coachId = $noCoachOnly ? '' : $coachId;

        if (!empty($search) || !empty($level) || !empty($status) || !empty($coachId) || $noCoachOnly) {
            $sessions = $model->searchSessions($search, $level, $status, $coachId, $noCoachOnly);
        } else {
            $sessions = $model->getSessions();
        }

        $this->renderAdmin('sessions', [
            'siteName' => 'Surf Sessions',
            'totalSessions' => $model->totalSessions,
            'availableSessions' => $model->availableSessions,
            'completedSessions' => $model->completedSessions,
            'sessions' => $sessions,
            'lessons' => $lessonModel->getLessons(),
            'coaches' => $coachModel->getCoaches()
        ]);
    }

    public function show(int $id = 0): void {
        if (!empty($id) && is_numeric($id) && $id > 0) {
            $model = new SessionModel();
            $session = $model->getSession($id);
            
            if ($session) {
                $this->renderAdmin('session', [
                    'session' => $session,
                    'assignments' => (new AssignmentModel())->getSessionAssignments($id)
                ]);
            } else {
                $this->redirect('sessions');
            }
        } else {
            $this->index();
        }
    }

    public function add(): void {
        $lessonModel = new LessonModel();
        $coachModel = new CoachModel();
        
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
                $this->redirectWithError('sessions/add', 'Lesson and Coach are required');
            }
            
            $model->addSession($data);
            unset($_SESSION["lesson_id"]);
            $this->redirect('sessions');
        }
        
        if (isset($_GET["lesson_id"])) {
            $_SESSION["lesson_id"] = $_GET["lesson_id"];
        }
        
        $this->renderAdmin('sessionForm', [
            'lessons' => $lessonModel->getLessons(),
            'coaches' => $coachModel->getCoaches(),
            'isEditing' => false,
        ]);
    }

    public function edit(int $id): void {
        $model = new SessionModel();
        $session = $model->getSession($id);
        
        if (!$session) {
            $this->redirect('sessions');
        }
        
        $lessonModel = new LessonModel();
        $coachModel = new CoachModel();
        $assignmentModel = new AssignmentModel();
        
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
                $this->redirectWithError('sessions/edit/' . $id, 'Lesson and Coach are required');
            }
            
            $model->updateSession($id, $data);
            $this->redirect('sessions/' . $id);
        }

        $this->renderAdmin('sessionForm', [
            'lessons' => $lessonModel->getLessons(),
            'coaches' => $coachModel->getCoaches(),
            'isEditing' => true,
            'session' => $session,
            'assignments' => $assignmentModel->getSessionAssignments($id)
        ]);
    }

    public function delete(int $id): void {
        $model = new SessionModel();            
        $result = $model->deleteSession($id);
        
        if ($result) {
            $this->redirectWithSuccess('sessions', 'Session deleted successfully');
        } else {
            $this->redirectWithError('sessions', 'Failed to delete session');
        }
    }

    private function renderStudent(string $template, array $data = []): void {
        $data['baseUrl'] = $this->baseUrl;
        extract($data);
        include '../app/Views/student/' . $template . '.php';
        exit;
    }
}
