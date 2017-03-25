<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFranchisesTrialFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('franchises', function($table)
        {
            $table->dateTime('trial_starts_at')->nullable();
            $table->dateTime('trial_ends_at')->nullable();
            $table->integer('trial_lead_cap');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('franchises', function($table)
        {
            $table->dropColumn('trial_starts_at');
            $table->dropColumn('trial_ends_at');
            $table->dropColumn('trial_lead_cap');            
        });
    }

}
