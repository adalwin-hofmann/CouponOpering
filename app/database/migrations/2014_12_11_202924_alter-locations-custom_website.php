<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLocationsCustomWebsite extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('locations', function($table)
        {
            $table->string('custom_website');
            $table->string('custom_website_text');
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
            $table->dropColumn('custom_website');
            $table->dropColumn('custom_website_text');
        });
    }

}
