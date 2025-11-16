<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            // Agregar el campo id_user
            $table->unsignedBigInteger('id_user')->nullable()->after('id');

            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            // Agregar el campo descripcion
            $table->text('descripcion')->nullable()->after('id_user');
        });
    }

    public function down(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
            $table->dropColumn('id_user');
            $table->dropColumn('descripcion');
        });
    }
};
