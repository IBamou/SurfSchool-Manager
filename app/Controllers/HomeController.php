<?php
namespace Ilyas\SurfManager\Controllers;

class HomeController {
    public $baseUrl;

    public function __construct() {
        $this->baseUrl = 'http://localhost/surfManager/';
    }

    public function show() {
        $baseUrl = $this->baseUrl;
        include '../app/Views/home.php';
        exit;
    }
}
