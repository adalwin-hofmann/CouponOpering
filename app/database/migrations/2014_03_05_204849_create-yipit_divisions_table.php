<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYipitDivisionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('yipit_divisions', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('name');
            $table->string('url');
            $table->string('country');
            $table->float('lon');
            $table->float('lat');
            $table->text('about');
            $table->boolean('is_active')->default(true);
            $table->integer('time_zone_diff');
            $table->string('slug');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('yipit_divisions');
	}

}
