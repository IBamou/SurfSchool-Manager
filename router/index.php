<?php
session_start();    
require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\HomeController;
use App\Controllers\DashboardController;
use App\Controllers\LessonsController;
use App\Controllers\SessionsController;
use App\Controllers\StudentsController;
use App\Controllers\CoachController;
use App\Controllers\AuthController;
use App\Controllers\ProfileController;
use App\Controllers\BookingController;

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

    public function route() {
        $basePath = '/surfManager';
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $currentUrl = trim(str_replace($basePath, '', $path), '/');
        if ($currentUrl === '') $currentUrl = '/';
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
        include '../app/Views/404.php';
    }
}

$router = new Router();

// Landing Page
$router->get('home', [HomeController::class, 'show']);
$router->get('/', [HomeController::class, 'show']);

// Auth
$router->get('login', [AuthController::class, 'login']);
$router->post('auth/login', [AuthController::class, 'login']);
$router->get('signup', [AuthController::class, 'signup']);
$router->post('auth/signup', [AuthController::class, 'signup']);
$router->get('auth/logout', [AuthController::class, 'logout']);

if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] === 'admin') {
        // Dashboard
        $router->get('dashboard', [DashboardController::class, 'index']);

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
        $router->get('sessions/book/{id}', [BookingController::class, 'book']);
        $router->post('sessions/book/{id}', [BookingController::class, 'book']);
        $router->post('sessions/cancel/{assignmentId}/{sessionId}', [BookingController::class, 'cancelBooking']);

        // Students
        $router->get('students', [StudentsController::class, 'index']);
        $router->get('students/{id}', [StudentsController::class, 'show']);
        $router->get('students/edit/{id}', [StudentsController::class, 'edit']);
        $router->post('students/edit/{id}', [StudentsController::class, 'edit']);
        $router->post('students/level/{id}', [StudentsController::class, 'updateLevel']);
        $router->post('students/payment/{assignmentId}/{studentId}', [StudentsController::class, 'updatePayment']);

        // Coaches
        $router->get('coaches', [CoachController::class, 'index']);
        $router->get('coaches/add', [CoachController::class, 'add']);
        $router->post('coaches/add', [CoachController::class, 'add']);
        $router->get('coaches/edit/{id}', [CoachController::class, 'edit']);
        $router->post('coaches/edit/{id}', [CoachController::class, 'edit']);
        $router->post('coaches/delete/{id}', [CoachController::class, 'delete']);

        // profile
        $router->get('profile', [ProfileController::class, 'profile']);
        $router->post('profile', [ProfileController::class, 'profile']);

    } elseif ($_SESSION['user']['role'] === 'user') {
        // Student Dashboard
        $router->get('dashboard', [DashboardController::class, 'dashboard']);
        $router->get('sessions', [SessionsController::class, 'sessions']);
        $router->get('profile', [ProfileController::class, 'profile']);
        $router->post('profile', [ProfileController::class, 'profile']);
        $router->get('edit-profile', [ProfileController::class, 'editProfile']);
        $router->get('change-password', [ProfileController::class, 'changePassword']);
    }
}

$router->route();
