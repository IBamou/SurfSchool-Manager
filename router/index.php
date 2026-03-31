<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';

use Ilyas\SurfManager\Controllers\HomeController;
use Ilyas\SurfManager\Controllers\LessonsController;
use Ilyas\SurfManager\Controllers\SessionsController;
use Ilyas\SurfManager\Controllers\StudentsController;
use Ilyas\SurfManager\Controllers\AuthController;

class Router {
    private $routes = [];

    public function get($pattern, $callback) {
        $this->routes[] = [
            'method' => 'GET',
            'pattern' => $pattern,
            'callback' => $callback
        ];
    }

    public function post($pattern, $callback) {
        $this->routes[] = [
            'method' => 'POST',
            'pattern' => $pattern,
            'callback' => $callback
        ];
    }

    public function run() {
        $basePath = '/surfManager';
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $currentUrl = trim(str_replace($basePath, '', $path), '/');
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) continue;

            $patternRegex = preg_replace_callback('#\{([\w]+)\}#', function($matches) {
                return '(?P<' . $matches[1] . '>\d+)';
            }, $route['pattern']);
            
            if (preg_match("#^$patternRegex$#", $currentUrl, $matches)) {
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key)) $params[$key] = (int)$value;
                }
                [$class, $method] = $route['callback'];
                $controller = new $class();
                call_user_func_array([$controller, $method], $params);
                return;
            }
        }

        http_response_code(404);
        echo "Page not found";
    }
}

$router = new Router();

// Home
$router->get('home', [HomeController::class, 'show']);

// Lessons
$router->get('lessons', [LessonsController::class, 'index']);
$router->get('lessons/{id}', [LessonsController::class, 'show']);
$router->get('lessons/add', [LessonsController::class, 'add']);
$router->post('lessons/add', [LessonsController::class, 'add']);
$router->get('lessons/edit/{id}', [LessonsController::class, 'edit']);
$router->post('lessons/edit/{id}', [LessonsController::class, 'edit']);
$router->post('lessons/delete/{id}', [LessonsController::class, 'delete']);

// Sessions
$router->get('sessions', [SessionsController::class, 'index']);
$router->get('sessions/{id}', [SessionsController::class, 'show']);
$router->get('sessions/add', [SessionsController::class, 'add']);
$router->post('sessions/add', [SessionsController::class, 'add']);
$router->get('sessions/edit/{id}', [SessionsController::class, 'edit']);
$router->post('sessions/edit/{id}', [SessionsController::class, 'edit']);
$router->post('sessions/delete/{id}', [SessionsController::class, 'delete']);
$router->get('sessions/book/{id}', [SessionsController::class, 'book']);
$router->post('sessions/book/{id}', [SessionsController::class, 'book']);
$router->post('sessions/cancel/{assignmentId}/{sessionId}', [SessionsController::class, 'cancelBooking']);

// Students
$router->get('students', [StudentsController::class, 'index']);
$router->get('students/{id}', [StudentsController::class, 'show']);
$router->get('students/edit/{id}', [StudentsController::class, 'edit']);
$router->post('students/edit/{id}', [StudentsController::class, 'edit']);
$router->post('students/level/{id}', [StudentsController::class, 'updateLevel']);
$router->post('students/payment/{assignmentId}/{studentId}', [StudentsController::class, 'updatePayment']);

// Auth
$router->get('login', [AuthController::class, 'login']);
$router->post('auth/login', [AuthController::class, 'login']);
$router->get('signup', [AuthController::class, 'signup']);
$router->post('auth/signup', [AuthController::class, 'signup']);
$router->get('auth/logout', [AuthController::class, 'logout']);

// Student Dashboard
$router->get('student/dashboard', [AuthController::class, 'dashboard']);
$router->get('student/lessons', [AuthController::class, 'lessons']);
$router->get('student/profile', [AuthController::class, 'profile']);
$router->get('student/edit-profile', [AuthController::class, 'editProfile']);
$router->get('student/change-password', [AuthController::class, 'changePassword']);

$router->run();
