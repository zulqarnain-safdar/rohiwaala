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
        Schema::create('bull_semen_technician', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bull_semen_id');
            $table->unsignedBigInteger('technician_id');
            $table->timestamps();

            $table->foreign('bull_semen_id')->references('id')->on('bull_semen')->onDelete('cascade');
            $table->foreign('technician_id')->references('id')->on('technicians')->onDelete('cascade');
            
            // Ensure unique combination
            $table->unique(['bull_semen_id', 'technician_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bull_semen_technician');
    }
};