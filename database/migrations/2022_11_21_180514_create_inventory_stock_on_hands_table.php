<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryStockOnHandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_stock_on_hands', function (Blueprint $table) {
            $table->id('stock_on_hand_id')->utoIncrement();
            $table->foreignId('store')->nullable(false)->constrained('fk_inventory_stock_on_hands_store')
            ->references('store_id')->on('inventory_stores')->onDelete('cascade');
            $table->foreignId('item')->nullable(false)->constrained('fk_inventinventory_stock_on_hands_item')
            ->references('inv_item_id')->on('inventory_items')->onDelete('cascade');
            $table->double('quantity')->nullable(false);
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_stock_on_hands_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_inventory_stock_on_hands_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('voided')->default(0);
            $table->foreignId('voided_by')->nullable()->constrained('fk_inventory_stock_on_hands_voided_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('voided_reason')->nullable();
            $table->dateTime('voided_date')->nullable();
            $table->char('uuid')->unique('ind_inventory_stock_on_hands_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_stock_on_hands');
    }
}
