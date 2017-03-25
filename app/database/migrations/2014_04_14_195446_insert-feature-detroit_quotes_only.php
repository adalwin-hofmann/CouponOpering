<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertFeatureDetroitQuotesOnly extends Migration {

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
            'name' => 'detroit_quotes_only',
            'value' => 120,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'Allow quotes only in Detroit? Radius in miles or 0.'
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'detroit_quotes_only')->delete();
    }

}
