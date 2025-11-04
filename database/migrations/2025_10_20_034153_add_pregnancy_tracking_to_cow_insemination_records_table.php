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
        Schema::table('cow_insemination_records', function (Blueprint $table) {
            $table->enum('pregnancy_status', ['pending', 'conceived', 'pregnant', 'miscarried', 'delivered'])->default('pending')->after('notes');
            $table->text('conception_notes')->nullable()->after('pregnancy_status');
            $table->text('pregnancy_notes')->nullable()->after('conception_notes');
            $table->text('delivery_notes')->nullable()->after('pregnancy_notes');
            $table->string('calf_image')->nullable()->after('delivery_notes');
            $table->timestamp('conception_date')->nullable()->after('calf_image');
            $table->timestamp('pregnancy_date')->nullable()->after('conception_date');
            $table->timestamp('delivery_date')->nullable()->after('pregnancy_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cow_insemination_records', function (Blueprint $table) {
            $table->dropColumn([
                'pregnancy_status',
                'conception_notes',
                'pregnancy_notes',
                'delivery_notes',
                'calf_image',
                'conception_date',
                'pregnancy_date',
                'delivery_date'
            ]);
        });
    }
};
