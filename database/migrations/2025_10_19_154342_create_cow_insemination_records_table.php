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
        Schema::create('cow_insemination_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('technician_jobs')->onDelete('cascade');
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');
            $table->enum('cow_in_heat', ['yes', 'no'])->nullable();
            $table->string('cow_photo')->nullable();
            $table->string('straw_barcode_code')->nullable();
            $table->string('straw_photo')->nullable();
            $table->decimal('amount_received', 10, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cow_insemination_records');
    }
};