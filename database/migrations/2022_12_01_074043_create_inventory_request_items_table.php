<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryRequestItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_request_items', function (Blueprint $table) {
            $table->id('request_item_id')->autoIncrement();
            $table->foreignId('request')->nullable(false)->constrained('fk_inventory_request_items_request')
            ->references('request_id')->on('inventory_requests')->onDelete('cascade');
            $table->foreignId('item')->nullable(false)->constrained('fk_inventory_request_items_item')
            ->references('inv_item_id')->on('inventory_items')->onDelete('cascade');
            $table->foreignId('units')->nullable(false)->constrained('fk_inventory_request_items_units')
            ->references('unit_id')->on('item_units')->onDelete('cascade');
            $table->double('quantity')->nullable(false);
            $table->double('equivalent_quantity')->nullable(false);
            $table->boolean('completed')->default(0);
            $table->foreignId('completed_by')->nullable()->constrained('fk_inventory_request_items_completed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->boolean('rejected')->default(0);
            $table->foreignId('rejected_by')->nullable()->constrained('fk_inventory_request_items_rejected_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('rejected_reason')->nullable();
            $table->dateTime('rejected_date')->nullable();
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_request_items_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_inventory_request_items_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('voided')->default(0);
            $table->foreignId('voided_by')->nullable()->constrained('fk_inventory_request_items_voided_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('voided_reason')->nullable();
            $table->dateTime('voided_date')->nullable();
            $table->char('uuid')->unique('ind_inventory_request_items_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_request_items');
    }
}
