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
        Schema::create('technicians', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('father_name')->nullable();
            $table->string('cnic')->unique()->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('home_address')->nullable();
            $table->text('clinic_address')->nullable();
            $table->string('degree_year')->nullable();
            $table->string('cnic_front_image')->nullable();
            $table->string('cnic_back_image')->nullable();
            $table->string('degree_front_image')->nullable();
            $table->string('degree_back_image')->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->integer('customers_per_month')->nullable();
            $table->text('areas_of_expertise')->nullable();
            $table->string('mobile_1');
            $table->string('mobile_2')->nullable();
            $table->string('clinic_latitude')->nullable();
            $table->string('clinic_longitude')->nullable();
            $table->string('unique_code')->unique()->nullable();
            $table->enum('status', ['incomplete', 'pending', 'active', 'inactive', 'blacklist'])->default('incomplete');
            $table->enum('availability', ['online', 'offline', 'on_leave'])->default('offline');
            $table->text('admin_comments')->nullable();
            $table->string('admin_signature')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technicians');
    }
};
