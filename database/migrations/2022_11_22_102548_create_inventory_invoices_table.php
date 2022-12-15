<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_invoices', function (Blueprint $table) {
            $table->id('invoice_id')->autoIncrement();
            $table->foreignId('store')->nullable(false)->constrained('fk_inventory_invoices_store')
            ->references('store_id')->on('inventory_stores')->onDelete('cascade');
            $table->foreignId('supplier')->nullable(false)->constrained('fk_inventory_invoices_supplier')
            ->references('supplier_id')->on('inventory_suppliers')->onDelete('cascade');
            $table->integer('purchase_order')->nullable(true);
            $table->boolean('completed')->default(0);
            $table->foreignId('completed_by')->nullable(true)->constrained('fk_inventory_invoices_completed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_invoices_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable(true)->constrained('fk_inventory_invoices_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('voided')->default(0);
            $table->foreignId('voided_by')->nullable()->constrained('fk_inventory_invoices_voided_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('voided_reason')->nullable();
            $table->dateTime('voided_date')->nullable();
            $table->char('uuid')->unique('ind_inventory_invoices_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_invoices');
    }
}
