<?php
namespace Ilyas\SurfManager\Models;

use Ilyas\SurfManager\Configs\Model;
use PDO;
use Exception;

class StudentModel extends Model {

    public $totalStudents = 0;
    public $beginnerCount = 0;
    public $intermediateCount = 0;
    public $advancedCount = 0;
    public $error = false;

    public function __construct() {
        parent::__construct();   
    }

    public function getStudents() {
        try {
            $query = 'SELECT s.*, u.name, u.email
                      FROM students s
                      LEFT JOIN users u ON s.user_id = u.id
                      ORDER BY u.name ASC';
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->error = true;
            return [];
        }
    }

    public function getStudent(int $id) {
        try {
            $query = 'SELECT s.*, u.name, u.email
                      FROM students s
                      LEFT JOIN users u ON s.user_id = u.id
                      WHERE s.id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->error = true;
            return null;
        }
    }

    public function getStudentAssignments(int $studentId) {
        try {
            $query = 'SELECT a.*, s.*, l.title as lesson_title, l.level as lesson_level,
                      c.name as coach_name
                      FROM assignments a
                      LEFT JOIN sessions s ON a.session_id = s.id
                      LEFT JOIN lessons l ON s.lesson_id = l.id
                      LEFT JOIN coaches c ON s.coach_id = c.id
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

    public function updateStudentLevel(int $id, string $level) {
        try {
            $query = 'UPDATE students SET level = :level WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':level', $level);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
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

    public function generateStatistics() {
        try {
            $query = 'SELECT level, COUNT(*) as count FROM students GROUP BY level';
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $this->totalStudents = 0;
            $this->beginnerCount = 0;
            $this->intermediateCount = 0;
            $this->advancedCount = 0;
            
            foreach ($results as $row) {
                $this->totalStudents += $row['count'];
                switch ($row['level']) {
                    case 'Beginner':
                        $this->beginnerCount = $row['count'];
                        break;
                    case 'Intermediate':
                        $this->intermediateCount = $row['count'];
                        break;
                    case 'Advanced':
                    case 'Expert':
                        $this->advancedCount += $row['count'];
                        break;
                }
            }
        } catch (Exception $e) {
            $this->error = true;
        }
    }
}
