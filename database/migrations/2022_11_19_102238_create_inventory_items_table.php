<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id('inv_item_id')->autoIncrement();
            $table->string('sku')->nullable(true);
            $table->foreignId('category')->nullable(false)->constrained('fk_inventory_items_category')
            ->references('category_id')->on('item_categories')->onDelete('cascade');
            $table->foreignId('group')->nullable(false)->constrained('fk_inventory_items_group')
            ->references('group_id')->on('item_groups')->onDelete('cascade');
            $table->foreignId('units')->nullable(false)->constrained('fk_inventory_items_units')
            ->references('unit_id')->on('item_units')->onDelete('cascade');
            $table->string('name')->nullable(false);
            $table->string('strength')->nullable(true);
            $table->string('description')->nullable(true);
            $table->boolean('prescription')->default(false)->nullable(false);
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_items_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_inventory_items_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('voided')->default(0);
            $table->foreignId('voided_by')->nullable()->constrained('fk_inventory_items_voided_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('voided_reason')->nullable();
            $table->dateTime('voided_date')->nullable();
            $table->uuid('uuid')->unique(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_items');
    }
}
