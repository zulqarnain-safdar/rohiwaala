# WhatsApp Sandbox Setup Guide

## Step 1: Join the Twilio WhatsApp Sandbox

1. **Go to Twilio Console**: https://console.twilio.com/
2. **Navigate to WhatsApp Sandbox**: 
   - Go to Messaging → Try it out → Send a WhatsApp message
   - Or visit: https://console.twilio.com/us1/develop/sms/try-it-out/whatsapp-learn

3. **Find Your Sandbox Code**:
   - Look for a message like: "Send 'join <sandbox-code>' to +1 415 523 8886"
   - The sandbox code is usually a two-word phrase like "sandbox-code"

4. **Join the Sandbox**:
   - Open WhatsApp on your phone
   - Send a message to **+1 415 523 8886**
   - Type: `join <your-sandbox-code>`
   - Example: `join sandbox-code`

5. **Confirm You're Connected**:
   - You should receive a confirmation message
   - Your phone number is now connected to the sandbox

## Step 2: Test the Connection

Run the test script:

```bash
php test_twilio.php
```

This will send a test message to your WhatsApp.

## Step 3: Update Your .env File

Add these lines to your `.env` file:

```env
# Twilio Configuration
TWILIO_SID=ACbafeda7ca0dcc46fc2c58281b9cc8e62
TWILIO_TOKEN=6022221c3e259c9666950f8e2c5d8488
TWILIO_FROM=+1234567890
TWILIO_WHATSAPP_FROM=+14155238886
```

## Step 4: Test the Registration

1. **Clear Laravel cache**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Try registering a new user**
3. **Check if you receive the OTP on WhatsApp**

## Troubleshooting

### If you don't receive messages:

1. **Check if you joined the sandbox**:
   - Send `join <sandbox-code>` to +1 415 523 8886
   - You should get a confirmation message

2. **Check your phone number format**:
   - Make sure it includes country code (+92 for Pakistan)
   - Example: +923083366618

3. **Check Twilio Console**:
   - Go to Monitor → Logs → Messages
   - Look for your message attempts
   - Check the status and error messages

4. **Check Laravel logs**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

### Common Issues:

1. **"The 'From' number is not valid"**:
   - Make sure you're using `whatsapp:+14155238886`
   - This is the Twilio sandbox number

2. **"Unable to create record"**:
   - Your phone number might not be in the sandbox
   - Join the sandbox first

3. **"Message not received"**:
   - Check if you joined the sandbox correctly
   - Make sure your phone number is correct

## Production Setup (Later)

For production, you'll need:
1. WhatsApp Business API approval
2. A verified WhatsApp Business number
3. Approved message templates
4. Different configuration

But for now, the sandbox is perfect for testing!

