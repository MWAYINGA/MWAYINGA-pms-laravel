<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryPosOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_pos_orders', function (Blueprint $table) {
            $table->id('order_id')->autoIncrement();
            $table->foreignId('source')->nullable(false)->constrained('fk_inventory_pos_orders_source')
            ->references('source_id')->on('inventory_pos_order_sources')->onDelete('cascade');
            $table->foreignId('item')->nullable(false)->constrained('fk_inventory_pos_orders_item')
            ->references('inv_item_id')->on('inventory_items')->onDelete('cascade');
            $table->foreignId('units')->nullable(false)->constrained('fk_inventory_pos_orders_units')
            ->references('unit_id')->on('item_units')->onDelete('cascade');
            $table->double('quantity')->nullable(false);
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_pos_orders_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_inventory_pos_orders_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('retired')->default(0);
            $table->foreignId('retired_by')->nullable()->constrained('fk_inventory_pos_orders_retired_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('retired_reason')->nullable();
            $table->dateTime('date_retired')->nullable();
            $table->char('uuid')->unique('ind_inventory_pos_orders_uuid');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_pos_orders');
    }
}
