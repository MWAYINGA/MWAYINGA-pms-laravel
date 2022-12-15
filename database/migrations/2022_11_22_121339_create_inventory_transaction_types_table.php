<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTransactionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_transaction_types', function (Blueprint $table) {
            $table->id('type_id')->autoIncrement();
            $table->string('name')->nullable(false);
            $table->string('operation')->nullable(false);
            $table->string('description')->nullable();
            $table->boolean('prefered')->default(1)->nullable(false);
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_transaction_types_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_inventory_inventory_transaction_types_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('retired')->default(0);
            $table->foreignId('retired_by')->nullable()->constrained('fk_inventory_inventory_transaction_types_retired_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('retired_reason')->nullable();
            $table->dateTime('date_retired')->nullable();
            $table->char('uuid')->unique('ind_inventory_transaction_types_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_transaction_types');
    }
}
