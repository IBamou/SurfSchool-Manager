<?php
namespace Ilyas\SurfManager\Configs;

use PDO;
use PDOException;
class Model {
    protected $db;

    private $host = 'localhost';
    private $dbname = 'surfManager';
    private $dbuser = 'root';
    private $password = '';

    public function __construct() {
        try {
            $this->db = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->dbuser, $this->password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}
