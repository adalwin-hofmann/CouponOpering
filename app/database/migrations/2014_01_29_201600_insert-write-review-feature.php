<?php

use Illuminate\Database\Migrations\Migration;

class InsertWriteReviewFeature extends Migration {

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
            'name' => 'write_review_button',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'write_review_button')->delete();
	}

}