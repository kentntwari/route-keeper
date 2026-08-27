<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        $primaryKeyColumn = DB::table('information_schema.table_constraints as constraints')
            ->join('information_schema.key_column_usage as columns', function ($join) {
                $join->on('constraints.constraint_name', '=', 'columns.constraint_name')
                    ->on('constraints.table_schema', '=', 'columns.table_schema')
                    ->on('constraints.table_name', '=', 'columns.table_name');
            })
            ->where('constraints.table_schema', 'public')
            ->where('constraints.table_name', 'incidents')
            ->where('constraints.constraint_type', 'PRIMARY KEY')
            ->value('columns.column_name');

        if ($primaryKeyColumn === 'incident_id') {
            return;
        }

        Schema::table('incidents', function (Blueprint $table) {
            $table->dropPrimary('incidents_pkey');
        });

        Schema::table('incidents', function (Blueprint $table) {
            $table->primary('incident_id');
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        $primaryKeyColumn = DB::table('information_schema.table_constraints as constraints')
            ->join('information_schema.key_column_usage as columns', function ($join) {
                $join->on('constraints.constraint_name', '=', 'columns.constraint_name')
                    ->on('constraints.table_schema', '=', 'columns.table_schema')
                    ->on('constraints.table_name', '=', 'columns.table_name');
            })
            ->where('constraints.table_schema', 'public')
            ->where('constraints.table_name', 'incidents')
            ->where('constraints.constraint_type', 'PRIMARY KEY')
            ->value('columns.column_name');

        if ($primaryKeyColumn === 'id') {
            return;
        }

        Schema::table('incidents', function (Blueprint $table) {
            $table->dropPrimary('incidents_pkey');
        });

        Schema::table('incidents', function (Blueprint $table) {
            $table->primary('id');
        });
    }
};
