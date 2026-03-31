<?php
namespace Ilyas\SurfManager\Models;

use Ilyas\SurfManager\Configs\Model;
use PDO;
use Exception;

class LessonModel extends Model {

    public $totalLessons = 0;
    public $error = false;

    public function __construct() {
        parent::__construct();   
    }

    public function getLessons() {
        try {
            $query = 'SELECT * FROM lessons ORDER BY created_at DESC';
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->error = true;
            return [];
        }
    }

    public function getLesson(int $id) {
        try {
            $query = 'SELECT * FROM lessons WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->error = true;
            return null;
        }
    }

    public function addLesson(array $data) {
        try {
            $query = 'INSERT INTO lessons (title, description, level) VALUES (:title, :description, :level)';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':level', $data['level']);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $this->error = true;
            echo $e->getMessage();
            return false;
        }
    }

    public function updateLesson(int $id, array $data) {
        try {
            $query = 'UPDATE lessons SET title = :title, description = :description, level = :level WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':level', $data['level']);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $this->error = true;
            return false;
        }
    }

    public function deleteLesson(int $id) {
        try {
            $query = 'DELETE FROM lessons WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $this->error = true;
            return false;
        }
    }

    public function searchLessons(string $search = '', string $level = '') {
        try {
            $query = 'SELECT * FROM lessons WHERE 1=1';
            $params = [];

            if (!empty($search)) {
                $query .= ' AND (title LIKE :search OR description LIKE :search)';
                $params['search'] = "%$search%";
            }

            if (!empty($level)) {
                $query .= ' AND level = :level';
                $params['level'] = $level;
            }

            $query .= ' ORDER BY created_at DESC';

            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->error = true;
            return [];
        }
    }

    public function getLessonSessions(int $lessonId) {
        try {
            $query = 'SELECT s.*, c.name as coach_name, c.speciality as coach_speciality
                      FROM sessions s
                      LEFT JOIN coaches c ON s.coach_id = c.id
                      WHERE s.lesson_id = :lesson_id
                      ORDER BY s.datetime DESC';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':lesson_id', $lessonId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->error = true;
            return [];
        }
    }
}
