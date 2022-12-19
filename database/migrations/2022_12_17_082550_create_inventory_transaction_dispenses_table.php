<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTransactionDispensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_transaction_dispenses', function (Blueprint $table) {
            $table->integer('transaction')->primary();
            $table->foreignId('dispense_order')->nullable(false)->constrained('fk_inventory_transaction_dispenses_dispense_order')
            ->references('dispensing_order_id')->on('inventory_dispensing_orders')->onDelete('cascade');
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_transaction_dispenses_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_inventory_transaction_dispenses_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('voided')->default(0);
            $table->foreignId('voided_by')->nullable()->constrained('fk_inventory_transaction_dispenses_voided_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('voided_reason')->nullable();
            $table->dateTime('voided_date')->nullable();
            $table->char('uuid')->unique('ind_inventory_transaction_dispenses_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_transaction_dispenses');
    }
}
