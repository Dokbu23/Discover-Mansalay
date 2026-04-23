<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVendorPaymentFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('vendor_payment_receipt_path')->nullable()->after('approved_at');
            $table->timestamp('vendor_payment_submitted_at')->nullable()->after('vendor_payment_receipt_path');
            $table->timestamp('vendor_payment_verified_at')->nullable()->after('vendor_payment_submitted_at');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'vendor_payment_receipt_path',
                'vendor_payment_submitted_at',
                'vendor_payment_verified_at',
            ]);
        });
    }
}
