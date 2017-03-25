<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOffersFranchiseId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        set_time_limit(30*60);
        ini_set('memory_limit', '1024M');
        Schema::table('offers', function($table)
        {
            $table->integer('franchise_id');
        });

        $franchises = DB::table('franchises')->get();
        foreach($franchises as $franchise)
        {
            DB::table('offers')->where('merchant_id', '=', $franchise->merchant_id)->update(array('franchise_id' => $franchise->id));
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function($table)
        {
            $table->dropColumn('franchise_id');
        });
    }

}
