<?php
namespace App\Helpers;

/**
 * Sanitizer Helper
 * Handles all input sanitization before database storage
 * IMPORTANT: This is for INPUT sanitization, not XSS prevention (use htmlspecialchars for output)
 */
class Sanitizer {

    /**
     * Sanitize a string for database storage
     * Removes dangerous characters and trims whitespace
     */
    public static function sanitizeString(?string $value): string {
        if ($value === null) return '';
        
        // Trim whitespace
        $value = trim($value);
        
        // Remove null bytes (security risk)
        $value = str_replace(chr(0), '', $value);
        
        // Strip tags for basic safety
        $value = strip_tags($value);
        
        // Remove control characters
        $value = preg_replace('/[\x00-\x1F\x7F]/', '', $value);
        
        return $value;
    }

    /**
     * Sanitize an email address
     */
    public static function sanitizeEmail(?string $email): string {
        if ($email === null) return '';
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }

    /**
     * Sanitize an integer
     */
    public static function sanitizeInt($value): int {
        return (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * Sanitize a float/double
     */
    public static function sanitizeFloat($value): float {
        return (float) filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    /**
     * Sanitize URL
     */
    public static function sanitizeUrl(?string $url): string {
        if ($url === null) return '';
        return filter_var(trim($url), FILTER_SANITIZE_URL);
    }

    /**
     * Sanitize HTML (allow some tags)
     */
    public static function sanitizeHtml(?string $html): string {
        if ($html === null) return '';
        
        // Strip dangerous tags but allow safe ones
        $allowed = '<b><i><u><strong><em><p><br><ul><ol><li>';
        return strip_tags(trim($html), $allowed);
    }

    /**
     * Sanitize phone number (allow only numbers, spaces, +, -)
     */
    public static function sanitizePhone(?string $phone): string {
        if ($phone === null) return '';
        return preg_replace('/[^0-9+\-\s]/', '', trim($phone));
    }

    /**
     * Sanitize array of strings
     */
    public static function sanitizeArray(array $array): array {
        return array_map([self::class, 'sanitizeString'], $array);
    }

    /**
     * Sanitize POST data (clean all inputs)
     */
    public static function sanitizePost(): array {
        $sanitized = [];
        foreach ($_POST as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = self::sanitizeArray($value);
            } else {
                $sanitized[$key] = self::sanitizeString($value);
            }
        }
        return $sanitized;
    }

    /**
     * Sanitize GET data (clean all inputs)
     */
    public static function sanitizeGet(): array {
        $sanitized = [];
        foreach ($_GET as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = self::sanitizeArray($value);
            } else {
                $sanitized[$key] = self::sanitizeString($value);
            }
        }
        return $sanitized;
    }
}
