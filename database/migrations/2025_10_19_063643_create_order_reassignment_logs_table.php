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
        Schema::create('order_reassignment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->foreignId('from_technician_id')->constrained('technicians')->onDelete('cascade');
            $table->foreignId('to_technician_id')->constrained('technicians')->onDelete('cascade');
            $table->foreignId('reassigned_by_technician_id')->constrained('technicians')->onDelete('cascade');
            $table->text('reason')->nullable();
            $table->json('metadata')->nullable(); // For storing additional data like notes, etc.
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['order_id', 'created_at']);
            $table->index(['from_technician_id', 'created_at']);
            $table->index(['to_technician_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_reassignment_logs');
    }
};
