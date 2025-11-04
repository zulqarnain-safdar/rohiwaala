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
        Schema::table('technicians', function (Blueprint $table) {
            $table->time('working_hours_start')->nullable()->after('availability');
            $table->time('working_hours_end')->nullable()->after('working_hours_start');
            $table->boolean('available_24_7')->default(false)->after('working_hours_end');
            $table->json('service_areas')->nullable()->after('available_24_7');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('technicians', function (Blueprint $table) {
            $table->dropColumn(['working_hours_start', 'working_hours_end', 'available_24_7', 'service_areas']);
        });
    }
};
