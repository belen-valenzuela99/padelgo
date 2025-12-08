<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cancha_tipo_reservacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cancha_id')->constrained('canchas')->onDelete('cascade');
            $table->foreignId('tipo_reservacion_id')->constrained('tipo_reservacion')->onDelete('cascade');
            $table->decimal('precio', 8, 2)->nullable(); // precio personalizado por cancha
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['cancha_id', 'tipo_reservacion_id']); // evita duplicados
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cancha_tipo_reservacion');
    }
};