<?php

use Illuminate\Database\Migrations\Migration;

class AlterContestsRemoveExtraFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('contests', function($table)
        {
            $table->dropColumn('views');
            $table->dropColumn('keyword_id');
            $table->dropColumn('company_id');
            $table->dropColumn('location_id');
        });    
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contests', function($table)
        {
            $table->integer('views');
            $table->integer('keyword_id');
            $table->integer('company_id');
            $table->integer('location_id');
        });
    }

}