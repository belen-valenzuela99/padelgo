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
        Schema::table('canchas', function (Blueprint $table) {
            // Eliminar la columna 'ubicacion'

            // Agrega la columna id_club (puede ser null al principio)
            $table->unsignedBigInteger('id_club')->nullable()->after('nombre');

            // Define la relación con la tabla clubs
            $table->foreign('id_club')->references('id')->on('clubs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('canchas', function (Blueprint $table) {
         // Elimina la clave foránea y la columna al revertir
            $table->dropForeign(['id_club']);
            $table->dropColumn('id_club');
        });
    }
};