<?php
namespace Ilyas\SurfManager\Models;

use Ilyas\SurfManager\Configs\Model;
use PDO;
use Exception;

class CoachModel extends Model {

    public $error = false;

    public function __construct() {
        parent::__construct();   
    }

    public function getCoaches() {
        try {
            $query = 'SELECT * FROM coaches';
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->error = true;
            return [];
        }
    }

    public function getCoach(int $id) {
        try {
            $query = 'SELECT c.*, u.name, u.email, u.avatar
                      FROM coaches c 
                      LEFT JOIN users u ON c.user_id = u.id
                      WHERE c.id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->error = true;
            return null;
        }
    }

    public function getCoachByUserId(int $userId) {
        try {
            $query = 'SELECT c.*, u.name, u.email
                      FROM coaches c 
                      LEFT JOIN users u ON c.user_id = u.id
                      WHERE c.user_id = :user_id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->error = true;
            return null;
        }
    }

    public function addCoach(array $data) {
        try {
            $query = 'INSERT INTO coaches (user_id, bio, specialty, years_experience, certifications, rating) 
                      VALUES (:user_id, :bio, :specialty, :years_experience, :certifications, :rating)';
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $data['user_id']);
            $stmt->bindParam(':bio', $data['bio']);
            $stmt->bindParam(':specialty', $data['specialty']);
            $stmt->bindParam(':years_experience', $data['years_experience']);
            $stmt->bindParam(':certifications', $data['certifications']);
            $stmt->bindParam(':rating', $data['rating']);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $this->error = true;
            return false;
        }
    }

    public function updateCoach(int $id, array $data) {
        try {
            $query = 'UPDATE coaches SET 
                bio = :bio, 
                specialty = :specialty, 
                years_experience = :years_experience, 
                certifications = :certifications,
                is_active = :is_active
                WHERE id = :id';
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':bio', $data['bio']);
            $stmt->bindParam(':specialty', $data['specialty']);
            $stmt->bindParam(':years_experience', $data['years_experience']);
            $stmt->bindParam(':certifications', $data['certifications']);
            $stmt->bindParam(':is_active', $data['is_active']);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $this->error = true;
            return false;
        }
    }

    public function updateRating(int $id, float $rating, int $totalReviews) {
        try {
            $query = 'UPDATE coaches SET rating = :rating, total_reviews = :total_reviews WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':total_reviews', $totalReviews);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $this->error = true;
            return false;
        }
    }

    public function getCoachLessons(int $coachId) {
        try {
            $query = 'SELECT * FROM lessons WHERE coach_id = :coach_id ORDER BY datetime DESC';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':coach_id', $coachId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->error = true;
            return [];
        }
    }
}
