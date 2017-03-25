<?php

use Illuminate\Database\Migrations\Migration;

class AlterUsersTableZipcode extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('users', function($table)
        {
            $table->string('zipcode', 10);
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table)
        {
            $table->dropColumn('zipcode');
        });
    }

}