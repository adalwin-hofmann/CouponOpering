<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertFullSoctDetroitOnlyFeature extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'soct',
            'name' => 'full_soct_detroit_only',
            'value' => '120',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'Should used cars and service deals be Detroit only? Radius in miles or 0.'
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'soct')->where('name', '=', 'full_soct_detroit_only')->delete();
    }

}
