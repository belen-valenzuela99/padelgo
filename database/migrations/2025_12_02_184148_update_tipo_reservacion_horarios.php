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
        Schema::table('tipo_reservacion', function (Blueprint $table) {
            // Eliminar columna antigua
            if (Schema::hasColumn('tipo_reservacion', 'franja_horaria')) {
                $table->dropColumn('franja_horaria');
            }

            // Agregar nuevas columnas
            $table->time('hora_inicio')->after('id');
            $table->time('hora_fin')->after('hora_inicio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipo_reservacion', function (Blueprint $table) {
            // Eliminar columnas nuevas
            $table->dropColumn(['hora_inicio', 'hora_fin']);

            // Restaurar columna original
            $table->string('franja_horaria')->nullable();
        });
    }
};