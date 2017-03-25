<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFranchisesNetlmsIdType extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        DB::statement("ALTER TABLE franchises CHANGE netlms_id netlms_id VARCHAR(255)");
        $franchises = \SOE\DB\Franchise::where('netlms_id', '!=', '0')->get();
        foreach($franchises as $franchise)
        {
            $franchise->netlms_id = PseudoCrypt::hash($franchise->netlms_id, 6);
            $franchise->save();
        }
        DB::table('franchises')->where('netlms_id', '=', '0')->update(array('netlms_id' => DB::raw("NULL")));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $franchises = \SOE\DB\Franchise::whereNotNull('netlms_id')->get();
        foreach($franchises as $franchise)
        {
            $franchise->netlms_id = PseudoCrypt::unhash($franchise->netlms_id);
            $franchise->save();
        }
        DB::statement("ALTER TABLE franchises CHANGE netlms_id netlms_id INT NOT NULL");
    }

}
