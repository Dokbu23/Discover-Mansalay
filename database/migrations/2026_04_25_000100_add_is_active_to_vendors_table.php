<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('vendors', 'is_active')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('type');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('vendors', 'is_active')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }
};
