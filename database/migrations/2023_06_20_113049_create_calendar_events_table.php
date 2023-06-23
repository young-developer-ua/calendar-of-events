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
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->string('color');
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->enum('recurrence_type', ['none', 'daily', 'weekly', 'monthly', 'yearly'])->nullable()->default(null);
            $table->text('recurrence_value')->nullable();
            $table->boolean('is_reminder')->default(false);
            $table->boolean('is_done')->default(false);
            $table->timestamps();
        });

        Schema::table('calendar_events', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calendar_events', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('calendar_events');
    }
};
