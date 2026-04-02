<?php
namespace Ilyas\SurfManager\Controllers;

use Ilyas\SurfManager\Models\LessonModel;
use Ilyas\SurfManager\Models\SessionModel;
use Ilyas\SurfManager\Models\StudentModel;
use Ilyas\SurfManager\Models\AssignmentModel;

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