<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventorySupplierAttributeTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_supplier_attribute_types', function (Blueprint $table) {
            $table->id('type_id')->autoIncrement();
            $table->string('name')->nullable(false);
            $table->string('description')->nullable();
            $table->boolean('mandatory')->default(0)->nullable(false);
            $table->boolean('allow_multiselect')->default(0)->nullable(false);
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_supplier_attribute_types_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_inventory_supplier_attribute_types_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('retired')->default(0);
            $table->foreignId('retired_by')->nullable()->constrained('fk_inventory_supplier_attribute_types_retired_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('retired_reason')->nullable();
            $table->dateTime('date_retired')->nullable();
            $table->char('uuid')->unique('ind_inventory_supplier_attribute_types_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_supplier_attribute_types');
    }
}
