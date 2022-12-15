<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryOrderPayInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_order_pay_installments', function (Blueprint $table) {
            $table->id('entry_no')->autoIncrement();
            $table->unsignedBigInteger('installment_no')->nullable(false);
            $table->foreignId('soql_no')->nullable(false)->constrained('fk_inventory_order_pay_installments_soql_no')
            ->references('soql_no')->on('inventory_sale_order_by_quote_lines')->onDelete('cascade');     
            $table->double('paid_amount')->nullable(false);
            $table->string('receipt')->nullable(false);
            $table->foreignId('created_by')->nullable()->constrained('fk_inventory_order_pay_installments_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->char('uuid')->unique('ind_inventory_order_pay_installments_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_order_pay_installments');
    }
}
