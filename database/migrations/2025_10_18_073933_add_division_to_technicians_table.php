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
            // Add division field after province
            $table->string('division')->nullable()->after('province');
            
            // Modify tehsil to be tehsil/city combined
            $table->string('tehsil_city')->nullable()->after('district');
            
            // Drop the separate city column
            $table->dropColumn('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('technicians', function (Blueprint $table) {
            // Add back the city column
            $table->string('city')->nullable()->after('tehsil');
            
            // Drop the combined tehsil_city column
            $table->dropColumn('tehsil_city');
            
            // Drop the division column
            $table->dropColumn('division');
        });
    }
};