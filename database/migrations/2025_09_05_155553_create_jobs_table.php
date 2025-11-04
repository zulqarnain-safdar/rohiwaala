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
        Schema::create('technician_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('technician_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'accepted', 'rejected', 'completed', 'failed'])->default('pending');
            $table->enum('progress_status', ['pending', 'accepted', 'rejected', 'administered', 'failed_no_heat'])->default('pending');
            $table->datetime('estimated_visit_at')->nullable();
            $table->datetime('actual_visit_at')->nullable();
            $table->string('visit_latitude')->nullable();
            $table->string('visit_longitude')->nullable();
            $table->text('technician_notes')->nullable();
            $table->text('farmer_notes')->nullable();
            $table->integer('rating')->nullable();
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->string('semen_straw_code')->nullable();
            $table->string('semen_straw_image')->nullable();
            $table->enum('cow_status', ['pregnant', 'miscarried', 'delivered'])->nullable();
            $table->text('delivery_notes')->nullable();
            $table->string('calf_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technician_jobs');
    }
};
