<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryDispensingOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_dispensing_orders', function (Blueprint $table) {
            $table->id('dispensing_order_id')->autoIncrement();
            $table->foreignId('pos_order')->nullable(false)->constrained('fk_inventoinventory_dispensing_orders_pos_order')
            ->references('order_id')->on('inventory_pos_orders');
            $table->foreignId('item')->nullable(false)->constrained('fk_inventory_dispensing_orders_item')
            ->references('inv_item_id')->on('inventory_items')->onDelete('cascade');
            $table->foreignId('units')->nullable(false)->constrained('fk_inventory_dispensing_orders_units')
            ->references('unit_id')->on('item_units')->onDelete('cascade');
            $table->double('quantity')->nullable(false);
            $table->double('equivalent_quantity')->nullable(false);
            $table->foreignId('quantifying_store')->nullable(false)->constrained('fk_inventory_dispensing_orders_quantifying_store')
            ->references('store_id')->on('inventory_stores')->onDelete('cascade');
            $table->foreignId('dispensing_store')->nullable()->constrained('fk_inventory_dispensing_orders_dispensing_store')
            ->references('store_id')->on('inventory_stores')->onDelete('cascade');
            $table->boolean('dipensed')->default(0);
            $table->foreignId('dipensed_by')->nullable()->constrained('fk_inventory_dispensing_orders_dipensed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_dispensed')->nullable(true);
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_dispensing_orders_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_inventory_dispensing_orders_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('voided')->default(0);
            $table->foreignId('voided_by')->nullable()->constrained('fk_inventory_dispensing_orders_voided_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('voided_reason')->nullable();
            $table->dateTime('date_voided')->nullable();
            $table->char('uuid')->unique('ind_inventory_dispensing_orders_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_dispensing_orders');
    }
}
