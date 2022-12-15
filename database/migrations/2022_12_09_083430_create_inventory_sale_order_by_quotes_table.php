<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventorySaleOrderByQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_sale_order_by_quotes', function (Blueprint $table) {
            $table->id('soq_no')->autoIncrement();
            $table->string('dated_sale_id')->nullable(false);
            $table->foreignId('sale_quote')->nullable(false)->constrained('fk_inventory_sale_order_by_quotes_sale_quote')
            ->references('quote_id')->on('inventory_sale_quotes')->onDelete('cascade');
            $table->string('payment_methods')->default('NOT SET');
            $table->double('payable_amount')->nullable(false);
            $table->double('paid_amount')->default(0);
            $table->double('debt_amount')->nullable(false);
            $table->foreignId('created_by')->nullable()->constrained('fk_inventory_sale_order_by_quotes_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->char('uuid')->unique('ind_inventory_sale_order_by_quotes_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_sale_order_by_quotes');
    }
}
