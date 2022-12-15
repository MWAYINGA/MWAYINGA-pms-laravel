<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventorySaleQuoteReferenceMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_sale_quote_reference_maps', function (Blueprint $table) {
            $table->id('entry_no')->autoIncrement();
            $table->foreignId('quote_line')->nullable(false)->constrained('fk_inventory_sale_order_by_quote_lines_quote_line')
            ->references('quote_line_id')->on('inventory_sale_quote_lines')->onDelete('cascade');   
            $table->string('reference_value')->nullable(false);
            $table->string('reference_type')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_sale_quote_reference_maps');
    }
}
