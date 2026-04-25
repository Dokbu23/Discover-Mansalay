<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'uploaded_by_user_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->foreignId('uploaded_by_user_id')->nullable()->after('vendor_id')->constrained('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'uploaded_by_user_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropConstrainedForeignId('uploaded_by_user_id');
            });
        }
    }
};
