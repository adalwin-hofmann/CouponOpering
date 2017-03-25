<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('training_pages', function($table)
        {
        	$table->timestamps();
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->text('content');
            $table->string('type');
            $table->integer('section_id');
            $table->integer('order');
            $table->string('url');
        });

        Schema::create('training_sections', function($table)
        {
        	$table->timestamps();
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('roles');
            $table->integer('order');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('training_pages');
		Schema::dropIfExists('training_sections');
	}

}
