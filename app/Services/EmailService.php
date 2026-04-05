<?php
namespace App\Services;

use App\Configs\Model;
use PDO;

/**
 * EmailService
 * Handles email verification tokens and email sending
 * 
 * Note: This uses a simple file-based approach for demonstration.
 * In production, use a proper email service like:
 * - PHPMailer + SMTP
 * - SendGrid
 * - Mailgun
 * - AWS SES
 */
class EmailService {
    private string $baseUrl;
    private string $tokenPath;
    private Logger $logger;

    // Token validity: 24 hours
    private const TOKEN_EXPIRY = 86400;

    public function __construct() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $this->baseUrl = $protocol . $host . '/surfManager/';
        
        $this->tokenPath = dirname(__DIR__, 2) . '/storage/email_tokens';
        $this->logger = Logger::getInstance();
        
        if (!is_dir($this->tokenPath)) {
            mkdir($this->tokenPath, 0755, true);
        }
    }

    /**
     * Generate and store verification token for user
     * @param int $userId User ID
     * @param string $email User email
     * @return string The verification token
     */
    public function generateVerificationToken(int $userId, string $email): string {
        // Generate secure random token
        $token = bin2hex(random_bytes(32));
        $hashedToken = hash('sha256', $token);
        
        $data = [
            'user_id' => $userId,
            'email' => $email,
            'created_at' => time(),
            'expires_at' => time() + self::TOKEN_EXPIRY,
            'used' => false
        ];
        
        // Store hashed token with data
        file_put_contents(
            $this->tokenPath . '/' . $hashedToken . '.json',
            json_encode($data),
            LOCK_EX
        );
        
        $this->logger->info("Verification token generated", [
            'user_id' => $userId,
            'email' => $email
        ]);
        
        return $token;
    }

    /**
     * Get the verification URL
     */
    public function getVerificationUrl(string $token): string {
        return $this->baseUrl . 'auth/verify?token=' . $token;
    }

    /**
     * Verify a token
     * @param string $token The plain text token
     * @return array ['success' => bool, 'user_id' => int|null, 'error' => string|null]
     */
    public function verifyToken(string $token): array {
        $hashedToken = hash('sha256', $token);
        $file = $this->tokenPath . '/' . $hashedToken . '.json';
        
        // Check if token file exists
        if (!file_exists($file)) {
            $this->logger->security("Invalid verification token attempted", [
                'token_hash' => substr($hashedToken, 0, 16) . '...'
            ]);
            return ['success' => false, 'user_id' => null, 'error' => 'Invalid token'];
        }
        
        // Load and parse token data
        $data = json_decode(file_get_contents($file), true);
        
        // Check if token already used
        if (!empty($data['used'])) {
            return ['success' => false, 'user_id' => null, 'error' => 'Token already used'];
        }
        
        // Check if token expired
        if (time() > $data['expires_at']) {
            $this->logger->warning("Expired verification token used", [
                'user_id' => $data['user_id'] ?? null
            ]);
            return ['success' => false, 'user_id' => null, 'error' => 'Token expired'];
        }
        
        // Mark token as used
        $data['used'] = true;
        file_put_contents($file, json_encode($data), LOCK_EX);
        
        $this->logger->info("Email verified successfully", [
            'user_id' => $data['user_id']
        ]);
        
        return [
            'success' => true,
            'user_id' => $data['user_id'],
            'error' => null
        ];
    }

    /**
     * Send verification email
     * 
     * Note: This is a placeholder that logs the email content.
     * In production, integrate with a real email service.
     */
    public function sendVerificationEmail(string $email, string $name, string $token): bool {
        $verifyUrl = $this->getVerificationUrl($token);
        
        $subject = "Verify your SurfManager account";
        
        $body = "
        <html>
        <body style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;'>
                <h1 style='color: white; margin: 0;'>🏄‍♂️ SurfManager</h1>
            </div>
            <div style='background: white; padding: 30px; border-radius: 0 0 10px 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
                <h2 style='color: #333;'>Hi $name,</h2>
                <p style='color: #666; font-size: 16px;'>
                    Thank you for signing up! Please verify your email address by clicking the button below:
                </p>
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='$verifyUrl' style='display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 40px; text-decoration: none; border-radius: 8px; font-weight: bold;'>
                        Verify Email Address
                    </a>
                </div>
                <p style='color: #666; font-size: 14px;'>
                    Or copy and paste this link into your browser:<br>
                    <a href='$verifyUrl' style='color: #667eea;'>$verifyUrl</a>
                </p>
                <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;'>
                <p style='color: #999; font-size: 12px;'>
                    This link will expire in 24 hours. If you didn't create an account, you can safely ignore this email.
                </p>
            </div>
        </body>
        </html>
        ";
        
        // Log the email (in production, actually send it)
        $this->logger->info("Verification email prepared", [
            'to' => $email,
            'subject' => $subject
        ]);
        
        // For demonstration, we'll just log it. 
        // In production, use PHPMailer or similar:
        // mail($email, $subject, $body, $headers);
        
        return true;
    }

    /**
     * Send password reset email
     */
    public function sendPasswordResetEmail(string $email, string $name, string $token): bool {
        $resetUrl = $this->baseUrl . 'auth/reset-password?token=' . $token;
        
        $subject = "Reset your SurfManager password";
        
        $body = "
        <html>
        <body style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;'>
                <h1 style='color: white; margin: 0;'>🔐 Password Reset</h1>
            </div>
            <div style='background: white; padding: 30px; border-radius: 0 0 10px 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
                <h2 style='color: #333;'>Hi $name,</h2>
                <p style='color: #666; font-size: 16px;'>
                    We received a request to reset your password. Click the button below to create a new password:
                </p>
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='$resetUrl' style='display: inline-block; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 15px 40px; text-decoration: none; border-radius: 8px; font-weight: bold;'>
                        Reset Password
                    </a>
                </div>
                <p style='color: #666; font-size: 14px;'>
                    Or copy and paste this link into your browser:<br>
                    <a href='$resetUrl' style='color: #f5576c;'>$resetUrl</a>
                </p>
                <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;'>
                <p style='color: #999; font-size: 12px;'>
                    This link will expire in 1 hour. If you didn't request a password reset, please ignore this email.
                </p>
            </div>
        </body>
        </html>
        ";
        
        $this->logger->info("Password reset email prepared", [
            'to' => $email,
            'subject' => $subject
        ]);
        
        return true;
    }

    /**
     * Clean up expired tokens (call periodically via cron)
     */
    public function cleanupExpiredTokens(): int {
        $count = 0;
        $files = glob($this->tokenPath . '/*.json');
        
        foreach ($files as $file) {
            $data = json_decode(file_get_contents($file), true);
            
            // Remove if expired OR used for more than 24 hours
            if (!empty($data['used']) && (time() - $data['expires_at']) > 86400) {
                unlink($file);
                $count++;
            } elseif (time() > $data['expires_at'] && empty($data['used'])) {
                unlink($file);
                $count++;
            }
        }
        
        if ($count > 0) {
            $this->logger->info("Cleaned up $count expired tokens");
        }
        
        return $count;
    }
}
