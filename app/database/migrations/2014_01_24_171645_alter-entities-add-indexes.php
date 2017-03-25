<?php

use Illuminate\Database\Migrations\Migration;

class AlterEntitiesAddIndexes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('locations', function($table)
        {
            $table->index('state', 'state');
        });

        DB::statement("UPDATE entities INNER JOIN locations ON entities.location_id = locations.id SET entities.state = locations.state;");

        DB::statement("UPDATE entities SET expires_year = YEAR(expires_at), starts_year = YEAR(starts_at), expires_day = DAYOFYEAR(expires_at), starts_day = DAYOFYEAR(starts_at);");

        Schema::table('entities', function($table)
        {
            $table->index(array('state', 'category_id', 'starts_day', 'expires_day', 'starts_year', 'expires_year'), 'state_category_starts_expires');
            $table->index(array('state', 'starts_day', 'expires_day', 'starts_year', 'expires_year'), 'state_starts_expires');
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
            $table->dropIndex('state_starts_expires');
            $table->dropIndex('state_category_starts_expires');
        });

        Schema::table('locations', function($table)
        {
            $table->dropIndex('state');
        });
	}

}