<?php

use Illuminate\Database\Migrations\Migration;

class AlterEntitesPopularity extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('entities', function($table)
        {
            $table->integer('popularity');
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entities', function($table)
        {
            $table->dropColumn('popularity');
        });
    }

}