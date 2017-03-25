<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFranchisesAddMagazinemanagerId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('franchises', function($table)
        {
            $table->integer('magazinemanager_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('franchises', function($table)
        {
            $table->dropColumn('magazinemanager_id');
        });
	}

}
