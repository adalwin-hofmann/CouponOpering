<?php

use Illuminate\Database\Migrations\Migration;

class AlterUserPrintsType extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('user_prints', function($table)
        {
            $table->string('type', 200)->default('print');
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_prints', function($table)
        {
            $table->dropColumn('type');
        });
    }

}