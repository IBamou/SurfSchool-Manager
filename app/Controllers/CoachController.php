<?php
namespace App\Controllers;

use App\Models\CoachModel;
use App\Models\SessionModel;

class CoachController extends BaseController {

    public function index(): void {
        $model = new CoachModel();
        $coaches = $model->getCoaches();

        $this->renderAdmin('coaches', [
            'coaches' => $coaches,
            'totalCoaches' => count($coaches)
        ]);
    }

    public function add(): void {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $model = new CoachModel();
            
            $data = [
                "name" => trim($_POST["name"] ?? ''),
                "email" => trim($_POST["email"] ?? ''),
                "phone" => trim($_POST["phone"] ?? ''),
                "speciality" => trim($_POST["speciality"] ?? ''),
                "experience" => (int)($_POST["experience"] ?? 0),
            ];
            
            if (empty($data["name"]) || empty($data["email"]) || empty($data["speciality"])) {
                $this->redirectWithError('coaches/add', 'Name, email and speciality are required');
            }
            
            $model->addCoach($data);
            $this->redirect('coaches');
        }

        $this->renderAdmin('coachForm');
    }

    public function edit(int $id): void {
        $model = new CoachModel();
        $coach = $model->getCoach($id);

        if (!$coach) {
            $this->redirect('coaches');
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $data = [
                "name" => trim($_POST["name"] ?? ''),
                "email" => trim($_POST["email"] ?? ''),
                "phone" => trim($_POST["phone"] ?? ''),
                "speciality" => trim($_POST["speciality"] ?? ''),
                "experience" => (int)($_POST["experience"] ?? 0),
            ];
            
            if (empty($data["name"]) || empty($data["email"]) || empty($data["speciality"])) {
                $this->redirectWithError('coaches/edit/' . $id, 'Name, email and speciality are required');
            }
            
            $model->updateCoach($id, $data);
            $this->redirect('coaches');
        }

        $this->renderAdmin('coachForm', [
            'coach' => $coach,
            'isEditing' => true
        ]);
    }

    public function delete(int $id): void {
        $model = new CoachModel();
        $sessionModel = new SessionModel();
        
        $sessionModel->clearCoachFromSessions($id);
        $result = $model->deleteCoach($id);
        
        if ($result) {
            $this->redirectWithSuccess('coaches', 'Coach deleted successfully');
        } else {
            $this->redirectWithError('coaches', 'Failed to delete coach');
        }
    }
}
