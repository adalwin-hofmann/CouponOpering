<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertFeatureMasterQuotesControl extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'master_quotes_control',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'Allow any quotes? 1 or 0.'
        ));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'master_quotes_control')->delete();
	}

}
