<?php

use Illuminate\Database\Migrations\Migration;

class AlterZipcodesCartesian extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('zipcodes', function($table)
        {
            $table->string('latm');
            $table->string('lngm');
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zipcodes', function($table)
        {
            $table->dropColumn('latm');
            $table->dropColumn('lngm');
        });
    }

}