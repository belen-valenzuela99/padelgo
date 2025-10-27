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
        Schema::create('reservacions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cancha_id');
            $table->date('reservacion_date');
            $table->unsignedBigInteger('id_tipo_reservacion')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();


            // Define la relación con la tabla clubs
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // Define la relación con la tabla clubs
            $table->foreign('cancha_id')->references('id')->on('canchas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservacions');
    }
};
