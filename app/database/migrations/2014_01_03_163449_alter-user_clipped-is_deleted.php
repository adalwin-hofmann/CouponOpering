<?php

use Illuminate\Database\Migrations\Migration;

class AlterUserClippedIsDeleted extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('user_clipped', function($table)
        {
            $table->boolean('is_deleted')->default(false);
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_clipped', function($table)
        {
            $table->dropColumn('is_deleted');
        });
    }

}