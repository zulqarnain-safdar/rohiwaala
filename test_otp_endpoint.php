<?php
// Test OTP verification endpoint
$url = 'http://localhost/api/auth/verify-otp';
$data = [
    'user_id' => 33, // Replace with actual user ID
    'otp' => '123456' // Replace with actual OTP
];

$options = [
    'http' => [
        'header' => "Content-type: application/json\r\n",
        'method' => 'POST',
        'content' => json_encode($data)
    ]
];

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

echo "Response: " . $result . "\n";
?>

