<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryVoucherNumberSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_voucher_number_sources', function (Blueprint $table) {
            $table->id('source_id')->autoIncrement();
            $table->string('name')->nullable(false);
            $table->string('description')->nullable();
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_voucher_number_sources_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_inventory_voucher_number_sources_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('retired')->default(0);
            $table->foreignId('retired_by')->nullable()->constrained('fk_inventory_voucher_number_sources_retired_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('retired_reason')->nullable();
            $table->dateTime('date_retired')->nullable();
            $table->char('uuid')->unique('ind_inventory_voucher_number_sources_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_voucher_number_sources');
    }
}
