<?php

use Illuminate\Database\Migrations\Migration;

class AlterEntitiesTableAddStartExpire extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('entities', function($table)
        {
            $table->dateTime('starts_at');
            $table->dateTime('expires_at');
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
            $table->dropColumn('starts_at');
            $table->dropColumn('expires_at');
        });
    }

}