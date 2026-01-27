<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('club_red_social', function (Blueprint $table) {
        $table->id();

        // FK a clubs
        $table->unsignedBigInteger('id_club');

        // FK a redes_sociales
        $table->unsignedBigInteger('id_red_social');

        $table->timestamps();

        // Claves foráneas
        $table->foreign('id_club')
              ->references('id')
              ->on('clubs')
              ->onDelete('cascade');

        $table->foreign('id_red_social')
              ->references('id')
              ->on('redes_sociales')
              ->onDelete('cascade');

        // Evita duplicados (mismo club + misma red)
        $table->unique(['id_club', 'id_red_social']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('club_red_social');
    }
};
