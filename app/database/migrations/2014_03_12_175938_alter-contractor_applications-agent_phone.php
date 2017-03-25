<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContractorApplicationsAgentPhone extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('contractor_applications', function($table)
        {
            $table->string('agent_phone', 20);
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
            $table->dropColumn('agent_phone');
        });
    }

}
