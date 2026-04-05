<?php
namespace App\Services;

/**
 * Logger Service
 * Handles all application logging with different severity levels
 * Logs are written to files in the logs directory
 */
class Logger {
    private static ?Logger $instance = null;
    private string $logPath;
    
    // Log levels
    const EMERGENCY = 'EMERGENCY';
    const ALERT = 'ALERT';
    const CRITICAL = 'CRITICAL';
    const ERROR = 'ERROR';
    const WARNING = 'WARNING';
    const NOTICE = 'NOTICE';
    const INFO = 'INFO';
    const DEBUG = 'DEBUG';

    private function __construct() {
        $this->logPath = dirname(__DIR__, 2) . '/logs/' . date('Y-m-d') . '.log';
    }

    public static function getInstance(): Logger {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Log a message with given level
     * @param string $level Log severity level
     * @param string $message The message to log
     * @param array $context Additional context data
     */
    public function log(string $level, string $message, array $context = []): void {
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' | Context: ' . json_encode($context) : '';
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
        $userId = $_SESSION['user']['id'] ?? 'guest';
        
        $logEntry = "[$timestamp] [$level] [$ip] [User:$userId] $message$contextStr" . PHP_EOL;
        
        file_put_contents($this->logPath, $logEntry, FILE_APPEND | LOCK_EX);
    }

    /**
     * Log emergency - system is unusable
     */
    public function emergency(string $message, array $context = []): void {
        $this->log(self::EMERGENCY, $message, $context);
    }

    /**
     * Log error - error conditions that need attention
     */
    public function error(string $message, array $context = []): void {
        $this->log(self::ERROR, $message, $context);
    }

    /**
     * Log warning - warning conditions that should be addressed
     */
    public function warning(string $message, array $context = []): void {
        $this->log(self::WARNING, $message, $context);
    }

    /**
     * Log info - interesting events (user logins, etc.)
     */
    public function info(string $message, array $context = []): void {
        $this->log(self::INFO, $message, $context);
    }

    /**
     * Log debug - detailed debug information
     */
    public function debug(string $message, array $context = []): void {
        $this->log(self::DEBUG, $message, $context);
    }

    /**
     * Log authentication events
     */
    public function auth(string $action, bool $success, string $email = '', array $extra = []): void {
        $status = $success ? 'SUCCESS' : 'FAILED';
        $context = array_merge(['email' => $email, 'status' => $status], $extra);
        $this->log(self::INFO, "AUTH: $action - $status", $context);
    }

    /**
     * Log security events (suspicious activity)
     */
    public function security(string $event, array $context = []): void {
        $this->log(self::WARNING, "SECURITY: $event", $context);
    }
}
