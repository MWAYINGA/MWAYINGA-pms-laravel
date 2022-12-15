<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryVoucherNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_voucher_numbers', function (Blueprint $table) {
            $table->id('voucher_number_id')->autoIncrement();
            $table->foreignId('voucher')->nullable(false)->constrained('fk_inventory_voucher_numbers_voucher')
            ->references('voucher_id')->on('inventory_vouchers')->onDelete('cascade');
            $table->foreignId('source')->nullable(false)->constrained('fk_inventory_voucher_numbers_source')
            ->references('source_id')->on('inventory_voucher_number_sources')->onDelete('cascade');
            $table->string('value')->nullable(false);
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_voucher_numbers_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_inventory_voucher_numbers_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('voided')->default(0);
            $table->foreignId('voided_by')->nullable()->constrained('fk_inventory_voucher_numbers_voided_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('voided_reason')->nullable();
            $table->dateTime('voided_date')->nullable();
            $table->char('uuid')->unique('ind_inventory_voucher_numbers_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_voucher_numbers');
    }
}
