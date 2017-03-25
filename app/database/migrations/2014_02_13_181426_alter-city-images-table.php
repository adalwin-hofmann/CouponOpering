<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCityImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE city_images CHANGE city display VARCHAR(255)");
		Schema::table('city_images', function($table)
        {
            $table->string('region_type');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("ALTER TABLE city_images CHANGE display city VARCHAR(255)");
		Schema::table('city_images', function($table)
        {
            $table->dropColumn('region_type');
        });
	}

}
