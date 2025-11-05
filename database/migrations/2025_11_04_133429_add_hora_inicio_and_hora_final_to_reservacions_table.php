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
        Schema::table('reservacions', function (Blueprint $table) {
            // Agregamos las nuevas columnas después de reservacion_date
            $table->time('hora_inicio')->nullable()->after('reservacion_date');
            $table->time('hora_final')->nullable()->after('hora_inicio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservacions', function (Blueprint $table) {
            // Eliminamos las columnas si se hace rollback
            $table->dropColumn(['hora_inicio', 'hora_final']);
        });
    }
};
