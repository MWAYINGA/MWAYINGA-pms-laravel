<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventorySaleStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_sale_statuses', function (Blueprint $table) {
            $table->id('status_id')->autoIncrement();
            $table->string('name')->nullable(false);
            $table->string('quote_type')->nullable(false);
            $table->foreignId('created_by')->nullable(false)->constrained('fk_inventory_sale_statuses_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_inventory_sale_statuses_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('voided')->default(0);
            $table->foreignId('voided_by')->nullable()->constrained('fk_inventory_sale_statuses_voided_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('voided_reason')->nullable(true);
            $table->dateTime('date_voided')->nullable(true);
            $table->char('uuid')->unique('ind_inventory_sale_statuses_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_sale_statuses');
    }
}
