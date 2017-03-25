<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOffersIsFollowupFor extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('offers', function($table)
        {
            $table->integer('is_followup_for');
        });

        $contests = \SOE\DB\Contest::where('follow_up_id', '!=', '0')->get();
        foreach($contests as $contest)
        {
            \SOE\DB\Offer::where('id', $contest->follow_up_id)->update(array('is_followup_for' => $contest->id));
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
            $table->dropColumn('is_followup_for');            
        });
    }

}
