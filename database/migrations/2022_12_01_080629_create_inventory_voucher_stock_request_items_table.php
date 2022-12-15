<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryVoucherStockRequestItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_voucher_stock_request_items', function (Blueprint $table) {
            $table->id('voucher_item_id')->autoIncrement();
            $table->foreignId('voucher')->nullable(false)->constrained('fk_inventory_voucher_stock_request_items_voucher')
            ->references('voucher_id')->on('inventory_vouchers')->onDelete('cascade');
            $table->foreignId('request_item')->nullable(false)->constrained('fk_inventory_voucher_stock_request_items_request_item')
            ->references('request_item_id')->on('inventory_request_items')->onDelete('cascade');
            $table->foreignId('batch')->nullable(false)->constrained('fk_inventory_voucher_stock_request_items_batch')
            ->references('bath_reference_id')->on('inventory_item_batches')->onDelete('cascade');
            $table->double('quantity')->nullable(false);
            $table->boolean('accepted')->default(0);
            $table->foreignId('accepted_by')->nullable()->constrained('fk_inventory_voucher_stock_request_items_accepted_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('accepted_remarks')->nullable();
            $table->dateTime('date_accepted')->nullable();
            $table->boolean('rejected')->default(0);
            $table->foreignId('rejected_by')->nullable()->constrained('fk_inventory_voucher_stock_request_items_rejected_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('rejected_reason')->nullable();
            $table->dateTime('date_rejected')->nullable();
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_voucher_stock_request_items_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_inventory_voucher_stock_request_items_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('voided')->default(0);
            $table->foreignId('voided_by')->nullable()->constrained('fk_inventory_voucher_stock_request_items_voided_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('voided_reason')->nullable();
            $table->dateTime('voided_date')->nullable();
            $table->char('uuid')->unique('ind_inventory_voucher_stock_request_items_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_voucher_stock_request_items');
    }
}
