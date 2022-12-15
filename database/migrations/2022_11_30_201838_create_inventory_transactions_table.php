<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id('transaction_id')->autoIncrement();
            $table->foreignId('store')->nullable(false)->constrained('fk_inventory_transactions_store')
            ->references('store_id')->on('inventory_stores')->onDelete('cascade');
            $table->foreignId('batch')->nullable(false)->constrained('fk_inventory_transactions_batch')
            ->references('bath_reference_id')->on('inventory_item_batches')->onDelete('cascade');
            $table->foreignId('source')->nullable(false)->constrained('fk_inventory_transactions_source')
            ->references('source_id')->on('inventory_transaction_sources')->onDelete('cascade');
            $table->foreignId('type')->nullable(false)->constrained('fk_inventory_transactions_type')
            ->references('type_id')->on('inventory_transaction_types')->onDelete('cascade');
            $table->double('quantity')->nullable(false);
            $table->double('quantity_before')->nullable(false);
            $table->double('quantity_after')->nullable(false);
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_transactions_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_inventory_transactions_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('voided')->default(0);
            $table->foreignId('voided_by')->nullable()->constrained('fk_inventory_transactions_voided_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('voided_reason')->nullable();
            $table->dateTime('voided_date')->nullable();
            $table->char('uuid')->unique('ind_inventory_transactions_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_transactions');
    }
}
