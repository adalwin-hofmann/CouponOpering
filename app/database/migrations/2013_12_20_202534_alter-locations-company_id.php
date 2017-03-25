<?php

use Illuminate\Database\Migrations\Migration;

class AlterLocationsCompanyId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('locations', function($table)
        {
            $table->integer('company_id');
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function($table)
        {
            $table->dropColumn('company_id');
        });
    }

}