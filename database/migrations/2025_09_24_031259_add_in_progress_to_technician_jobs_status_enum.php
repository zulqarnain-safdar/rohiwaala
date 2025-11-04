<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, we need to use raw SQL to modify enum values
        DB::statement("ALTER TABLE technician_jobs MODIFY COLUMN status ENUM('pending', 'accepted', 'rejected', 'completed', 'failed', 'in_progress') DEFAULT 'pending'");
        DB::statement("ALTER TABLE technician_jobs MODIFY COLUMN progress_status ENUM('pending', 'accepted', 'rejected', 'administered', 'failed_no_heat', 'in_progress') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove in_progress from enum values
        DB::statement("ALTER TABLE technician_jobs MODIFY COLUMN status ENUM('pending', 'accepted', 'rejected', 'completed', 'failed') DEFAULT 'pending'");
        DB::statement("ALTER TABLE technician_jobs MODIFY COLUMN progress_status ENUM('pending', 'accepted', 'rejected', 'administered', 'failed_no_heat') DEFAULT 'pending'");
    }
};
