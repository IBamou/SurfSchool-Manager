<?php
namespace Ilyas\SurfManager\Controllers;
use Ilyas\SurfManager\Models\UserModel;
class UsersController {
    public function __construct() {}

    public function show(int $id = 0) {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $model = new UserModel();
            if (!empty($id) && is_numeric($id) && $id > 0) {
                    $user = $model->getUser($id);
                    $this->render_template('user', ['user', $user]);
            } else {
                $users = $model->getUsers();
                $this->render_template('users', ['users', $users]);
            }
        }
    }

    public function update(int $id) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $model = new UserModel();
            $level = $_POST['level'];
            $model->updateUserLevel($id, $level); 
        }
    }

    public function render_template(string $template = '', ...$args) {
        if ($template) {
            foreach ($args as $pair) {
                [$key , $value] = $pair;
                $$key = $value;
            }
            include '../app/Views/admin/' . $template . '.php';
            exit;
        }
    }



}
