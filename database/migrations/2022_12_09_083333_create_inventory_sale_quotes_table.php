<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventorySaleQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_sale_quotes', function (Blueprint $table) {
            $table->id('quote_id')->autoIncrement();
            $table->unsignedInteger('customer')->nullable(true);
            $table->double('total_quote')->nullable(false);
            $table->double('payable_amount')->nullable(false);
            $table->foreignId('status')->nullable(false)->constrained('fk_inventory_sale_quotes_status')
            ->references('status_id')->on('inventory_sale_statuses')->onDelete('cascade');
            $table->boolean('discounted')->default(0);
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_sale_quotes_created_by')
            ->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('inventory_sale_quotes');
    }
}
