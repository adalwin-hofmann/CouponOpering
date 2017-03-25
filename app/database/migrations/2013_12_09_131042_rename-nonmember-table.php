<?php

use Illuminate\Database\Migrations\Migration;

class RenameNonmemberTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        DB::statement('RENAME TABLE `nonmenber` TO `nonmembers`');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('RENAME TABLE `nonmembers` TO `nonmenber`');
    }

}