<?php
// Test OTP Verification
require_once 'vendor/autoload.php';

use App\Models\User;
use App\Services\OtpService;

// Find the latest user
$user = User::latest()->first();

if (!$user) {
    echo "No users found in database\n";
    exit;
}

echo "Testing OTP verification for user: {$user->id}\n";
echo "User OTP: " . ($user->otp ?? 'null') . "\n";
echo "OTP expires at: " . ($user->otp_expires_at ?? 'null') . "\n";

// Test with the stored OTP
if ($user->otp) {
    $otpService = new OtpService();
    $result = $otpService->verifyOtp($user, $user->otp);
    echo "OTP verification result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
} else {
    echo "No OTP found for user\n";
}
?>

