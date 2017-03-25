<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRecoveryAssociation extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('recovery', function($table)
        {
            $table->integer('association_id');
            $table->string('association_type');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recovery', function($table)
        {
            $table->dropColumn('association_id');
            $table->dropColumn('association_type');
        });
    }

}
