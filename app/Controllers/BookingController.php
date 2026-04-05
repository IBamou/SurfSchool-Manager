<?php
namespace App\Controllers;

use App\Models\SessionModel;
use App\Models\AssignmentModel;
use App\Models\StudentModel;

class BookingController extends BaseController {

    public function book(int $id): void {
        $model = new SessionModel();
        $session = $model->getSession($id);
        
        if (!$session || $session['status'] !== 'available' || $session['spots_available'] <= 0) {
            $this->redirectWithError('sessions/' . $id, 'Session is unavailable');
        }
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $studentId = (int)($_POST["student_id"] ?? 0);
            
            if (empty($studentId)) {
                $this->redirectWithError('sessions/' . $id . '/book', 'Student required');
            }
            
            $assignmentModel = new AssignmentModel();
            $result = $assignmentModel->assignStudent($id, $studentId);
            
            if ($result) {
                $this->redirectWithSuccess('sessions/' . $id, 'Student booked successfully');
            } else {
                $this->redirectWithError('sessions/' . $id, 'Booking failed');
            }
        }
        
        $studentModel = new StudentModel();
        $this->renderAdmin('sessionBook', [
            'session' => $session,
            'students' => $studentModel->getStudents()
        ]);
    }

    public function cancelBooking(int $assignmentId, int $sessionId): void {
        $assignmentModel = new AssignmentModel();
        $result = $assignmentModel->removeAssignment($assignmentId);
        
        if ($result) {
            $this->redirectWithSuccess('sessions/' . $sessionId, 'Student removed from session');
        } else {
            $this->redirectWithError('sessions/' . $sessionId, 'Failed to remove student');
        }
    }
}
