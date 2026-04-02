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
            $query = 'SELECT * FROM coaches ORDER BY name ASC';
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
            $query = 'SELECT * FROM coaches WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->error = true;
            return null;
        }
    }

    public function addCoach(array $data) {
        try {
            $query = 'INSERT INTO coaches (name, email, phone, speciality, experience) 
                      VALUES (:name, :email, :phone, :speciality, :experience)';
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':speciality', $data['speciality']);
            $stmt->bindParam(':experience', $data['experience']);
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
                name = :name, 
                email = :email, 
                phone = :phone,
                speciality = :speciality, 
                experience = :experience
                WHERE id = :id';
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':speciality', $data['speciality']);
            $stmt->bindParam(':experience', $data['experience']);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $this->error = true;
            return false;
        }
    }

    public function deleteCoach(int $id) {
        try {
            $query = 'DELETE FROM coaches WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $this->error = true;
            return false;
        }
    }
}
