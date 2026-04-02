<?php
namespace Ilyas\SurfManager\Controllers;

use Ilyas\SurfManager\Models\LessonModel;
use Ilyas\SurfManager\Models\SessionModel;

class LessonsController {
    public $baseUrl;

    public function __construct() {
        $this->baseUrl = 'http://localhost/surfManager/';
    }

    public function index() {
        $model = new LessonModel();
        
        $search = $_GET['search'] ?? '';
        $level = $_GET['level'] ?? '';

        if (!empty($search) || !empty($level)) {
            $lessons = $model->searchLessons($search, $level);
        } else {
            $lessons = $model->getLessons();
        }

        $data = [
            'siteName' => 'Surf Lessons',
            'baseUrl' => $this->baseUrl,
            'lessons' => $lessons
        ];

        $this->render_template('lessons', $data);
    }

    public function show(int $id = 0) {
        if (!empty($id) && is_numeric($id) && $id > 0) {
            $model = new LessonModel();
            $sessionModel = new SessionModel();
            
            $lesson = $model->getLesson($id);
            $sessions = $sessionModel->getSessionByLesson($id);
            
            if ($lesson) {
                $this->render_template('lesson', [
                    'baseUrl' => $this->baseUrl,
                    'lesson' => $lesson,
                    'sessions' => $sessions
                ]);
            } else {
                header("Location: " . $this->baseUrl . "lessons");
                exit;
            }
        } else {
            $this->index();
        }
    }

    public function add() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $model = new LessonModel();
            
            $data = [
                "title" => trim($_POST["title"] ?? ''),
                "description" => trim($_POST["description"] ?? ''),
                "level" => $_POST["level"] ?? 'Beginner',
            ];
            
            if (empty($data["title"])) {
                header("Location: " . $this->baseUrl . "lessons/add?error=Title is required");
                exit;
            }
            
            $model->addLesson($data);
            header("Location: " . $this->baseUrl . "lessons");
            exit;
        }

        $this->render_template('lessonForm', [
            'baseUrl' => $this->baseUrl,
            'isEditing' => false
        ]);
    }

    public function edit(int $id) {
        $model = new LessonModel();
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $data = [
                "title" => trim($_POST["title"] ?? ''),
                "description" => trim($_POST["description"] ?? ''),
                "level" => $_POST["level"] ?? 'Beginner',
            ];
            
            if (empty($data["title"])) {
                header("Location: " . $this->baseUrl . "lessons/" . $id . "/edit?error=Title is required");
                exit;
            }
            
            $model->updateLesson($id, $data);
            header("Location: " . $this->baseUrl . "lessons/" . $id);
            exit;
        }

        $lesson = $model->getLesson($id);
        
        if (!$lesson) {
            header("Location: " . $this->baseUrl . "lessons");
            exit;
        }
        
        $this->render_template('lessonForm', [
            'baseUrl' => $this->baseUrl,
            'isEditing' => true,
            'lesson' => $lesson
        ]);
    }

    public function delete(int $id) {
        $model = new LessonModel();            
        $result = $model->deleteLesson($id);
        if ($result) {
            header("Location: " . $this->baseUrl . "lessons?success=Lesson deleted successfully");
        } else {
            header("Location: " . $this->baseUrl . "lessons?error=Failed to delete lesson");
        }
        exit;
    }

    private function render_template(string $template = '', array $data = []) {
        if ($template) {
            extract($data);
            include '../app/Views/admin/' . $template . '.php';
            exit;
        }
    }
}
