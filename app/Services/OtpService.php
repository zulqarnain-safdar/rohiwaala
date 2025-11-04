<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class OtpService
{
    protected $twilioClient;
    protected $twilioFrom;

    public function __construct()
    {
        $this->twilioFrom = config('services.twilio.whatsapp_from');
        
        $this->twilioClient = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    /**
     * Generate a 6-digit OTP
     */
    public function generateOtp(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Send OTP via WhatsApp using Twilio
     */
    public function sendOtpViaWhatsApp(string $mobile, string $otp): bool
    {
        try {
            // Format mobile number for WhatsApp (add country code if not present)
            $formattedMobile = $this->formatMobileForWhatsApp($mobile);
            
            $message = "Your BreedCare verification code is: {$otp}. This code will expire in 10 minutes. Do not share this code with anyone.";
            
            // Always use the Twilio sandbox number for WhatsApp
            $fromNumber = 'whatsapp:+14155238886';
            
            $this->twilioClient->messages->create(
                "whatsapp:{$formattedMobile}",
                [
                    'from' => $fromNumber,
                    'body' => $message
                ]
            );

            Log::info("OTP sent via WhatsApp to {$formattedMobile}");
            return true;
            
        } catch (\Exception $e) {
            Log::error("Failed to send OTP via WhatsApp: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send OTP via SMS as fallback
     */
    public function sendOtpViaSms(string $mobile, string $otp): bool
    {
        try {
            $smsFrom = config('services.twilio.from');
            
            // Skip SMS if no valid Twilio number is configured
            if (empty($smsFrom) || $smsFrom === '+1234567890') {
                Log::info("SMS fallback skipped - no valid Twilio SMS number configured");
                return false;
            }
            
            $formattedMobile = $this->formatMobileForSms($mobile);
            
            $message = "Your BreedCare verification code is: {$otp}. This code will expire in 10 minutes.";
            
            $this->twilioClient->messages->create(
                $formattedMobile,
                [
                    'from' => $smsFrom,
                    'body' => $message
                ]
            );

            Log::info("OTP sent via SMS to {$formattedMobile}");
            return true;
            
        } catch (\Exception $e) {
            Log::error("Failed to send OTP via SMS: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Store OTP in database for a user
     */
    public function storeOtp(User $user, string $otp): void
    {
        $expiresAt = now()->addMinutes(10);
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => $expiresAt
        ]);
        
        Log::info("OTP stored for user", [
            'user_id' => $user->id,
            'otp' => $otp,
            'expires_at' => $expiresAt,
            'current_time' => now()
        ]);
    }

    /**
     * Verify OTP for a user
     */
    public function verifyOtp(User $user, string $otp): bool
    {
        Log::info("OTP Verification Debug", [
            'user_id' => $user->id,
            'stored_otp' => $user->otp,
            'provided_otp' => $otp,
            'otp_expires_at' => $user->otp_expires_at,
            'current_time' => now(),
            'is_expired' => $user->otp_expires_at ? now()->isAfter($user->otp_expires_at) : 'no_expiry_time'
        ]);

        if (!$user->otp || !$user->otp_expires_at) {
            Log::warning("OTP verification failed: No OTP or expiry time", [
                'user_id' => $user->id,
                'has_otp' => !empty($user->otp),
                'has_expiry' => !empty($user->otp_expires_at)
            ]);
            return false;
        }

        if (now()->isAfter($user->otp_expires_at)) {
            Log::warning("OTP verification failed: OTP expired", [
                'user_id' => $user->id,
                'expires_at' => $user->otp_expires_at,
                'current_time' => now()
            ]);
            return false;
        }

        if ($user->otp !== $otp) {
            Log::warning("OTP verification failed: OTP mismatch", [
                'user_id' => $user->id,
                'stored_otp' => $user->otp,
                'provided_otp' => $otp,
                'match' => $user->otp === $otp
            ]);
            return false;
        }

        // Clear OTP after successful verification
        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
            'is_verified' => true
        ]);

        Log::info("OTP verification successful", ['user_id' => $user->id]);
        return true;
    }

    /**
     * Resend OTP for a user
     */
    public function resendOtp(User $user): bool
    {
        $otp = $this->generateOtp();
        
        // Try WhatsApp first, then SMS as fallback
        $sent = $this->sendOtpViaWhatsApp($user->mobile, $otp);
        
        if (!$sent) {
            $sent = $this->sendOtpViaSms($user->mobile, $otp);
        }

        if ($sent) {
            $this->storeOtp($user, $otp);
            return true;
        }

        return false;
    }

    /**
     * Format mobile number for WhatsApp
     */
    private function formatMobileForWhatsApp(string $mobile): string
    {
        // Remove any non-digit characters
        $mobile = preg_replace('/[^0-9]/', '', $mobile);
        
        // Add country code if not present (assuming Pakistan +92)
        if (!str_starts_with($mobile, '92')) {
            if (str_starts_with($mobile, '0')) {
                $mobile = '92' . substr($mobile, 1);
            } else {
                $mobile = '92' . $mobile;
            }
        }
        
        return $mobile;
    }

    /**
     * Format mobile number for SMS
     */
    private function formatMobileForSms(string $mobile): string
    {
        // Remove any non-digit characters
        $mobile = preg_replace('/[^0-9]/', '', $mobile);
        
        // Add country code if not present (assuming Pakistan +92)
        if (!str_starts_with($mobile, '92')) {
            if (str_starts_with($mobile, '0')) {
                $mobile = '92' . substr($mobile, 1);
            } else {
                $mobile = '92' . $mobile;
            }
        }
        
        return '+' . $mobile;
    }

    /**
     * Check if OTP is expired
     */
    public function isOtpExpired(User $user): bool
    {
        if (!$user->otp_expires_at) {
            return true;
        }

        return now()->isAfter($user->otp_expires_at);
    }

    /**
     * Clear expired OTP
     */
    public function clearExpiredOtp(User $user): void
    {
        if ($this->isOtpExpired($user)) {
            $user->update([
                'otp' => null,
                'otp_expires_at' => null
            ]);
        }
    }
}
