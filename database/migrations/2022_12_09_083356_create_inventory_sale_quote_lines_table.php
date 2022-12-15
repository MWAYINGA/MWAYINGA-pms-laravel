<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventorySaleQuoteLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_sale_quote_lines', function (Blueprint $table) {
            $table->id('quote_line_id')->autoIncrement();
            $table->foreignId('quote')->nullable(false)->constrained('fk_inventory_sale_quotes_item')
            ->references('quote_id')->on('inventory_sale_quotes')->onDelete('cascade');
            $table->foreignId('item')->nullable(false)->constrained('fk_inventory_sale_quotes_item')
            ->references('inv_item_id')->on('inventory_items')->onDelete('cascade');
            $table->double('quantity')->nullable(false);
            $table->string('units')->nullable(false);
            $table->double('quoted_amount')->nullable(false);
            $table->double('payable_amount')->nullable(false);
            $table->foreignId('status')->nullable(false)->constrained('fk_inventory_sale_quotes_status')
            ->references('status_id')->on('inventory_sale_statuses')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_inventory_sale_quotes_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->char('uuid')->unique('ind_inventory_sale_quotes_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_sale_quote_lines');
    }
}
