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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('flight_number');
            $table->enum('flight_type', ['arrival', 'departure']);
            $table->foreignId('origin_id')->nullable()->constrained('airports');
            $table->foreignId('destination_id')->nullable()->constrained('airports');
            $table->dateTime('scheduled_time')->nullable();
            $table->string('gate')->nullable();
            $table->string('status')->default('Check-in Open');
            $table->dateTime('delayed_until')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
