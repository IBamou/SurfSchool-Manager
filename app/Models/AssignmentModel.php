<?php
namespace Ilyas\SurfManager\Models;

use Ilyas\SurfManager\Configs\Model;
use PDO;
use Exception;

class AssignmentModel extends Model {

    public $error = false;

    public function __construct() {
        parent::__construct();   
    }

    public function getSessionAssignments(int $sessionId) {
        try {
            $query = 'SELECT a.*, st.id as student_id, st.level as student_level, u.name as student_name, u.email as student_email
                      FROM assignments a
                      LEFT JOIN students st ON a.student_id = st.id
                      LEFT JOIN users u ON st.user_id = u.id
                      WHERE a.session_id = :session_id
                      ORDER BY a.assigned_at DESC';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':session_id', $sessionId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->error = true;
            return [];
        }
    }

    public function getStudentAssignments(int $studentId) {
        try {
            $query = 'SELECT a.*, s.*, l.title as lesson_title
                      FROM assignments a
                      LEFT JOIN sessions s ON a.session_id = s.id
                      LEFT JOIN lessons l ON s.lesson_id = l.id
                      WHERE a.student_id = :student_id
                      ORDER BY s.datetime DESC';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':student_id', $studentId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->error = true;
            return [];
        }
    }

    public function assignStudent(int $sessionId, int $studentId) {
        try {
            $checkQuery = 'SELECT id FROM assignments WHERE session_id = :session_id AND student_id = :student_id';
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bindParam(':session_id', $sessionId);
            $checkStmt->bindParam(':student_id', $studentId);
            $checkStmt->execute();
            
            if ($checkStmt->fetch()) {
                return false;
            }

            $this->db->beginTransaction();
            
            $updateQuery = 'UPDATE sessions SET spots_available = spots_available - 1 
                            WHERE id = :session_id AND spots_available > 0';
            $updateStmt = $this->db->prepare($updateQuery);
            $updateStmt->bindParam(':session_id', $sessionId);
            $updateStmt->execute();
            
            if ($updateStmt->rowCount() == 0) {
                $this->db->rollBack();
                return false;
            }

            $query = 'INSERT INTO assignments (session_id, student_id) VALUES (:session_id, :student_id)';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':session_id', $sessionId);
            $stmt->bindParam(':student_id', $studentId);
            $stmt->execute();
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            $this->error = true;
            return false;
        }
    }

    public function removeAssignment(int $assignmentId) {
        try {
            $this->db->beginTransaction();
            
            $query = 'SELECT session_id FROM assignments WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $assignmentId);
            $stmt->execute();
            $assignment = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$assignment) {
                $this->db->rollBack();
                return false;
            }
            
            $updateQuery = 'UPDATE sessions SET spots_available = spots_available + 1 WHERE id = :session_id';
            $updateStmt = $this->db->prepare($updateQuery);
            $updateStmt->bindParam(':session_id', $assignment['session_id']);
            $updateStmt->execute();
            
            $deleteQuery = 'DELETE FROM assignments WHERE id = :id';
            $deleteStmt = $this->db->prepare($deleteQuery);
            $deleteStmt->bindParam(':id', $assignmentId);
            $deleteStmt->execute();
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            $this->error = true;
            return false;
        }
    }

    public function updatePaymentStatus(int $assignmentId, string $status) {
        try {
            $query = 'UPDATE assignments SET payment_status = :status WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $assignmentId);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $this->error = true;
            return false;
        }
    }

    public function getAssignmentCount(int $sessionId) {
        try {
            $query = 'SELECT COUNT(*) as count FROM assignments WHERE session_id = :session_id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':session_id', $sessionId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            $this->error = true;
            return 0;
        }
    }
}
