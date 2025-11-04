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
        // Drop the existing foreign key constraint
        Schema::table('order_reassignment_logs', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
        });
        
        // Add the correct foreign key constraint to technician_jobs table
        Schema::table('order_reassignment_logs', function (Blueprint $table) {
            $table->foreign('job_id')->references('id')->on('technician_jobs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the correct foreign key constraint
        Schema::table('order_reassignment_logs', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
        });
        
        // Restore the original foreign key constraint to jobs table
        Schema::table('order_reassignment_logs', function (Blueprint $table) {
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });
    }
};
