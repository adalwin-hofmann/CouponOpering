<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEntitiesAddCompanyInfo extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('entities', function($table)
        {
            $table->integer('company_id');
            $table->string('company_name');
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
			$table->dropColumn('company_id');
			$table->dropColumn('company_name');
		});
	}

}
