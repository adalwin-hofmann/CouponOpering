<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEntitiesFranchisesProjectTags extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('entities', function($table)
        {
            $table->string('project_tags');
        });
        Schema::table('franchises', function($table)
        {
            $table->string('project_tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entities', function($table)
        {
            $table->dropColumn('project_tags');
        });
        Schema::table('franchises', function($table)
        {
            $table->dropColumn('project_tags');
        });
    }

}
