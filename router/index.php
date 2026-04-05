<?php
/**
 * Router Index
 * Main entry point that routes all HTTP requests to appropriate controllers
 */

// Start session FIRST before anything else
session_start();

// Register error handler for catching all errors
require __DIR__ . '/../vendor/autoload.php';
use App\Helpers\ErrorHandler;
ErrorHandler::register();

// Import controllers
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

/**
 * Router Class
 * Handles URL pattern matching and dispatches to controllers
 * 
 * Features:
 * - GET/POST method separation
 * - Parameter extraction from URLs (e.g., /users/{id})
 * - Middleware support for authentication/authorization
 */
class Router {
    static private array $routes = [];
    static private int $currentRouteIndex;

    /**
     * Register a GET route
     */
    static public function get(string $pattern, array $callback): self {
        $route = [
            'method' => 'GET',
            'pattern' => $pattern,
            'callback' => $callback,
            'middleware' => []
        ];
        self::$routes[] = $route;
        self::$currentRouteIndex = count(self::$routes) - 1;
        return new self;
    }

    /**
     * Register a POST route
     */
    static public function post(string $pattern, array $callback): self {
        $route = [
            'method' => 'POST',
            'pattern' => $pattern,
            'callback' => $callback,
            'middleware' => []
        ];
        self::$routes[] = $route;
        self::$currentRouteIndex = count(self::$routes) - 1;
        return new self;
    }

    /**
     * Add middleware to the current route
     */
    static public function middleware(string $method, array $args = []): self {
        if (isset(self::$currentRouteIndex)) {
            self::$routes[self::$currentRouteIndex]['middleware'][] = [$method, $args];
        }
        return new self;
    }

    /**
     * Parse the current request
     * Returns path and HTTP method
     */
    static public function request(): array {
        $basePath = '/surfManager';
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $request = trim(str_replace($basePath, '', $path), '/');
        if ($request === '') $request = '/';
        return [
            'PATH' => $request, 
            'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD']
        ];
    }

    /**
     * Match current request to a route and execute
     */
    static public function match(): void {
        $request = self::request();

        // Iterate through all registered routes
        foreach (self::$routes as $route) {
            // Skip if HTTP method doesn't match
            if ($route['method'] !== $request['REQUEST_METHOD']) continue;

            // Convert URL pattern to regex
            // Example: 'users/{id}' becomes 'users/(?P<id>\d+)'
            $patternRegex = preg_replace_callback(
                '#\{([\w]+)\}#', 
                fn($matches) => '(?P<' . $matches[1] . '>\d+)',
                $route['pattern']
            );
            
            // Try to match the request path against the pattern
            if (preg_match("#^$patternRegex$#", $request['PATH'], $matches)) {
                // Extract named parameters from URL
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key)) $params[$key] = (int)$value;
                }
                
                // Run middleware (if any)
                if (!empty($route['middleware'])) {
                    foreach ($route['middleware'] as $middleware) {
                        [$middlewareMethod, $args] = $middleware;
                        call_user_func_array([new Middleware(), $middlewareMethod], $args);
                    }
                }
                
                // Instantiate controller and call method
                [$class, $method] = $route['callback'];
                $controller = new $class();
                call_user_func_array([$controller, $method], $params);
                return;
            }
        }

        // No route matched - show 404
        ErrorHandler::render404('Page not found');
    }
}

// =============================================================================
// ROUTES DEFINITION
// =============================================================================

// Landing Page (Public)
Router::get('/', [HomeController::class, 'show']);
Router::get('home', [HomeController::class, 'show']);

// Auth Routes (Guest only - redirect if logged in)
Router::get('login', [AuthController::class, 'login'])
    ->middleware('isGuest');

Router::post('auth/login', [AuthController::class, 'login'])
    ->middleware('isGuest');

Router::get('signup', [AuthController::class, 'signup'])
    ->middleware('isGuest');

Router::post('auth/signup', [AuthController::class, 'signup'])
    ->middleware('isGuest');

// Email verification (Public - needs token)
Router::get('auth/verify', [AuthController::class, 'verify']);

Router::post('auth/logout', [AuthController::class, 'logout']);

// Dashboard (Logged in users)
Router::get('dashboard', [DashboardController::class, 'dashboard'])
    ->middleware('isLoggedIn');

// Sessions (Logged in users)
Router::get('sessions', [SessionsController::class, 'sessions'])
    ->middleware('isLoggedIn');

// Profile (Logged in users)
Router::get('profile', [ProfileController::class, 'profile'])
    ->middleware('isLoggedIn');

Router::post('profile', [ProfileController::class, 'profile'])
    ->middleware('isLoggedIn');

// ---------------------------------------------Admin Only---------------------------------------------

// Lessons Management
Router::get('lessons', [LessonsController::class, 'index'])->middleware('isAdmin');
Router::get('lessons/{id}', [LessonsController::class, 'show'])->middleware('isAdmin');
Router::get('lessons/add', [LessonsController::class, 'add'])->middleware('isAdmin');
Router::post('lessons/add', [LessonsController::class, 'add'])->middleware('isAdmin');
Router::get('lessons/edit/{id}', [LessonsController::class, 'edit'])->middleware('isAdmin');
Router::post('lessons/edit/{id}', [LessonsController::class, 'edit'])->middleware('isAdmin');
Router::post('lessons/delete/{id}', [LessonsController::class, 'delete'])->middleware('isAdmin');

// Sessions Management
Router::get('sessions/{id}', [SessionsController::class, 'show'])->middleware('isAdmin');
Router::get('sessions/add', [SessionsController::class, 'add'])->middleware('isAdmin');
Router::post('sessions/add', [SessionsController::class, 'add'])->middleware('isAdmin');
Router::get('sessions/edit/{id}', [SessionsController::class, 'edit'])->middleware('isAdmin');
Router::post('sessions/edit/{id}', [SessionsController::class, 'edit'])->middleware('isAdmin');
Router::post('sessions/delete/{id}', [SessionsController::class, 'delete'])->middleware('isAdmin');
Router::get('sessions/book/{id}', [BookingController::class, 'book'])->middleware('isAdmin');
Router::post('sessions/book/{id}', [BookingController::class, 'book'])->middleware('isAdmin');
Router::post('sessions/cancel/{assignmentId}/{sessionId}', [BookingController::class, 'cancelBooking'])->middleware('isAdmin');

// Students Management
Router::get('students', [StudentsController::class, 'index'])->middleware('isAdmin');
Router::get('students/{id}', [StudentsController::class, 'show'])->middleware('isAdmin');
Router::get('students/edit/{id}', [StudentsController::class, 'edit'])->middleware('isAdmin');
Router::post('students/edit/{id}', [StudentsController::class, 'edit'])->middleware('isAdmin');
Router::post('students/level/{id}', [StudentsController::class, 'updateLevel'])->middleware('isAdmin');
Router::post('students/payment/{assignmentId}/{studentId}', [StudentsController::class, 'updatePayment'])->middleware('isAdmin');

// Coaches Management
Router::get('coaches', [CoachController::class, 'index'])->middleware('isAdmin');
Router::get('coaches/add', [CoachController::class, 'add'])->middleware('isAdmin');
Router::post('coaches/add', [CoachController::class, 'add'])->middleware('isAdmin');
Router::get('coaches/edit/{id}', [CoachController::class, 'edit'])->middleware('isAdmin');
Router::post('coaches/edit/{id}', [CoachController::class, 'edit'])->middleware('isAdmin');
Router::post('coaches/delete/{id}', [CoachController::class, 'delete'])->middleware('isAdmin');

// Start the router
Router::match();
