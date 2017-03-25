<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFranchisesIsLeadsConfirmed extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('franchises', function($table)
        {
            $table->boolean('is_leads_confirmed')->default(false);
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
            $table->dropColumn('is_leads_confirmed');
        });
    }

}
