<?php
namespace App\Controllers;

use App\Models\LessonModel;
use App\Models\SessionModel;
use App\Models\StudentModel;
use App\Models\AssignmentModel;

class HomeController extends BaseController {

    public function show(): void {
        $this->render('home');
    }

    public function dashboard(): void {
        $lessonModel = new LessonModel();
        $sessionModel = new SessionModel();
        $studentModel = new StudentModel();
        $assignmentModel = new AssignmentModel();

        $totalLessons = count($lessonModel->getLessons());
        $totalSessions = count($sessionModel->getSessions());
        $studentModel->generateStatistics();

        $this->render('admin/dashboard', [
            'totalLessons' => $totalLessons,
            'totalSessions' => $totalSessions,
            'totalStudents' => $studentModel->totalStudents,
            'totalAssignments' => count($assignmentModel->getAllAssignments())
        ]);
    }
}
