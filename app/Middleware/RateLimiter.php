<?php
namespace App\Middleware;

use App\Services\Logger;

/**
 * RateLimiter Middleware
 * Prevents abuse by limiting request frequency per IP/user
 */
class RateLimiter {
    
    // Default limits (requests per window)
    private const DEFAULT_LIMIT = 60;      // 60 requests
    private const DEFAULT_WINDOW = 60;     // per 60 seconds
    
    // Stricter limits for sensitive endpoints
    private const LOGIN_LIMIT = 5;         // 5 attempts
    private const LOGIN_WINDOW = 60;       // per minute
    private const SIGNUP_LIMIT = 3;        // 3 attempts
    private const SIGNUP_WINDOW = 300;    // per 5 minutes

    private string $storagePath;
    private Logger $logger;

    public function __construct() {
        $this->storagePath = dirname(__DIR__, 2) . '/storage/rate_limits';
        $this->logger = Logger::getInstance();
        
        // Create storage directory if not exists
        if (!is_dir($this->storagePath)) {
            mkdir($this->storagePath, 0755, true);
        }
    }

    /**
     * Check rate limit for general requests
     */
    public function limit(int $limit = self::DEFAULT_LIMIT, int $window = self::DEFAULT_WINDOW): bool {
        return $this->check($this->getIdentifier(), $limit, $window, 'general');
    }

    /**
     * Check rate limit for login attempts
     */
    public function limitLogin(): bool {
        return $this->check($this->getIdentifier(), self::LOGIN_LIMIT, self::LOGIN_WINDOW, 'login');
    }

    /**
     * Check rate limit for signup attempts
     */
    public function limitSignup(): bool {
        return $this->check($this->getIdentifier(), self::SIGNUP_LIMIT, self::SIGNUP_WINDOW, 'signup');
    }

    /**
     * Check rate limit for API requests
     */
    public function limitApi(): bool {
        return $this->check($this->getIdentifier(), 100, 60, 'api');
    }

    /**
     * Core rate limiting logic
     */
    private function check(string $identifier, int $limit, int $window, string $type): bool {
        $file = $this->getStorageFile($identifier, $type);
        $now = time();
        
        // Load existing data
        $data = $this->loadData($file);
        
        // Remove old entries (outside the window)
        $data = array_filter($data, fn($timestamp) => ($now - $timestamp) < $window);
        
        // Count requests in current window
        $requestCount = count($data);
        
        if ($requestCount >= $limit) {
            // Rate limit exceeded
            $this->logger->warning("Rate limit exceeded", [
                'type' => $type,
                'ip' => $this->getClientIp(),
                'requests' => $requestCount,
                'limit' => $limit
            ]);
            
            $this->setRateLimitHeaders($limit, 0, $limit);
            return false;
        }
        
        // Add current request timestamp
        $data[] = $now;
        
        // Save updated data
        $this->saveData($file, $data);
        
        // Set rate limit headers
        $remaining = $limit - $requestCount - 1;
        $resetTime = !empty($data) ? min($data) + $window - $now : $window;
        $this->setRateLimitHeaders($limit, max(0, $remaining), max(1, $resetTime));
        
        return true;
    }

    /**
     * Get unique identifier for rate limiting
     * Uses IP for guests, user ID for logged in users
     */
    private function getIdentifier(): string {
        if (isset($_SESSION['user']['id'])) {
            return 'user_' . $_SESSION['user']['id'];
        }
        return 'ip_' . md5($this->getClientIp());
    }

    /**
     * Get client IP address
     */
    private function getClientIp(): string {
        $headers = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_X_FORWARDED_FOR',      // Proxy
            'HTTP_X_REAL_IP',            // Nginx
            'REMOTE_ADDR'                // Direct
        ];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                // Handle comma-separated IPs (X-Forwarded-For)
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
            }
        }
        
        return '0.0.0.0';
    }

    /**
     * Get storage file path for identifier
     */
    private function getStorageFile(string $identifier, string $type): string {
        return $this->storagePath . '/' . $type . '_' . md5($identifier) . '.json';
    }

    /**
     * Load rate limit data from file
     */
    private function loadData(string $file): array {
        if (!file_exists($file)) {
            return [];
        }
        
        $content = file_get_contents($file);
        $data = json_decode($content, true);
        
        return is_array($data) ? $data : [];
    }

    /**
     * Save rate limit data to file
     */
    private function saveData(string $file, array $data): void {
        file_put_contents($file, json_encode(array_values($data)), LOCK_EX);
    }

    /**
     * Set rate limit HTTP headers
     */
    private function setRateLimitHeaders(int $limit, int $remaining, int $reset): void {
        header('X-RateLimit-Limit: ' . $limit);
        header('X-RateLimit-Remaining: ' . max(0, $remaining));
        header('X-RateLimit-Reset: ' . $reset);
    }

    /**
     * Clear rate limit for an identifier (e.g., after successful login)
     */
    public function clear(string $identifier, string $type = 'login'): void {
        $file = $this->getStorageFile($identifier, $type);
        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * Get remaining requests for current identifier
     */
    public function getRemaining(string $type = 'general'): array {
        $identifier = $this->getIdentifier();
        $file = $this->getStorageFile($identifier, $type);
        $data = $this->loadData($file);
        
        $limits = [
            'general' => self::DEFAULT_LIMIT,
            'login' => self::LOGIN_LIMIT,
            'signup' => self::SIGNUP_LIMIT,
            'api' => 100
        ];
        
        $windows = [
            'general' => self::DEFAULT_WINDOW,
            'login' => self::LOGIN_WINDOW,
            'signup' => self::SIGNUP_WINDOW,
            'api' => 60
        ];
        
        $limit = $limits[$type] ?? self::DEFAULT_LIMIT;
        $window = $windows[$type] ?? self::DEFAULT_WINDOW;
        
        $now = time();
        $data = array_filter($data, fn($timestamp) => ($now - $timestamp) < $window);
        
        return [
            'limit' => $limit,
            'remaining' => max(0, $limit - count($data)),
            'reset' => !empty($data) ? min($data) + $window : $window
        ];
    }
}
