<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryStoreAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_store_attributes', function (Blueprint $table) {
            $table->id('attribute_id')->autoIncrement();
            $table->foreignId('store')->nullable(false)->constrained('fk_inventory_store_attributes_store')
            ->references('store_id')->on('inventory_stores')->onDelete('cascade');
            $table->foreignId('type')->nullable(false)->constrained('fk_inventory_store_attributes_type')
            ->references('type_id')->on('inventory_store_attribute_types')->onDelete('cascade');
            $table->string('value')->nullable(false);
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_store_attributes_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_inventory_store_attributes_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('voided')->default(0);
            $table->foreignId('voided_by')->nullable()->constrained('fk_inventory_store_attributes_voided_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('voided_reason')->nullable();
            $table->dateTime('voided_date')->nullable();
            $table->char('uuid')->unique('ind_inventory_store_attributes_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_store_attributes');
    }
}
