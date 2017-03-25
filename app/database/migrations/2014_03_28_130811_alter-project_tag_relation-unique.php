<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProjectTagRelationUnique extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('project_tag_relation', function($table)
        {
            $table->unique(array('parent_id', 'child_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_tag_relation', function($table)
        {
            $table->dropUnique('project_tag_relation_parent_id_child_id_unique');
        });
    }

}
