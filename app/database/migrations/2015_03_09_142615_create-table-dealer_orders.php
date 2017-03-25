<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDealerOrders extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('dealer_orders', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->integer('franchise_id');
            $table->integer('make_id');
            $table->string('zipcode');
            $table->integer('radius');
            $table->integer('budget');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->integer('netlms_order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dealer_orders');
    }

}
