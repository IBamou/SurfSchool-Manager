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
use App\Middleware\Middleware;

class Router {
    static private $routes = [];
    static private $currentRouteIndex;

    static public function get($pattern, $callback) {
        $route = [
            'method' => 'GET',
            'pattern' => $pattern,
            'callback' => $callback,
            'middleware' => []
        ];

        self::$routes[] = $route;
        self::$currentRouteIndex = count(self::$routes) - 1;
        return new static;

    }

    static public function post($pattern, $callback) {
        $route = [
            'method' => 'POST',
            'pattern' => $pattern,
            'callback' => $callback,
            'middleware' => []
        ];

        self::$routes[] = $route;
        self::$currentRouteIndex = count(self::$routes) - 1;
        return new static;
    }

    static public function middleware(string $method, $args=[]) {
        if (isset(self::$currentRouteIndex)) {
            self::$routes[self::$currentRouteIndex]['middleware'][] = [$method, $args];
        }
        return new static;
    }

    static public function request() {
        $basePath = '/surfManager';
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $request = trim(str_replace($basePath, '', $path), '/');
        if ($request === '') $request = '/';
        return ['PATH' => $request, 'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD']];
    }

    static public function match() {

        $request = self::request();

        foreach (self::$routes as $route) {
            if ($route['method'] !== $request['REQUEST_METHOD']) continue;

            $patternRegex = preg_replace_callback('#\{([\w]+)\}#', function($matches) {
                return '(?P<' . $matches[1] . '>\d+)';
            }, $route['pattern']);
            
            if (preg_match("#^$patternRegex$#", $request['PATH'], $matches)) {
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key)) $params[$key] = (int)$value;
                }
                if (!empty($route['middleware'])) {
                    foreach($route['middleware'] as $middleware) {
                        [$middlewareMethod, $args] = $middleware;
                        call_user_func_array([new Middleware(), $middlewareMethod], $args);
                    }
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



// Landing Page
Router::get('home', [HomeController::class, 'show']);

Router::get('/', [HomeController::class, 'show']);

// Auth
Router::get('login', [AuthController::class, 'login'])
        ->middleware('gest');

Router::post('auth/login', [AuthController::class, 'login'])
        ->middleware('gest');
        
Router::get('signup', [AuthController::class, 'signup'])
        ->middleware('gest');

Router::post('auth/signup', [AuthController::class, 'signup'])
        ->middleware('gest');

Router::post('auth/logout', [AuthController::class, 'logout'])
        ->middleware('gest');


// Dashboard
Router::get('dashboard', [DashboardController::class, 'dashboard'])
        ->middleware('isLoggedIn');

// Session
Router::get('sessions', [SessionsController::class, 'sessions'])
        ->middleware('isLoggedIn');

// profile
Router::get('profile', [ProfileController::class, 'profile'])
        ->middleware('isLoggedIn');

Router::post('profile', [ProfileController::class, 'profile'])
        ->middleware('isLoggedIn');

// ---------------------------------------------Admin----------------------------------------------------
// Lessons
Router::get('lessons', [LessonsController::class, 'index'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::get('lessons/{id}', [LessonsController::class, 'show'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);
        
Router::get('lessons/add', [LessonsController::class, 'add'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::post('lessons/add', [LessonsController::class, 'add'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::get('lessons/edit/{id}', [LessonsController::class, 'edit'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::post('lessons/edit/{id}', [LessonsController::class, 'edit'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::post('lessons/delete/{id}', [LessonsController::class, 'delete'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);


// Sessions
Router::get('sessions/{id}', [SessionsController::class, 'show'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::get('sessions/add', [SessionsController::class, 'add'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::post('sessions/add', [SessionsController::class, 'add'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::get('sessions/edit/{id}', [SessionsController::class, 'edit'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::post('sessions/edit/{id}', [SessionsController::class, 'edit'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::post('sessions/delete/{id}', [SessionsController::class, 'delete'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::get('sessions/book/{id}', [BookingController::class, 'book'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::post('sessions/book/{id}', [BookingController::class, 'book'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::post('sessions/cancel/{assignmentId}/{sessionId}', [BookingController::class, 'cancelBooking'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);


// Students
Router::get('students', [StudentsController::class, 'index'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::get('students/{id}', [StudentsController::class, 'show'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::get('students/edit/{id}', [StudentsController::class, 'edit'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::post('students/edit/{id}', [StudentsController::class, 'edit'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::post('students/level/{id}', [StudentsController::class, 'updateLevel'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::post('students/payment/{assignmentId}/{studentId}', [StudentsController::class, 'updatePayment'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

// Coaches
Router::get('coaches', [CoachController::class, 'index'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::get('coaches/add', [CoachController::class, 'add'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::post('coaches/add', [CoachController::class, 'add'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::get('coaches/edit/{id}', [CoachController::class, 'edit'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::post('coaches/edit/{id}', [CoachController::class, 'edit'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);

Router::post('coaches/delete/{id}', [CoachController::class, 'delete'])
        ->middleware('isLoggedIn')
        ->middleware('auth', ['role' => 'admin']);


Router::match();
