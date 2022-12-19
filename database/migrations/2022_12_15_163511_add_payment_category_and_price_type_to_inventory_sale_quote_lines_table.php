<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentCategoryAndPriceTypeToInventorySaleQuoteLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_sale_quote_lines', function (Blueprint $table) {
            //
            $table->foreignId('price_type')->nullable(false)->after('units')
            ->constrained('fk_inventory_sale_quote_lines_price_type')
            ->references('price_type_id')->on('item_price_types')->onDelete('cascade');
            $table->foreignId('payment_category')->nullable(false)->after('units')
            ->constrained('fk_inventory_sale_quote_lines_payment_category')
            ->references('category_id')->on('inventory_payment_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_sale_quote_lines', function (Blueprint $table) {
            //
            $table->dropForeign(['payment_category']);
            $table->dropColumn('payment_category');
        });
    }
}
