<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEntitesRecIndex extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('entities', function($table)
        {
            $table->index(array('state', 'category_id', 'is_active', 'franchise_active', 'expires_day', 'starts_day', 'latm', 'lngm'), 'rec_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entities', function($table)
        {
            $table->dropIndex('rec_index');
        });
    }

}
