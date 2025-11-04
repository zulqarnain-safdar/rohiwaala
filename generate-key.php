<?php
/**
 * Standalone PHP script to generate Laravel application key
 * This runs outside of Laravel framework
 */

// Generate a secure 32-character key
$key = 'base64:' . base64_encode(random_bytes(32));

// Path to .env file (adjust if needed)
$envPath = __DIR__ . '/.env';

echo "<h1>Laravel Application Key Generator</h1>";
echo "<p><strong>Generated Key:</strong> <code>$key</code></p>";

try {
    // Check if .env file exists
    if (!file_exists($envPath)) {
        echo "<p style='color: red;'>❌ .env file not found at: $envPath</p>";
        echo "<p>Please create a .env file first with your database and app configuration.</p>";
        exit;
    }
    
    // Read the .env file
    $envContent = file_get_contents($envPath);
    
    // Update or add APP_KEY
    if (strpos($envContent, 'APP_KEY=') !== false) {
        $envContent = preg_replace('/APP_KEY=.*/', 'APP_KEY=' . $key, $envContent);
        echo "<p style='color: green;'>✅ Updated existing APP_KEY in .env file</p>";
    } else {
        $envContent .= "\nAPP_KEY=" . $key . "\n";
        echo "<p style='color: green;'>✅ Added new APP_KEY to .env file</p>";
    }
    
    // Write back to .env file
    if (file_put_contents($envPath, $envContent)) {
        echo "<p style='color: green;'>✅ .env file updated successfully!</p>";
        echo "<p><strong>Next steps:</strong></p>";
        echo "<ul>";
        echo "<li>Delete this file (generate-key.php) for security</li>";
        echo "<li>Visit: <a href='/demo/'>https://dangardoctor.pk/demo/</a></li>";
        echo "<li>Or test: <a href='/demo/test'>https://dangardoctor.pk/demo/test</a></li>";
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>❌ Failed to write to .env file. Check file permissions.</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><strong>Manual Method:</strong> If this doesn't work, manually add this line to your .env file:</p>";
echo "<code>APP_KEY=$key</code>";
?>
