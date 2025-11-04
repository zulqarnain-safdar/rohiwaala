# Twilio WhatsApp OTP Setup Guide

This guide will help you set up WhatsApp OTP verification using Twilio for the BreedCare Platform.

## Prerequisites

1. Twilio Account (https://www.twilio.com/)
2. WhatsApp Business Account
3. Twilio WhatsApp Sandbox or Production WhatsApp Business API

## Configuration Steps

### 1. Twilio Account Setup

1. Sign up for a Twilio account at https://www.twilio.com/
2. Get your Account SID and Auth Token from the Twilio Console Dashboard
3. Purchase a phone number for SMS (if using SMS fallback)

### 2. WhatsApp Business Setup

#### Option A: WhatsApp Sandbox (For Testing)
1. Go to https://console.twilio.com/us1/develop/sms/try-it-out/whatsapp-learn
2. Follow the instructions to set up WhatsApp Sandbox
3. Note down your sandbox WhatsApp number (format: `whatsapp:+14155238886`)

#### Option B: WhatsApp Business API (For Production)
1. Apply for WhatsApp Business API through Twilio
2. Complete the verification process
3. Get your approved WhatsApp Business number

### 3. Environment Configuration

Add the following variables to your `.env` file:

```env
# Twilio Configuration
TWILIO_SID=your_account_sid_here
TWILIO_TOKEN=your_auth_token_here
TWILIO_FROM=+1234567890
TWILIO_WHATSAPP_FROM=whatsapp:+1234567890
```

### 4. Phone Number Formatting

The OTP service automatically formats phone numbers for Pakistan (+92):
- Input: `03001234567` → Output: `+923001234567`
- Input: `3001234567` → Output: `+923001234567`

### 5. Testing the Setup

1. Start your Laravel application
2. Try registering a new user
3. Check your WhatsApp for the verification code
4. If WhatsApp fails, SMS will be sent as fallback

## Features

### WhatsApp OTP Features
- ✅ 6-digit OTP generation
- ✅ WhatsApp message delivery
- ✅ SMS fallback if WhatsApp fails
- ✅ 10-minute expiration
- ✅ Automatic cleanup of expired OTPs
- ✅ Resend functionality
- ✅ Pakistan phone number formatting

### Security Features
- ✅ OTP expiration after 10 minutes
- ✅ One-time use OTPs
- ✅ Rate limiting (implement as needed)
- ✅ Secure OTP storage

## Troubleshooting

### Common Issues

1. **WhatsApp not receiving messages**
   - Check if the number is registered with WhatsApp
   - Verify the WhatsApp sandbox setup
   - Ensure the phone number format is correct

2. **SMS not working**
   - Verify Twilio credentials
   - Check if the phone number is valid
   - Ensure sufficient Twilio credits

3. **OTP not being generated**
   - Check Laravel logs for errors
   - Verify database migration ran successfully
   - Check Twilio service configuration

### Debug Steps

1. Check Laravel logs: `tail -f storage/logs/laravel.log`
2. Test Twilio credentials in Twilio Console
3. Verify environment variables are loaded
4. Check database for OTP fields

## Production Considerations

1. **Rate Limiting**: Implement rate limiting for OTP requests
2. **Monitoring**: Set up monitoring for OTP delivery rates
3. **Backup**: Consider multiple SMS providers for redundancy
4. **Compliance**: Ensure compliance with local regulations

## Cost Optimization

1. **WhatsApp vs SMS**: WhatsApp is generally cheaper for international messages
2. **Bulk Messaging**: Consider Twilio's bulk messaging rates
3. **Geographic Pricing**: Check rates for Pakistan specifically

## Security Best Practices

1. **OTP Expiration**: Keep OTP expiration short (10 minutes)
2. **Rate Limiting**: Limit OTP requests per phone number
3. **Cleanup**: Regularly clean up expired OTPs
4. **Logging**: Log OTP attempts for security monitoring

## Support

For issues related to:
- Twilio setup: Contact Twilio Support
- WhatsApp Business: Contact WhatsApp Business Support
- Application issues: Check Laravel logs and documentation

