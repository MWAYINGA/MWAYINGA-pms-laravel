<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use League\CommonMark\Reference\Reference;

class CreateItemUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_units', function (Blueprint $table) {
            $table->id('unit_id')->autoIncrement();
            $table->string('name')->nullable(false);
            $table->string('description')->nullable(true);
            $table->foreignId('created_by')->nullable(false)->constrained('fk_item_units_created_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_created')->useCurrent()->nullable(false);
            $table->foreignId('changed_by')->nullable()->constrained('fk_item_units_changed_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('date_changed')->nullable(true);
            $table->boolean('voided')->default(0);
            $table->foreignId('voided_by')->nullable()->constrained('fk_item_units_voided_by')
            ->references('id')->on('users')->onDelete('cascade');
            $table->string('voided_reason')->nullable();
            $table->dateTime('voided_date')->nullable();
            $table->uuid('uuid')->unique(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_units');
    }
}
