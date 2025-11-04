<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Farmer;
use App\Models\Technician;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|in:farmer,technician',
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|unique:users,mobile',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'is_verified' => false, // Require OTP verification
        ]);

        // Create role-specific profile
        if ($request->role === 'farmer') {
            Farmer::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'mobile' => $request->mobile,
            ]);
        } elseif ($request->role === 'technician') {
            Technician::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'mobile_1' => $request->mobile,
                'status' => 'incomplete',
            ]);
        }

        // Send OTP for verification
        $otpService = new OtpService();
        $otp = $otpService->generateOtp();
        
        // Try WhatsApp first, then SMS as fallback
        $sent = $otpService->sendOtpViaWhatsApp($user->mobile, $otp);
        if (!$sent) {
            $sent = $otpService->sendOtpViaSms($user->mobile, $otp);
        }

        if ($sent) {
            $otpService->storeOtp($user, $otp);
            
            // Refresh user to get updated OTP data
            $user->refresh();
            
            Log::info("Registration successful, OTP stored", [
                'user_id' => $user->id,
                'stored_otp' => $user->otp,
                'otp_expires_at' => $user->otp_expires_at
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Registration successful. Please verify your phone number.',
                'user' => $user,
                'requires_verification' => true,
                'user_id' => $user->id,
            ], 201);
        } else {
            // For development/testing, we can still proceed without OTP
            // In production, you might want to delete the user
            if (config('app.debug')) {
                // In debug mode, mark as verified and proceed
                $user->update(['is_verified' => true]);
                $token = $user->createToken('auth_token')->plainTextToken;
                
                return response()->json([
                    'success' => true,
                    'message' => 'Registration successful (Debug mode - OTP skipped)',
                    'user' => $user,
                    'token' => $token,
                ], 201);
            } else {
                // In production, delete the user if OTP fails
                $user->delete();
                
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send verification code. Please try again.'
                ], 500);
            }
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|string',
            'password' => 'required|string',
            'remember_me' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('mobile', $request->mobile)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Check if user is verified
        if (!$user->is_verified) {
            // Send OTP for verification
            $otpService = new OtpService();
            $otp = $otpService->generateOtp();
            
            // Try WhatsApp first, then SMS as fallback
            $sent = $otpService->sendOtpViaWhatsApp($user->mobile, $otp);
            if (!$sent) {
                $sent = $otpService->sendOtpViaSms($user->mobile, $otp);
            }

            if ($sent) {
                $otpService->storeOtp($user, $otp);
                
                Log::info("Login verification OTP sent", [
                    'user_id' => $user->id,
                    'mobile' => $user->mobile,
                    'otp' => $otp
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Please verify your phone number before logging in. Check your WhatsApp for the verification code.',
                    'requires_verification' => true,
                    'user_id' => $user->id
                ], 403);
            } else {
                // If OTP sending fails, still allow verification but with a warning
                Log::warning("Failed to send OTP during login verification", [
                    'user_id' => $user->id,
                    'mobile' => $user->mobile
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Please verify your phone number before logging in. If you don\'t receive the code, please try the resend option.',
                    'requires_verification' => true,
                    'user_id' => $user->id
                ], 403);
            }
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = response()->json([
            'success' => true,
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ]);

        // Attach cookie directly on the response to avoid middleware queue issues on API route
        if ($request->boolean('remember_me')) {
            $secure = $request->isSecure();
            $response->cookie(
                'remember_mobile',
                $user->mobile,
                60 * 24 * 30,
                '/',
                null,
                $secure,
                false,
                false,
                'Lax'
            );
        } else {
            $response->withCookie(Cookie::forget('remember_mobile'));
        }

        return $response;
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Return remembered mobile from cookie, if available.
     */
    public function rememberedMobile(Request $request)
    {
        $mobile = $request->cookie('remember_mobile');
        return response()->json([
            'success' => (bool) $mobile,
            'mobile' => $mobile
        ]);
    }

    public function verifyOtp(Request $request)
    {
        Log::info("OTP Verification Request", [
            'user_id' => $request->user_id,
            'otp' => $request->otp,
            'all_data' => $request->all()
        ]);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            Log::warning("OTP Verification validation failed", [
                'errors' => $validator->errors()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::find($request->user_id);
        if (!$user) {
            Log::warning("OTP Verification failed: User not found", [
                'user_id' => $request->user_id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        Log::info("User found for OTP verification", [
            'user_id' => $user->id,
            'user_otp' => $user->otp,
            'user_otp_expires_at' => $user->otp_expires_at
        ]);

        $otpService = new OtpService();
        
        if ($otpService->verifyOtp($user, $request->otp)) {
            // Generate authentication token
            $token = $user->createToken('auth_token')->plainTextToken;

            Log::info("OTP verification successful", [
                'user_id' => $user->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Phone number verified successfully',
                'user' => $user,
                'token' => $token,
            ]);
        } else {
            Log::warning("OTP verification failed", [
                'user_id' => $user->id,
                'provided_otp' => $request->otp
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired verification code'
            ], 400);
        }
    }

    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $otpService = new OtpService();
        
        if ($otpService->resendOtp($user)) {
            return response()->json([
                'success' => true,
                'message' => 'Verification code sent successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification code. Please try again.'
            ], 500);
        }
    }

    /**
     * Start forgot password flow: send OTP to the user's mobile
     */
    public function forgotPasswordRequestOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('mobile', $request->mobile)->first();
        if (!$user) {
            // Avoid user enumeration: respond with success even if user not found
            return response()->json([
                'success' => true,
                'message' => 'If the mobile exists, an OTP has been sent.'
            ]);
        }

        $otpService = new OtpService();
        $otp = $otpService->generateOtp();

        $sent = $otpService->sendOtpViaWhatsApp($user->mobile, $otp);
        if (!$sent) {
            $sent = $otpService->sendOtpViaSms($user->mobile, $otp);
        }

        if ($sent) {
            $otpService->storeOtp($user, $otp);
            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully',
                'user_id' => $user->id,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to send OTP. Please try again.'
        ], 500);
    }

    /**
     * Verify OTP for forgot password (does not change verification status)
     */
    public function forgotPasswordVerifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::find($request->user_id);
        if (!$user || !$user->otp || !$user->otp_expires_at) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired verification code'
            ], 400);
        }

        if (now()->isAfter($user->otp_expires_at) || $user->otp !== $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired verification code'
            ], 400);
        }

        // At this step we simply confirm the code is valid; do not clear it yet
        return response()->json([
            'success' => true,
            'message' => 'OTP verified. You may now reset your password.'
        ]);
    }

    /**
     * Reset password after verifying OTP
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'otp' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::find($request->user_id);
        if (!$user || !$user->otp || !$user->otp_expires_at) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired verification code'
            ], 400);
        }

        if (now()->isAfter($user->otp_expires_at) || $user->otp !== $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired verification code'
            ], 400);
        }

        $user->password = Hash::make($request->password);
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password has been reset successfully.'
        ]);
    }

}