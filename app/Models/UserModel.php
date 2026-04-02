<?php
namespace Ilyas\SurfManager\Models;

use Ilyas\SurfManager\Configs\Model;
use PDO;
use Exception;

class UserModel extends Model {

    public $last_added_user_id;
    public $error = false;

    public function __construct() {
        parent::__construct();   
        $this->last_added_user_id = $this->db->lastInsertId();
    }

    public function getUsers() {
        try {
            $query = 'SELECT * FROM users';
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->error = true;
        }
    }

    public function addUser($name, $email, $password) {
        try {
            $query = 'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $this->error = true;
        }
    }
    public function getUser(int $id = 0, string $email = '') {
        try {
            if ($id > 0) {
                $query = 'SELECT id, name, email, role FROM users WHERE id = :id';
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
            } else if ($email) {
                $query = 'SELECT id, name, email, role FROM users WHERE email = :email';
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
            }
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->error = true;
        }
    }

    public function findById(int $id) {
        try {
            $query = 'SELECT * FROM users WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->error = true;
            return null;
        }
    }


    public function updateUserLevel(int $id, string $level) {
        try {
            $query = 'UPDATE users SET level = :level WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':level', $level);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $this->error = true;
        }
    }

}