<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLocationsAddHiddenCustomAddress extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('locations', function($table)
        {
            $table->boolean('is_address_hidden');
            $table->string('custom_address_text');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('locations', function($table)
        {
            $table->dropColumn('is_address_hidden');
            $table->dropColumn('custom_address_text');
        });
	}

}
