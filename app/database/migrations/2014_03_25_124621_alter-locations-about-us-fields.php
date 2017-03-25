<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLocationsAboutUsFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('locations', function($table)
        {
            $table->text('about');
            $table->string('page_title');
            $table->string('keywords');
            $table->string('meta_description');
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
            $table->dropColumn('about');
            $table->dropColumn('page_title');
            $table->dropColumn('keywords');
            $table->dropColumn('meta_description');
        });
    }

}
