<?php
// Test Twilio WhatsApp Setup
require_once 'vendor/autoload.php';

use Twilio\Rest\Client;

// Your Twilio credentials
$sid = "ACbafeda7ca0dcc46fc2c58281b9cc8e62";
$token = "6022221c3e259c9666950f8e2c5d8488"; // Replace with your actual token
$twilio = new Client($sid, $token);

// Test phone number (replace with your actual number)
$testPhoneNumber = "+923083366618"; // Your phone number

try {
    echo "Testing Twilio WhatsApp connection...\n";
    
    // Send a simple text message (not template)
    $message = $twilio->messages->create(
        "whatsapp:{$testPhoneNumber}", // to
        [
            "from" => "whatsapp:+14155238886", // Twilio sandbox number
            "body" => "Hello! This is a test message from BreedCare Platform. If you receive this, your WhatsApp setup is working correctly."
        ]
    );
    
    echo "Message sent successfully!\n";
    echo "Message SID: " . $message->sid . "\n";
    echo "Status: " . $message->status . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Error Code: " . $e->getCode() . "\n";
}
?>

