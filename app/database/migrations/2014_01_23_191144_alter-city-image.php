<?php

use Illuminate\Database\Migrations\Migration;

class AlterCityImage extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('city_images', function($table)
        {
            $table->string('latm');
            $table->string('lngm');
            $table->string('city');
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
            $table->dropColumn('latm');
            $table->dropColumn('lngm');
            $table->dropColumn('city');
        });
	}

}