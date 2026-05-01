<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rb_penjualan', function (Blueprint $table) {
            $table->string('moota_bank_account_name', 255)->nullable()->after('moota_bank_account_id');
        });
    }

    public function down()
    {
        Schema::table('rb_penjualan', function (Blueprint $table) {
            $table->dropColumn('moota_bank_account_name');
        });
    }
};
