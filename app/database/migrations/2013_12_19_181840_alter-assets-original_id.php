<?php

use Illuminate\Database\Migrations\Migration;

class AlterAssetsOriginalId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('assets', function($table)
        {
            $table->integer('original_id');
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
            $table->dropColumn('original_id');
        });
    }

}