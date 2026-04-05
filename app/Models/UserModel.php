<?php
namespace App\Models;

use App\Configs\Model;
use PDO;
use Exception;

/**
 * UserModel - Handles all user-related database operations
 * 
 * IMPORTANT: This model only handles data access (CRUD operations).
 * Business logic should be in Services layer.
 */
class UserModel extends Model {

    public ?int $last_added_user_id = null;
    public bool $error = false;

    public function __construct() {
        parent::__construct();   
    }

    /**
     * Get all users
     */
    public function getUsers(): array {
        try {
            $query = 'SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC';
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->error = true;
            return [];
        }
    }

    /**
     * Get user by ID or email
     */
    public function getUser(int $id = 0, string $email = ''): ?array {
        try {
            if ($id > 0) {
                $query = 'SELECT id, name, email, role, email_verified, created_at FROM users WHERE id = :id';
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            } else if ($email) {
                $query = 'SELECT id, name, email, role, email_verified, created_at FROM users WHERE email = :email';
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
            } else {
                return null;
            }
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Exception $e) {
            $this->error = true;
            return null;
        }
    }

    /**
     * Find user by ID only
     */
    public function findById(int $id): ?array {
        try {
            $query = 'SELECT id, name, email, role, email_verified, created_at FROM users WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Exception $e) {
            $this->error = true;
            return null;
        }
    }

    /**
     * Check if email exists
     */
    public function emailExists(string $email): bool {
        try {
            $query = 'SELECT COUNT(*) FROM users WHERE email = :email';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            $this->error = true;
            return false;
        }
    }

    /**
     * Create new user
     */
    public function addUser(string $name, string $email, string $password): bool {
        try {
            // Check if email already exists
            if ($this->emailExists($email)) {
                return false;
            }
            
            $query = 'INSERT INTO users (name, email, password, email_verified, created_at) 
                       VALUES (:name, :email, :password, 0, NOW())';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            
            $this->last_added_user_id = (int) $this->db->lastInsertId();
            return true;
        } catch (Exception $e) {
            $this->error = true;
            return false;
        }
    }

    /**
     * Mark user email as verified
     */
    public function markEmailVerified(int $userId): bool {
        try {
            $query = 'UPDATE users SET email_verified = 1, verified_at = NOW() WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $this->error = true;
            return false;
        }
    }

    /**
     * Update user profile
     */
    public function updateProfile(int $id, string $name, string $email): bool {
        try {
            $query = 'UPDATE users SET name = :name, email = :email WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $this->error = true;
            return false;
        }
    }

    /**
     * Update user level
     */
    public function updateUserLevel(int $id, string $level): bool {
        try {
            $query = 'UPDATE users SET level = :level WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':level', $level);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            $this->error = true;
            return false;
        }
    }

    /**
     * Change user password (verifies current password first)
     */
    public function changePassword(int $id, string $currentPassword, string $newPassword): bool {
        try {
            // First verify current password
            $query = 'SELECT password FROM users WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user || !password_verify($currentPassword, $user['password'])) {
                return false;
            }
            
            // Update to new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $query = 'UPDATE users SET password = :password, updated_at = NOW() WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return true;
        } catch (Exception $e) {
            $this->error = true;
            return false;
        }
    }
}
