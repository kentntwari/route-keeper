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
        Schema::create('stops', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('start_time');
            $table->string('end_time')->nullable();
            $table->string('total_time')->nullable();
            $table->string('estimated_time')->nullable();
            $table->integer('deliveries_count');
            $table->integer('pickups_count');
            $table->string('exception', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stops');
    }
};
