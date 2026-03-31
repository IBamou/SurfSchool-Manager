<?php
namespace Ilyas\SurfManager\Models;

use Ilyas\SurfManager\Configs\Model;
use PDO;
use Exception;

class UserModel extends Model {

    public $error = false;
    public function __construct() {
        parent::__construct();   
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

    public function getUser(int $id) {
        try {
            $query = 'SELECT * FROM users WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->error = true;
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