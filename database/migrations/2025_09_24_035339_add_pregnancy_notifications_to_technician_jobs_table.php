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
        Schema::table('technician_jobs', function (Blueprint $table) {
            $table->boolean('notified_100_days')->default(false)->after('calf_image');
            $table->boolean('notified_200_days')->default(false)->after('notified_100_days');
            $table->boolean('notified_250_days')->default(false)->after('notified_200_days');
            $table->boolean('notified_270_days')->default(false)->after('notified_250_days');
            $table->boolean('notified_delivery_due')->default(false)->after('notified_270_days');
            $table->datetime('last_notification_sent')->nullable()->after('notified_delivery_due');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('technician_jobs', function (Blueprint $table) {
            $table->dropColumn([
                'notified_100_days',
                'notified_200_days', 
                'notified_250_days',
                'notified_270_days',
                'notified_delivery_due',
                'last_notification_sent'
            ]);
        });
    }
};