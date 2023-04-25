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
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            // user_id
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // hotel_id
            $table->foreignId('hotel_id')->constrained('hotel')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('room_count');
            $table->double('total_amount');
            $table->string('status');
            $table->string('description')->nullable();
            $table->boolean('is_payment')->default(false);
            $table->string('payment_type')->nullable();
            $table->date('date_in');
            $table->date('date_out');
            $table->date('date_booking');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};