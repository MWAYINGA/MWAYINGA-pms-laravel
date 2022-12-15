<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentCategoryToInventorySaleOrderByQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_sale_order_by_quotes', function (Blueprint $table) {
            //
            $table->foreignId('payment_category')->nullable(false)->after('sale_quote')
            ->constrained('fk_inventory_sale_order_by_quotes_payment_category')
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
        Schema::table('inventory_sale_order_by_quotes', function (Blueprint $table) {
            //
            $table->dropForeign(['payment_category']);
            $table->dropColumn('payment_category');
        });
    }
}
