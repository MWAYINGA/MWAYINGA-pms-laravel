<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryStockOnHandByBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_stock_on_hand_by_batches', function (Blueprint $table) {
            $table->id('stock_on_batch_id')->utoIncrement();
            $table->foreignId('store')->nullable(false)->constrained('fk_inventory_stock_on_hand_by_batches_store')
            ->references('store_id')->on('inventory_stores')->onDelete('cascade');
            $table->foreignId('batch')->nullable(false)->constrained('fk_inventory_stock_on_hand_by_batches_batch')
            ->references('bath_reference_id')->on('inventory_item_batches')->onDelete('cascade');
            $table->double('quantity')->nullable(false);
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_stock_on_hand_by_batches_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_inventory_stock_on_hand_by_batches_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('voided')->default(0);
            $table->foreignId('voided_by')->nullable()->constrained('fk_inventory_stock_on_hand_by_batches_voided_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('voided_reason')->nullable();
            $table->dateTime('voided_date')->nullable();
            $table->char('uuid')->unique('ind_inventory_stock_on_hand_by_batches_uuid'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_stock_on_hand_by_batches');
    }
}
