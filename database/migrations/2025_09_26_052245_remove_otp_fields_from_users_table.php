<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove OTP-related fields from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['otp', 'otp_expires_at']);
            // Keep is_verified field as it's still useful for general verification status
        });
        
        // Drop the otp_tokens table
        Schema::dropIfExists('otp_tokens');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate otp_tokens table
        Schema::create('otp_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('mobile');
            $table->string('token');
            $table->timestamp('expires_at');
            $table->boolean('is_used')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->string('provider')->default('users');
            $table->timestamps();
            
            $table->index(['mobile', 'provider']);
            $table->index(['token', 'provider']);
            $table->index('expires_at');
        });
        
        // Re-add OTP fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('otp')->nullable()->after('mobile');
            $table->timestamp('otp_expires_at')->nullable()->after('otp');
        });
    }
};