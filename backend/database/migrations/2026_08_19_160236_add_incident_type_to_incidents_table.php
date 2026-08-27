<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            // Store the normalized value; application code enforces format
            $table->string('incident_type', 100)->unique();
        });
    }

    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropUnique(['incident_type']);
            $table->dropColumn('incident_type');
        });
    }
};
