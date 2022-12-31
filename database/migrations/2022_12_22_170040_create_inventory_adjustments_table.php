<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_adjustments', function (Blueprint $table) {
            $table->id('adjustment_id')->autoIncrement();
            $table->foreignId('store')->nullable(false)->constrained('fk_inventory_adjustments_store')
            ->references('store_id')->on('inventory_stores')->onDelete('cascade');
            $table->boolean('approved')->default(0);
            $table->foreignId('approved_by')->nullable(true)->constrained('fk_inventory_adjustments_approved_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_approved')->nullable(true);
            $table->string('approced_remarks')->nullable();
            $table->boolean('rejected')->default(0);
            $table->foreignId('rejected_by')->nullable(true)->constrained('fk_inventory_adjustments_rejected_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_rejected')->nullable(true);
            $table->string('rejected_reason')->nullable();
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_adjustments_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable(true)->constrained('fk_inventory_adjustments_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('voided')->default(0);
            $table->foreignId('voided_by')->nullable()->constrained('fk_inventory_adjustments_voided_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('voided_reason')->nullable();
            $table->dateTime('voided_date')->nullable();
            $table->char('uuid')->unique('ind_inventory_adjustments_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_adjustments');
    }
}
