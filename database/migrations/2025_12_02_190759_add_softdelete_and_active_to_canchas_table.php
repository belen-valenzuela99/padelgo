<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('canchas', function (Blueprint $table) {
            // Publicada / No publicada
            $table->boolean('is_active')
                  ->default(true)
                  ->after('duracion_maxima');

            // Soft delete
            $table->softDeletes(); // crea la columna deleted_at
        });
    }

    public function down(): void
    {
        Schema::table('canchas', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->dropSoftDeletes(); // elimina deleted_at
        });
    }
};
