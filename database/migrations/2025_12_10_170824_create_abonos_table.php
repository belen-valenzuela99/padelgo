<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abonos', function (Blueprint $table) {

            $table->id();

            // Relación con el jugador (usuario)
            $table->unsignedBigInteger('user_id');

            // Relación con la cancha
            $table->unsignedBigInteger('cancha_id');

            // Día de la semana (lunes, martes, etc.)
            $table->string('dia_semana', 20);

            // Mes del abono (1-12)
            $table->integer('mes');

            // Horarios
            $table->time('hora_inicio');
            $table->time('hora_fin');

            // Precio del abono
            $table->decimal('precio', 10, 2);

            // Estado del abono
            $table->boolean('activo')->default(true);

            $table->timestamps();

            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cancha_id')->references('id')->on('canchas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abonos');
    }
};
