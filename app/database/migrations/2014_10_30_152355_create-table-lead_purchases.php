<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLeadPurchases extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('lead_purchases', function($table)
        {
            $table->increments('id');
            $table->integer('franchise_id');
            $table->string('netlms_id');
            $table->integer('netlms_lead_id');
            $table->dateTime('purchased_at');
            $table->string('category');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->float('price', 8, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_purchases');
    }

}
