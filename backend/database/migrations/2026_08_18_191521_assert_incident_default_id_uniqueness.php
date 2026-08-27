<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const INDEX_NAME = 'incidents_id_unique';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if ($this->hasUniqueIndexOnIncidentId()) {
            return;
        }

        Schema::table('incidents', function (Blueprint $table) {
            $table->unique('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! $this->hasIndexNamed(self::INDEX_NAME)) {
            return;
        }

        Schema::table('incidents', function (Blueprint $table) {
            $table->dropUnique(self::INDEX_NAME);
        });
    }

    private function hasUniqueIndexOnIncidentId(): bool
    {
        return match (DB::getDriverName()) {
            'mysql', 'mariadb' => DB::table('information_schema.statistics')
                ->where('table_schema', DB::getDatabaseName())
                ->where('table_name', 'incidents')
                ->where('column_name', 'id')
                ->where('non_unique', 0)
                ->exists(),
            'pgsql' => DB::table('pg_indexes')
                ->where('schemaname', 'public')
                ->where('tablename', 'incidents')
                ->whereRaw("indexdef ILIKE '%UNIQUE INDEX%'")
                ->whereRaw("indexdef ILIKE '%(id)%'")
                ->exists(),
            'sqlite' => collect(DB::select("PRAGMA index_list('incidents')"))
                ->where('unique', 1)
                ->contains(function (object $index): bool {
                    return collect(DB::select("PRAGMA index_info('{$index->name}')"))
                        ->contains('name', 'id');
                }),
            default => $this->hasIndexNamed(self::INDEX_NAME),
        };
    }

    private function hasIndexNamed(string $indexName): bool
    {
        return match (DB::getDriverName()) {
            'mysql', 'mariadb' => DB::table('information_schema.statistics')
                ->where('table_schema', DB::getDatabaseName())
                ->where('table_name', 'incidents')
                ->where('index_name', $indexName)
                ->exists(),
            'pgsql' => DB::table('pg_indexes')
                ->where('schemaname', 'public')
                ->where('tablename', 'incidents')
                ->where('indexname', $indexName)
                ->exists(),
            'sqlite' => collect(DB::select("PRAGMA index_list('incidents')"))
                ->contains('name', $indexName),
            default => false,
        };
    }
};
