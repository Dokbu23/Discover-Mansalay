<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("\n            UPDATE products p\n            INNER JOIN vendors v ON v.id = p.vendor_id\n            SET p.uploaded_by_user_id = v.user_id\n            WHERE p.uploaded_by_user_id IS NULL\n        ");
    }

    public function down(): void
    {
        // No rollback needed for backfilled ownership values.
    }
};
