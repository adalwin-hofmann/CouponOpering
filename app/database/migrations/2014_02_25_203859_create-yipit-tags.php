<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYipitTags extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('yipit_tags', function($table)
		{
		    $table->timestamps();
			$table->increments('id');
		    $table->integer('category_id');
		    $table->integer('subcategory_id');
		    $table->string('name');
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
		Schema::dropIfExists('yipit_tags');
	}

}
