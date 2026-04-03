<?php
namespace App\Controllers;

use App\Models\SessionModel;
use App\Models\AssignmentModel;
use App\Models\StudentModel;

class BookingController {
    public $baseUrl;

    public function __construct() {
        $this->baseUrl = $this->getBaseUrl();
    }
    
    private function getBaseUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        return $protocol . $host . '/surfManager/';
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
        $result = $assignmentModel->removeAssignment($assignmentId);
        if ($result) {
            header("Location: " . $this->baseUrl . "sessions/" . $sessionId . "?success=Student removed from session");
        } else {
            header("Location: " . $this->baseUrl . "sessions/" . $sessionId . "?error=Failed to remove student");
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