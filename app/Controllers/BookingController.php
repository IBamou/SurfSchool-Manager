<?php
namespace Ilyas\SurfManager\Controllers;

use Ilyas\SurfManager\Models\SessionModel;
use Ilyas\SurfManager\Models\AssignmentModel;
use Ilyas\SurfManager\Models\StudentModel;

class BookingController {
    public $baseUrl;

    public function __construct() {
        $this->baseUrl = 'http://localhost/surfManager/';
    }

    public function book(int $id) {
        $model = new SessionModel();
        $session = $model->getSession($id);
        
        if (!$session || $session['status'] !== 'available' || $session['spots_available'] <= 0) {
            header("Location: " . $this->baseUrl . "sessions/" . $id . "?error=unavailable");
            exit;
        }
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $studentId = (int)($_POST["student_id"] ?? 0);
            
            if (empty($studentId)) {
                header("Location: " . $this->baseUrl . "sessions/" . $id . "/book?error=Student required");
                exit;
            }
            
            $assignmentModel = new AssignmentModel();
            $result = $assignmentModel->assignStudent($id, $studentId);
            
            if ($result) {
                header("Location: " . $this->baseUrl . "sessions/" . $id . "?success=booked");
            } else {
                header("Location: " . $this->baseUrl . "sessions/" . $id . "?error=booking_failed");
            }
            exit;
        }
        
        $studentModel = new StudentModel();
        $students = $studentModel->getStudents();
        $this->render_template('sessionBook', [
            'baseUrl' => $this->baseUrl,
            'session' => $session,
            'students' => $students
        ]);
    }

    public function cancelBooking(int $assignmentId, int $sessionId) {
        $assignmentModel = new AssignmentModel();
        $assignmentModel->removeAssignment($assignmentId);
        header("Location: " . $this->baseUrl . "sessions/" . $sessionId);
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