<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserClippedRecIndex extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('user_clipped', function($table)
        {
            $table->index(array('offer_id', 'user_id', 'is_deleted'), 'rec_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_clipped', function($table)
        {
            $table->dropIndex('rec_index');
        });
    }

}
