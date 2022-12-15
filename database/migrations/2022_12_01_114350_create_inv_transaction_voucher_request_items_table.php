<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvTransactionVoucherRequestItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_transaction_voucher_request_items', function (Blueprint $table) {
            $table->integer('transaction')->primary();
            $table->foreignId('voucher_item')->nullable(false)->constrained('fk_transaction_voucher_request_items_voucher_item')
            ->references('voucher_item_id')->on('inventory_voucher_stock_request_items')->onDelete('cascade');
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inv_transaction_voucher_request_items_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_inv_transaction_voucher_request_items_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('voided')->default(0);
            $table->foreignId('voided_by')->nullable()->constrained('fk_inv_transaction_voucher_request_items_voided_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('voided_reason')->nullable();
            $table->dateTime('voided_date')->nullable();
            $table->char('uuid')->unique('ind__inv_transaction_voucher_request_items_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inv_transaction_voucher_request_items');
    }
}
