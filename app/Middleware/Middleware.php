<?php
namespace App\Middleware;

class Middleware {

    public $baseUrl;

    public function __construct() {
        $this->baseUrl = $this->getBaseUrl();
    }

    private function getBaseUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        return $protocol . $host . '/surfManager/';
    }

    public function auth($role) {
        if ($_SESSION['user']['role'] != $role) {
            include '../app/Views/404.php';
            exit;
        }
    }

    public function isLoggedIn() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl .'login');
            exit;
        }
    }


}