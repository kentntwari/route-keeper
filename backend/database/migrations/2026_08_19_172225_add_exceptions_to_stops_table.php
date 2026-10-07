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
        if ($this->isExceptionNullable()) {
            return;
        }

        Schema::table('stops', function (Blueprint $table) {
            $table->string('exception', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! $this->isExceptionNullable()) {
            return;
        }

        Schema::table('stops', function (Blueprint $table) {
            $table->string('exception', 100)->nullable(false)->change();
        });
    }

    private function isExceptionNullable(): bool
    {
        foreach (Schema::getColumns('stops') as $column) {
            if (($column['name'] ?? null) !== 'exception') {
                continue;
            }

            return (bool) ($column['nullable'] ?? false);
        }

        throw new RuntimeException('The stops.exception column does not exist.');
    }
};
