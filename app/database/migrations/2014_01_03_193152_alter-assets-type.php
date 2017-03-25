<?php

use Illuminate\Database\Migrations\Migration;

class AlterAssetsType extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('assets', function($table)
        {
            $table->string('type');
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assets', function($table)
        {
            $table->dropColumn('type');
        });
    }

}