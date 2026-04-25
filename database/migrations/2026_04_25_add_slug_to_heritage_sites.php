<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('heritage_sites', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('name');
        });
    }

    public function down()
    {
        Schema::table('heritage_sites', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
