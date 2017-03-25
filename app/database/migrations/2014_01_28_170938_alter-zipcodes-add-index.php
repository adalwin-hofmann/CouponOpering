<?php

use Illuminate\Database\Migrations\Migration;

class AlterZipcodesAddIndex extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE zipcodes MODIFY COLUMN state VARBINARY(2) NOT NULL');
		Schema::table('zipcodes', function($table)
        {
            $table->index('state', 'state');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('zipcodes', function($table)
        {
            $table->dropIndex('state');
        });
        DB::statement('ALTER TABLE zipcodes MODIFY COLUMN state VARCHAR(2) NOT NULL');
	}

}