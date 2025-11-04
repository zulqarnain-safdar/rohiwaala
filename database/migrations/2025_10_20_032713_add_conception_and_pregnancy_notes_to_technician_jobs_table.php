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
            $table->text('conception_notes')->nullable()->after('cow_status');
            $table->text('pregnancy_notes')->nullable()->after('conception_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('technician_jobs', function (Blueprint $table) {
            $table->dropColumn(['conception_notes', 'pregnancy_notes']);
        });
    }
};
