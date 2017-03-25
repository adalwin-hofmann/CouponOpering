<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertFeatureContestWinnerPool extends Migration {

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
            'name' => 'contest_winner_pool',
            'value' => '5',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'The number of semi-finalists to select for each contest.'
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'contest_winner_pool')->delete();
    }

}
