<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_invoice_items', function (Blueprint $table) {
            $table->id('invoice_item_id')->autoIncrement();
            $table->foreignId('invoice')->nullable(false)->constrained('fk_inventory_invoice_items_invoice')
            ->references('invoice_id')->on('inventory_invoices')->onDelete('cascade');
            $table->foreignId('batch')->nullable(false)->constrained('fk_inventory_invoice_items_batch')
            ->references('bath_reference_id')->on('inventory_item_batches')->onDelete('cascade');
            $table->foreignId('units')->nullable(false)->constrained('fk_inventory_invoice_items_units')
            ->references('unit_id')->on('item_units')->onDelete('cascade');
            $table->double('quantity')->nullable(false);
            $table->double('batch_quantity')->nullable(false);
            $table->double('unit_price')->nullable(false);
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_invoice_items_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_inventory_invoice_items_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('voided')->default(0);
            $table->foreignId('voided_by')->nullable()->constrained('fk_inventory_invoice_items_voided_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('voided_reason')->nullable();
            $table->dateTime('voided_date')->nullable();
            $table->char('uuid')->unique('ind_inventory_invoice_items_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_invoice_items');
    }
}
