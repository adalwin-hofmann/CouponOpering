<?php

use Illuminate\Database\Migrations\Migration;

class CreateAssetCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('asset_categories', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('name');
            $table->integer('parent_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('asset_categories');
	}

}