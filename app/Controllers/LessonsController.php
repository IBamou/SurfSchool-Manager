<?php
namespace App\Controllers;

use App\Models\LessonModel;
use App\Models\SessionModel;

class LessonsController extends BaseController {

    public function index(): void {
        $model = new LessonModel();
        
        $search = $_GET['search'] ?? '';
        $level = $_GET['level'] ?? '';

        if (!empty($search) || !empty($level)) {
            $lessons = $model->searchLessons($search, $level);
        } else {
            $lessons = $model->getLessons();
        }

        $this->renderAdmin('lessons', [
            'siteName' => 'Surf Lessons',
            'lessons' => $lessons
        ]);
    }

    public function show(int $id = 0): void {
        if (!empty($id) && is_numeric($id) && $id > 0) {
            $model = new LessonModel();
            $sessionModel = new SessionModel();
            
            $lesson = $model->getLesson($id);
            
            if ($lesson) {
                $this->renderAdmin('lesson', [
                    'lesson' => $lesson,
                    'sessions' => $sessionModel->getSessionByLesson($id)
                ]);
            } else {
                $this->redirect('lessons');
            }
        } else {
            $this->index();
        }
    }

    public function add(): void {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $model = new LessonModel();
            
            $data = [
                "title" => trim($_POST["title"] ?? ''),
                "description" => trim($_POST["description"] ?? ''),
                "level" => $_POST["level"] ?? 'Beginner',
            ];
            
            if (empty($data["title"])) {
                $this->redirectWithError('lessons/add', 'Title is required');
            }
            
            $model->addLesson($data);
            $this->redirect('lessons');
        }

        $this->renderAdmin('lessonForm', ['isEditing' => false]);
    }

    public function edit(int $id): void {
        $model = new LessonModel();
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $data = [
                "title" => trim($_POST["title"] ?? ''),
                "description" => trim($_POST["description"] ?? ''),
                "level" => $_POST["level"] ?? 'Beginner',
            ];
            
            if (empty($data["title"])) {
                $this->redirectWithError('lessons/edit/' . $id, 'Title is required');
            }
            
            $model->updateLesson($id, $data);
            $this->redirect('lessons/' . $id);
        }

        $lesson = $model->getLesson($id);
        
        if (!$lesson) {
            $this->redirect('lessons');
        }
        
        $this->renderAdmin('lessonForm', [
            'isEditing' => true,
            'lesson' => $lesson
        ]);
    }

    public function delete(int $id): void {
        $model = new LessonModel();            
        $result = $model->deleteLesson($id);
        
        if ($result) {
            $this->redirectWithSuccess('lessons', 'Lesson deleted successfully');
        } else {
            $this->redirectWithError('lessons', 'Failed to delete lesson');
        }
    }
}
