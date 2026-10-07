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

        if ($this->isUrlColumnExists()) {
            return;
        }

        Schema::table('snapshots', function (Blueprint $table) {
            $table->string('url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        if (! $this->isUrlColumnExists()) {
            return;
        }

        Schema::table('snapshots', function (Blueprint $table) {
            $table->dropColumn('url');
        });
    }

    private function isUrlColumnExists(): bool
    {
        foreach (Schema::getColumns('snapshots') as $column) {
            if (($column['name'] ?? null) === 'url') {
                return true;
            }
        }

        return false;
    }
};
