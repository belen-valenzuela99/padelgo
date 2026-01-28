<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('club_servicios', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_club');
            $table->string('nombre_servicio');

            $table->timestamps();

            // Clave foránea
            $table->foreign('id_club')
                ->references('id')
                ->on('clubs')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('club_servicios');
    }
};
