<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('club_red_social', function (Blueprint $table) {
            $table->string('url_red')->after('id_red_social');
        });
    }

    public function down(): void
    {
        Schema::table('club_red_social', function (Blueprint $table) {
            $table->dropColumn('url_red');
        });
    }
};
