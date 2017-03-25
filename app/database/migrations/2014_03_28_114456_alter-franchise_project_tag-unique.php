<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFranchiseProjectTagUnique extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('franchise_project_tag', function($table)
        {
            $table->unique(array('franchise_id', 'project_tag_id'));
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('franchise_project_tag', function($table)
        {
            $table->dropUnique('franchise_project_tag_franchise_id_project_tag_id_unique');
        });
	}

}
