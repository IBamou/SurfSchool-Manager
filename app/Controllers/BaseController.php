<?php
namespace App\Controllers;

use App\Services\Logger;
use App\Helpers\ErrorHandler;

/**
 * BaseController
 * Base class for all controllers providing common functionality
 * 
 * Features:
 * - Automatic base URL generation
 * - Standardized rendering methods
 * - Redirect helpers with flash messages
 * - Error handling integration
 * - Logging integration
 */
abstract class BaseController {
    protected string $baseUrl;
    protected Logger $logger;

    public function __construct() {
        $this->baseUrl = $this->getBaseUrl();
        $this->logger = Logger::getInstance();
    }

    /**
     * Generate base URL for the application
     * Detects HTTPS and constructs proper URL
     */
    protected function getBaseUrl(): string {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        return $protocol . $_SERVER['HTTP_HOST'] . '/surfManager/';
    }

    /**
     * Render any view template
     * 
     * @param string $template Path to template (e.g., 'home' or 'admin/dashboard')
     * @param array $data Data to pass to the view
     */
    protected function render(string $template, array $data = []): void {
        // Always include baseUrl for views
        $data['baseUrl'] = $this->baseUrl;
        extract($data);
        include '../app/Views/' . $template . '.php';
        exit;
    }

    /**
     * Render authentication views
     */
    protected function renderAuth(string $template, array $data = []): void {
        $data['baseUrl'] = $this->baseUrl;
        extract($data);
        include '../app/Views/auth/' . $template . '.php';
        exit;
    }

    /**
     * Render admin views
     */
    protected function renderAdmin(string $template, array $data = []): void {
        $data['baseUrl'] = $this->baseUrl;
        extract($data);
        include '../app/Views/admin/' . $template . '.php';
        exit;
    }

    /**
     * Redirect to URL (relative to base or absolute)
     */
    protected function redirect(string $url): void {
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            header('Location: ' . $url);
        } else {
            header('Location: ' . $this->baseUrl . $url);
        }
        exit;
    }

    /**
     * Redirect with success flash message
     */
    protected function redirectWithSuccess(string $url, string $message): void {
        $_SESSION['success'] = $message;
        $this->logger->info("Success redirect: $message", ['url' => $url]);
        header('Location: ' . $this->baseUrl . $url);
        exit;
    }

    /**
     * Redirect with error flash message
     */
    protected function redirectWithError(string $url, string $message): void {
        $_SESSION['error'] = $message;
        $this->logger->warning("Error redirect: $message", ['url' => $url]);
        header('Location: ' . $this->baseUrl . $url);
        exit;
    }

    /**
     * Go back to previous page with error message
     */
    protected function backWithError(string $message): void {
        $_SESSION['error'] = $message;
        $this->logger->warning("Back with error: $message");
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    /**
     * Render 404 error page
     */
    protected function render404(string $message = 'Page not found'): void {
        ErrorHandler::render404($message);
    }

    /**
     * Render 403 error page
     */
    protected function render403(string $message = 'Access denied'): void {
        ErrorHandler::render403($message);
    }

    /**
     * Check if current request is AJAX
     */
    protected function isAjax(): bool {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
    }

    /**
     * Return JSON response (for AJAX requests)
     */
    protected function json(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Log info message
     */
    protected function logInfo(string $message, array $context = []): void {
        $this->logger->info($message, $context);
    }

    /**
     * Log error message
     */
    protected function logError(string $message, array $context = []): void {
        $this->logger->error($message, $context);
    }

    /**
     * Log warning message
     */
    protected function logWarning(string $message, array $context = []): void {
        $this->logger->warning($message, $context);
    }
}
