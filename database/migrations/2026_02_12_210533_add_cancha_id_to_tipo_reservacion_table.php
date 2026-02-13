<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
      public function up(): void
    {
        Schema::table('tipo_reservacion', function (Blueprint $table) {

            // Agregar cancha_id como foreign key
            $table->foreignId('cancha_id')
                ->after('id')
                ->constrained('canchas')
                ->onDelete('cascade');

            // Agregar estado activo
            $table->boolean('activo')
                ->default(true)
                ->after('precio');
        });
    }

    public function down(): void
    {
        Schema::table('tipo_reservacion', function (Blueprint $table) {

            // Primero eliminar foreign key
            $table->dropForeign(['cancha_id']);

            // Luego eliminar columnas
            $table->dropColumn(['cancha_id', 'activo']);
        });
    }
};


