<?php
namespace App\Controllers;

use App\Models\LessonModel;
use App\Models\SessionModel;
use App\Models\StudentModel;
use App\Models\AssignmentModel;

class HomeController {
    public $baseUrl;

    public function __construct() {
        $this->baseUrl = $this->getBaseUrl();
    }
    
    private function getBaseUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        return $protocol . $host . '/surfManager/';
    }

    public function show() {
        $baseUrl = $this->baseUrl;
        include '../app/Views/home.php';
        exit;
    }

    public function dashboard() {
        $lessonModel = new LessonModel();
        $sessionModel = new SessionModel();
        $studentModel = new StudentModel();
        $assignmentModel = new AssignmentModel();

        $totalLessons = count($lessonModel->getLessons());
        $totalSessions = count($sessionModel->getSessions());
        $totalStudents = 0;
        $studentModel->generateStatistics();
        $totalStudents = $studentModel->totalStudents;
        
        $assignments = $assignmentModel->getAllAssignments();
        $totalAssignments = count($assignments);

        $baseUrl = $this->baseUrl;
        include '../app/Views/admin/dashboard.php';
        exit;
    }
}