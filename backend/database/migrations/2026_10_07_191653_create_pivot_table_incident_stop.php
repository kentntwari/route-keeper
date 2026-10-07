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
        Schema::create('incident_stop', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('incident_id');
            $table->foreign('incident_id')
                ->references('incident_id')
                ->on('incidents')
                ->cascadeOnDelete();
            $table->foreignUuid('stop_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_stop');
    }
};
