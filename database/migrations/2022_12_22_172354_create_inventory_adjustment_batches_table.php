<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryAdjustmentBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_adjustment_batches', function (Blueprint $table) {
            $table->id('adjustment_batch_id')->autoIncrement();
            $table->foreignId('adjustment')->nullable(false)->constrained('fk_inventory_adjustment_batches_adjustment')
            ->references('adjustment_id')->on('inventory_adjustments')->onDelete('cascade');
            $table->foreignId('factor')->nullable(false)->constrained('fk_inventory_adjustment_batches_factor')
            ->references('adjustment_factor_id')->on('inventory_adjustment_factors')->onDelete('cascade');
            $table->foreignId('batch')->nullable(false)->constrained('fk_inventory_adjustment_batches_batch')
            ->references('bath_reference_id')->on('inventory_item_batches')->onDelete('cascade');
            $table->double('quantity')->nullable(false);
            $table->string('remarks')->nullable();
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_adjustment_batches_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_inventory_adjustment_batches_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('voided')->default(0);
            $table->foreignId('voided_by')->nullable()->constrained('fk_inventory_adjustment_batches_voided_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('voided_reason')->nullable();
            $table->dateTime('voided_date')->nullable();
            $table->char('uuid')->unique('ind_inventory_adjustment_batches_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_adjustment_batches');
    }
}
