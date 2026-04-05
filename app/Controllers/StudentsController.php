<?php
namespace App\Controllers;

use App\Models\StudentModel;

class StudentsController extends BaseController {

    public function index(): void {
        $model = new StudentModel();
        $model->generateStatistics();

        $this->renderAdmin('students', [
            'totalStudents' => $model->totalStudents,
            'beginnerCount' => $model->beginnerCount,
            'intermediateCount' => $model->intermediateCount,
            'advancedCount' => $model->advancedCount,
            'students' => $model->getStudents()
        ]);
    }

    public function show(int $id = 0): void {
        if (!empty($id) && is_numeric($id) && $id > 0) {
            $model = new StudentModel();
            $student = $model->getStudent($id);
            
            if ($student) {
                $this->renderAdmin('student', [
                    'student' => $student,
                    'assignments' => $model->getStudentAssignments($id)
                ]);
            } else {
                $this->redirect('students');
            }
        } else {
            $this->index();
        }
    }

    public function edit(int $id): void {
        $model = new StudentModel();
        $student = $model->getStudent($id);
        
        if (!$student) {
            $this->redirect('students');
        }
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $level = $_POST['level'] ?? 'Beginner';
            $model->updateStudentLevel($id, $level);
            $this->redirect('students/' . $id);
        }

        $this->renderAdmin('studentForm', ['student' => $student]);
    }

    public function updatePayment(int $assignmentId, int $studentId): void {
        header('Content-Type: application/json');
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $model = new StudentModel();
            $status = $_POST['payment_status'] ?? 'pending';
            $result = $model->updatePaymentStatus($assignmentId, $status);
            echo json_encode(['success' => $result]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
        }
        exit;
    }

    public function updateLevel(int $id): void {
        header('Content-Type: application/json');
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $model = new StudentModel();
            $level = $_POST['level'] ?? 'Beginner';
            $result = $model->updateStudentLevel($id, $level);
            echo json_encode(['success' => $result]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
        }
        exit;
    }
}
