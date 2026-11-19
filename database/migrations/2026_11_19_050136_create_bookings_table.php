<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('code', 12)->unique();
            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone', 30);
            $table->date('check_in');
            $table->date('check_out');
            $table->unsignedSmallInteger('guests_count');
            $table->unsignedBigInteger('total_price');
            $table->string('status', 20)->default('pending_payment');
            $table->timestamp('expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['check_in', 'check_out']);
            $table->index('status');
        });

        Schema::create('booking_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->restrictOnDelete(); //mencegah kamar yang pernah dipesan terhapus dari database.
            $table->unsignedInteger('price_per_night');
            $table->timestamps();

            $table->unique(['booking_id', 'room_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_rooms');
        Schema::dropIfExists('bookings');
    }
};
