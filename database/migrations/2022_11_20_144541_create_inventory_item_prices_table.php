<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryItemPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_item_prices', function (Blueprint $table) {
            $table->id('inv_item_price_id')->autoIncrement();
            $table->foreignId('item')->nullable(false)->constrained('fk_inventory_item_prices_item')
            ->references('inv_item_id')->on('inventory_items')->onDelete('cascade');
            $table->foreignId('price_type')->nullable(false)->constrained('fk_inventory_item_prices_price_type')
            ->references('price_type_id')->on('item_price_types')->onDelete('cascade');
            $table->double('price')->nullable(false);
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_item_prices_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_inventory_item_prices_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('voided')->default(0);
            $table->foreignId('voided_by')->nullable()->constrained('fk_inventory_item_prices_voided_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('voided_reason')->nullable();
            $table->dateTime('voided_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_item_prices');
    }
}
