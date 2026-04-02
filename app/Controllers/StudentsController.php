<?php
namespace Ilyas\SurfManager\Controllers;

use Ilyas\SurfManager\Models\StudentModel;

class StudentsController {
    public $baseUrl;

    public function __construct() {
        $this->baseUrl = 'http://localhost/surfManager/';
    }

    public function index() {
        $model = new StudentModel();
        
        $model->generateStatistics();
        $students = $model->getStudents();

        $data = [
            'baseUrl' => $this->baseUrl,
            'totalStudents' => $model->totalStudents,
            'beginnerCount' => $model->beginnerCount,
            'intermediateCount' => $model->intermediateCount,
            'advancedCount' => $model->advancedCount,
            'students' => $students
        ];

        $this->render_template('students', $data);
    }

    public function show(int $id = 0) {
        if (!empty($id) && is_numeric($id) && $id > 0) {
            $model = new StudentModel();
            
            $student = $model->getStudent($id);
            
            if ($student) {
                $assignments = $model->getStudentAssignments($id);
                
                $this->render_template('student', [
                    'baseUrl' => $this->baseUrl,
                    'student' => $student,
                    'assignments' => $assignments
                ]);
            } else {
                header("Location: " . $this->baseUrl . "students");
                exit;
            }
        } else {
            $this->index();
        }
    }

    public function edit(int $id) {
        $model = new StudentModel();
        $student = $model->getStudent($id);
        
        if (!$student) {
            header("Location: " . $this->baseUrl . "students");
            exit;
        }
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $level = $_POST['level'] ?? 'Beginner';
            $model->updateStudentLevel($id, $level);
            header("Location: " . $this->baseUrl . "students/" . $id);
            exit;
        }

        $this->render_template('studentForm', [
            'baseUrl' => $this->baseUrl,
            'student' => $student
        ]);
    }

    public function updatePayment(int $assignmentId, int $studentId) {
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

    public function updateLevel(int $id) {
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

    private function render_template(string $template = '', array $data = []) {
        if ($template) {
            extract($data);
            include '../app/Views/admin/' . $template . '.php';
            exit;
        }
    }
}
