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
        Schema::create('cattle_farms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('father_name')->nullable();
            $table->string('picture')->nullable();
            $table->string('latitude');
            $table->string('longitude');
            $table->integer('number_of_cows')->default(0);
            $table->integer('number_of_calves')->default(0);
            $table->integer('number_of_bulls')->default(0);
            $table->integer('number_of_goats')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cattle_farms');
    }
};
