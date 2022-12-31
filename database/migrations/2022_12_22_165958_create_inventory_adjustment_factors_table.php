<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryAdjustmentFactorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_adjustment_factors', function (Blueprint $table) {
            $table->id('adjustment_factor_id')->autoIncrement();
            $table->string('name')->nullable(false);
            $table->string('description')->nullable();
            $table->foreignId('category')->nullable(false)->constrained('fk_inventory_adjustment_factors_category')
            ->references('category_id')->on('inventory_adjustment_factor_categories')->onDelete('cascade');
            $table->foreignId('type')->nullable(false)->constrained('fk_inventory_adjustment_factors_type')
            ->references('type_id')->on('inventory_adjustment_factor_types')->onDelete('cascade');
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_adjustment_factors_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_inventory_adjustment_factors_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('voided')->default(0);
            $table->foreignId('voided_by')->nullable()->constrained('fk_inventory_adjustment_factors_voided_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('voided_reason')->nullable();
            $table->dateTime('date_voided')->nullable();
            $table->char('uuid')->unique('ind_inventory_adjustment_factors_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_adjustment_factors');
    }
}
