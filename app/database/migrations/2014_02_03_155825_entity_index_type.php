<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EntityIndexType extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('entities', function($table)
        {
            $table->index(array('entitiable_id','entitiable_type'), 'type_id');
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
            $table->dropIndex('type_id');
        });
	}

}
