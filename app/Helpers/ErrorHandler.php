<?php
namespace App\Helpers;

use App\Services\Logger;
use Throwable;

/**
 * ErrorHandler
 * Centralized error and exception handling for the application
 */
class ErrorHandler {

    /**
     * Register the error handler (call this early in index.php)
     */
    public static function register(): void {
        // Set proper error reporting based on environment
        if (self::isDebugMode()) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        } else {
            error_reporting(0);
            ini_set('display_errors', '0');
        }
        
        // Set exception handler
        set_exception_handler([self::class, 'handleException']);
        
        // Set shutdown handler for fatal errors
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    /**
     * Handle PHP errors (only for development - converts to exception)
     * Note: In production, this is disabled and PHP handles errors normally
     */
    public static function handleError(int $level, string $message, string $file = '', int $line = 0): bool {
        // Only handle in debug mode
        if (!self::isDebugMode()) {
            return false; // Let PHP handle it normally
        }
        
        // Skip if error reporting is disabled or if error is suppressed with @
        if (!(error_reporting() & $level)) {
            return false;
        }

        $exception = new \ErrorException($message, 0, $level, $file, $line);
        self::handleException($exception);
        
        return true;
    }

    /**
     * Handle uncaught exceptions
     */
    public static function handleException(Throwable $e): void {
        $logger = Logger::getInstance();
        
        // Log the exception
        $logger->error($e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        // Determine if we should show detailed errors
        if (self::isDebugMode()) {
            self::renderDetailedError($e);
        } else {
            self::renderGenericError($e);
        }
    }

    /**
     * Handle fatal errors (called on shutdown)
     */
    public static function handleShutdown(): void {
        $error = error_get_last();
        
        // Only handle fatal errors
        $fatalErrors = [
            E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR
        ];
        
        if ($error !== null && in_array($error['type'], $fatalErrors)) {
            $logger = Logger::getInstance();
            $logger->critical("Fatal Error: " . $error['message'], [
                'file' => $error['file'],
                'line' => $error['line']
            ]);
            
            if (!self::isDebugMode()) {
                self::renderGenericError(null, "A fatal error occurred. Please try again later.");
            }
        }
    }

    /**
     * Check if debug mode is enabled
     */
    private static function isDebugMode(): bool {
        // Enable debug mode if:
        // 1. DEBUG env is explicitly set to true
        // 2. Running on localhost (127.0.0.1 or localhost)
        if (isset($_ENV['DEBUG'])) {
            return (bool) $_ENV['DEBUG'];
        }
        
        // Auto-detect localhost
        $localhosts = ['127.0.0.1', 'localhost', '::1'];
        return in_array($_SERVER['REMOTE_ADDR'] ?? '', $localhosts);
    }

    /**
     * Render detailed error page (for development)
     */
    private static function renderDetailedError(Throwable $e): void {
        $message = $e->getMessage();
        $file = $e->getFile();
        $line = $e->getLine();
        $trace = $e->getTraceAsString();
        
        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <title>Error - SurfManager</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
                .error-container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                h1 { color: #dc2626; }
                .error-info { background: #fee2e2; padding: 15px; border-radius: 8px; margin: 15px 0; }
                .trace { background: #f3f4f6; padding: 15px; border-radius: 8px; overflow-x: auto; font-family: monospace; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='error-container'>
                <h1>⚠️ Error Occurred</h1>
                <div class='error-info'>
                    <strong>Message:</strong> " . htmlspecialchars($message) . "<br>
                    <strong>File:</strong> " . htmlspecialchars($file) . "<br>
                    <strong>Line:</strong> " . $line . "
                </div>
                <h3>Stack Trace:</h3>
                <pre class='trace'>" . htmlspecialchars($trace) . "</pre>
                <a href='javascript:history.back()'>← Go Back</a>
            </div>
        </body>
        </html>";
        exit;
    }

    /**
     * Render generic error page (for production)
     */
    private static function renderGenericError(?Throwable $e, string $customMessage = ''): void {
        $message = $customMessage ?: "Something went wrong. Please try again later.";
        
        // Check if it's an AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => $message
            ]);
            exit;
        }

        http_response_code(500);
        include dirname(__DIR__, 2) . '/app/Views/errors/500.php';
        exit;
    }

    /**
     * Render 404 page
     */
    public static function render404(string $message = 'Page not found'): void {
        http_response_code(404);
        
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => $message
            ]);
            exit;
        }

        include dirname(__DIR__, 2) . '/app/Views/404.php';
        exit;
    }

    /**
     * Render 403 forbidden page
     */
    public static function render403(string $message = 'Access denied'): void {
        http_response_code(403);
        
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => $message
            ]);
            exit;
        }

        include dirname(__DIR__, 2) . '/app/Views/errors/403.php';
        exit;
    }
}
