<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventorySaleOrderByQuoteLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_sale_order_by_quote_lines', function (Blueprint $table) {
            $table->id('soql_no')->autoIncrement();
            $table->foreignId('sale_order_quote')->nullable(false)->constrained('fk_inventory_sale_order_by_quote_lines_sale_order_quote')
            ->references('soq_no')->on('inventory_sale_order_by_quotes')->onDelete('cascade');
            $table->foreignId('quote_line')->nullable(false)->constrained('fk_inventory_sale_order_by_quote_lines_quote_line')
            ->references('quote_line_id')->on('inventory_sale_quote_lines')->onDelete('cascade');   
            $table->double('paid_amount')->default(0);
            $table->double('debt_amount')->nullable(false);
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
        Schema::dropIfExists('inventory_sale_order_by_quote_lines');
    }
}
