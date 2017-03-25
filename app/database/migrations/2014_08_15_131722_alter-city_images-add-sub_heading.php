<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCityImagesAddSubHeading extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('city_images', function($table)
        {
            $table->text('sub_heading')->nullable();
            $table->text('used_cars_about')->nullable();
            $table->text('used_cars_sub_heading')->nullable();
            $table->text('new_cars_about')->nullable();
            $table->text('new_cars_sub_heading')->nullable();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('city_images', function($table)
        {
            $table->dropColumn('sub_heading');
            $table->dropColumn('used_cars_about');
            $table->dropColumn('used_cars_sub_heading');
            $table->dropColumn('new_cars_about');
            $table->dropColumn('new_cars_sub_heading');
        });
	}

}
