<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rb_penjualan', function (Blueprint $table) {
            $table->string('moota_transaction_id', 191)->nullable()->after('kode_transaksi');
            $table->string('moota_bank_account_id', 100)->nullable()->after('moota_transaction_id');
            $table->string('moota_status', 50)->nullable()->after('moota_bank_account_id');
            $table->dateTime('moota_paid_at')->nullable()->after('moota_status');
            $table->longText('moota_payload')->nullable()->after('moota_paid_at');

            $table->index('moota_transaction_id', 'idx_moota_transaction_id');
            $table->index('moota_status', 'idx_moota_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rb_penjualan', function (Blueprint $table) {
            // Drop indexes first
            if (Schema::hasColumn('rb_penjualan', 'moota_transaction_id')) {
                $table->dropIndex('idx_moota_transaction_id');
            }
            if (Schema::hasColumn('rb_penjualan', 'moota_status')) {
                $table->dropIndex('idx_moota_status');
            }

            $table->dropColumn(['moota_transaction_id','moota_bank_account_id','moota_status','moota_paid_at','moota_payload']);
        });
    }
};
