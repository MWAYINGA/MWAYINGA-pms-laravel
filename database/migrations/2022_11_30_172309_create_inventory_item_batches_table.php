<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryItemBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_item_batches', function (Blueprint $table) {
            $table->id('bath_reference_id')->utoIncrement();
            $table->foreignId('item')->nullable(false)->constrained('fk_inventory_item_batches_item')
            ->references('inv_item_id')->on('inventory_items')->onDelete('cascade');
            $table->string('batch_no')->nullable(false);
            $table->dateTime('manufacture_date')->nullable(true);
            $table->dateTime('expire_date')->nullable(false);
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_item_batches_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_inventory_item_batches_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('voided')->default(0);
            $table->foreignId('voided_by')->nullable()->constrained('fk_inventory_item_batches_voided_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('voided_reason')->nullable();
            $table->dateTime('voided_date')->nullable();
            $table->char('uuid')->unique('ind_inventory_item_batches_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_item_baths');
    }
}
