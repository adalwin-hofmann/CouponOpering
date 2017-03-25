<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContractorApplicationsLeadPhone extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('contractor_applications', function($table)
        {
            $table->string('lead_phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contractor_applications', function($table)
        {
            $table->dropColumn('lead_phone');
        });
    }

}
