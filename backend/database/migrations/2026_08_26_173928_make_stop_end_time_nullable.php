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
        if ($this->isEndTimeNullable()) {
            return;
        }

        Schema::table('stops', function (Blueprint $table) {
            $table->string('end_time')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stops', function (Blueprint $table) {
            $table->string('end_time')->nullable(false)->change();
        });
    }

    private function isEndTimeNullable(): bool
    {
        foreach (Schema::getColumns('stops') as $column) {
            if (($column['name'] ?? null) !== 'end_time') {
                continue;
            }

            return (bool) ($column['nullable'] ?? false);
        }

        throw new RuntimeException('The stops.end_time column does not exist.');
    }
};
