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
            //// Cambiar el tipo de la columna reservacion_date a date
            $table->date('reservacion_date')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservacions', function (Blueprint $table) {
            //// Volver al tipo original datetime si hicieras rollback
            $table->dateTime('reservacion_date')->change();
        });
    }
};
