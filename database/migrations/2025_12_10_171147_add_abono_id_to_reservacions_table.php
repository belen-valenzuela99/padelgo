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
    Schema::table('reservacions', function (Blueprint $table) {
        $table->unsignedBigInteger('abono_id')->nullable()->after('id_tipo_reservacion');

        $table->foreign('abono_id')
            ->references('id')
            ->on('abonos')
            ->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('reservacions', function (Blueprint $table) {
        $table->dropForeign(['abono_id']);
        $table->dropColumn('abono_id');
    });
}

};
