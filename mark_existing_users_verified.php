<?php
// Script to mark existing users as verified
require_once 'vendor/autoload.php';

use App\Models\User;

echo "Marking existing users as verified...\n";

// Get all users who are not verified
$users = User::where('is_verified', false)->get();

echo "Found " . $users->count() . " unverified users\n";

foreach ($users as $user) {
    $user->update(['is_verified' => true]);
    echo "Marked user {$user->id} ({$user->name}) as verified\n";
}

echo "Done! All existing users can now login without OTP verification.\n";
echo "New users will still need to verify their phone numbers.\n";
?>

