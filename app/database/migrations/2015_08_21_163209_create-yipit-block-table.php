<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYipitBlockTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('yipit_banned_merchants', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->integer('merchant_id');
            $table->integer('yipitbusiness_id');
            $table->string('merchant_name');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('yipit_banned_merchants');
	}

}
