<?php
namespace Ilyas\SurfManager\Models;

use Ilyas\SurfManager\Configs\Model;
use PDO;
use Exception;

class SessionModel extends Model {

    public $totalSessions = 0;
    public $availableSessions = 0;
    public $completedSessions = 0;
    public $error = false;

    public function __construct() {
        parent::__construct();   
    }

    public function getSessionByLesson(int $lessonId) {
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

    public function getSessions() {
        try {
            $query = 'SELECT s.*, l.title as lesson_title, l.level as lesson_level,
                      c.name as coach_name, c.speciality as coach_speciality
                      FROM sessions s
                      LEFT JOIN lessons l ON s.lesson_id = l.id
                      LEFT JOIN coaches c ON s.coach_id = c.id
                      ORDER BY s.datetime DESC';
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->error = true;
            return [];
        }
    }

    public function getSession(int $id) {
        try {
            $query = 'SELECT s.*, l.title as lesson_title, l.description as lesson_description, l.level as lesson_level,
                      c.name as coach_name, c.email as coach_email, c.speciality as coach_speciality, c.experience as coach_experience
                      FROM sessions s
                      LEFT JOIN lessons l ON s.lesson_id = l.id
                      LEFT JOIN coaches c ON s.coach_id = c.id
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

    public function addSession(array $data) {
        try {
            $query = 'INSERT INTO sessions 
                (lesson_id, coach_id, datetime, duration, location, price, requirements, max_spots, spots_available) 
                VALUES 
                (:lesson_id, :coach_id, :datetime, :duration, :location, :price, :requirements, :max_spots, :spots_available)';
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':lesson_id', $data['lesson_id']);
            $stmt->bindParam(':coach_id', $data['coach_id']);
            $stmt->bindParam(':datetime', $data['datetime']);
            $stmt->bindParam(':duration', $data['duration']);
            $stmt->bindParam(':location', $data['location']);
            $stmt->bindParam(':price', $data['price']);
            $stmt->bindParam(':requirements', $data['requirements']);
            $stmt->bindParam(':max_spots', $data['max_spots']);
            $stmt->bindParam(':spots_available', $data['spots_available']);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $this->error = true;
            error_log($e->getMessage());
            return false;
        }
    }

    public function updateSession(int $id, array $data) {
        try {
            $query = 'UPDATE sessions SET 
                lesson_id = :lesson_id, 
                coach_id = :coach_id, 
                datetime = :datetime, 
                duration = :duration, 
                location = :location, 
                price = :price, 
                requirements = :requirements,
                status = :status,
                max_spots = :max_spots, 
                spots_available = :spots_available
                WHERE id = :id';
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':lesson_id', $data['lesson_id']);
            $stmt->bindParam(':coach_id', $data['coach_id']);
            $stmt->bindParam(':datetime', $data['datetime']);
            $stmt->bindParam(':duration', $data['duration']);
            $stmt->bindParam(':location', $data['location']);
            $stmt->bindParam(':price', $data['price']);
            $stmt->bindParam(':requirements', $data['requirements']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':max_spots', $data['max_spots']);
            $stmt->bindParam(':spots_available', $data['spots_available']);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $this->error = true;
            return false;
        }
    }

    public function deleteSession(int $id) {
        try {
            $query = 'DELETE FROM sessions WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $this->error = true;
            return false;
        }
    }

    public function searchSessions(string $search = '', string $level = '', string $status = '', string $coachId = '') {
        try {
            $query = 'SELECT s.*, l.title as lesson_title, l.level as lesson_level,
                      c.name as coach_name, c.speciality as coach_speciality
                      FROM sessions s
                      LEFT JOIN lessons l ON s.lesson_id = l.id
                      LEFT JOIN coaches c ON s.coach_id = c.id
                      WHERE 1=1';
            
            $params = [];

            if (!empty($search)) {
                $query .= ' AND (l.title LIKE :search OR s.location LIKE :search OR c.name LIKE :search)';
                $params['search'] = "%$search%";
            }

            if (!empty($level)) {
                $query .= ' AND l.level = :level';
                $params['level'] = $level;
            }

            if (!empty($status)) {
                $query .= ' AND s.status = :status';
                $params['status'] = $status;
            }

            if (!empty($coachId)) {
                $query .= ' AND s.coach_id = :coach_id';
                $params['coach_id'] = (int)$coachId;
            }

            $query .= ' ORDER BY s.datetime DESC';

            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->error = true;
            return [];
        }
    }

    public function generateStatistics() {
        try {
            $query = 'SELECT status, COUNT(*) as count FROM sessions GROUP BY status';
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $this->totalSessions = 0;
            $this->availableSessions = 0;
            $this->completedSessions = 0;
            
            foreach ($results as $row) {
                $this->totalSessions += $row['count'];
                switch ($row['status']) {
                    case 'available':
                        $this->availableSessions = $row['count'];
                        break;
                    case 'completed':
                        $this->completedSessions = $row['count'];
                        break;
                }
            }
        } catch (Exception $e) {
            $this->error = true;
        }
    }
}
