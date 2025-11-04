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
            $table->string('cow_photo')->nullable()->after('semen_straw_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('technician_jobs', function (Blueprint $table) {
            $table->dropColumn('cow_photo');
        });
    }
};
