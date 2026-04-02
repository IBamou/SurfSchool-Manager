<?php
namespace Ilyas\SurfManager\Controllers;

use Ilyas\SurfManager\Models\CoachModel;

class CoachController {
    public $baseUrl;

    public function __construct() {
        $this->baseUrl = 'http://localhost/surfManager/';
    }

    public function index() {
        $model = new CoachModel();
        $coaches = $model->getCoaches();
        $totalCoaches = count($coaches);

        $this->render_template('coaches', [
            'baseUrl' => $this->baseUrl,
            'coaches' => $coaches,
            'totalCoaches' => $totalCoaches
        ]);
    }

    public function add() {
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
                header("Location: " . $this->baseUrl . "coaches/add?error=Name, email and speciality are required");
                exit;
            }
            
            $model->addCoach($data);
            header("Location: " . $this->baseUrl . "coaches");
            exit;
        }

        $this->render_template('coachForm', [
            'baseUrl' => $this->baseUrl
        ]);
    }

    public function delete(int $id) {
        $model = new CoachModel();
        $model->deleteCoach($id);
        header("Location: " . $this->baseUrl . "coaches");
        exit;
    }

    public function edit(int $id) {
        $model = new CoachModel();
        $coach = $model->getCoach($id);

        if (!$coach) {
            header("Location: " . $this->baseUrl . "coaches");
            exit;
        }

        $this->render_template('coachForm', [
            'baseUrl' => $this->baseUrl,
            'coach' => $coach,
            'isEditing' => true
        ]);
    }

    public function update() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $model = new CoachModel();
            
            $id = (int)($_POST["id"] ?? 0);
            $data = [
                "name" => trim($_POST["name"] ?? ''),
                "email" => trim($_POST["email"] ?? ''),
                "phone" => trim($_POST["phone"] ?? ''),
                "speciality" => trim($_POST["speciality"] ?? ''),
                "experience" => (int)($_POST["experience"] ?? 0),
            ];
            
            if (empty($data["name"]) || empty($data["email"]) || empty($data["speciality"])) {
                header("Location: " . $this->baseUrl . "coaches/edit/" . $id . "?error=Name, email and speciality are required");
                exit;
            }
            
            $model->updateCoach($id, $data);
            header("Location: " . $this->baseUrl . "coaches");
            exit;
        }
    }

    private function render_template(string $template = '', array $data = []) {
        if ($template) {
            extract($data);
            include '../app/Views/admin/' . $template . '.php';
            exit;
        }
    }
}
