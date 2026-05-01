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
        Schema::create('moota_webhook_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_transaksi', 191)->nullable()->index();
            $table->string('moota_transaction_id', 191)->nullable()->index();
            $table->string('status', 100)->nullable()->index();
            $table->longText('payload')->nullable();
            $table->longText('headers')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('moota_webhook_logs');
    }
};
