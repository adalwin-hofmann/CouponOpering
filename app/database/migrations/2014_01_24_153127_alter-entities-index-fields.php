<?php

use Illuminate\Database\Migrations\Migration;

class AlterEntitiesIndexFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        DB::statement('ALTER TABLE entities ADD COLUMN state VARBINARY(2) NOT NULL;');
        Schema::table('entities', function($table)
        {
            $table->integer('expires_year');
            $table->integer('expires_day');
            $table->integer('starts_year');
            $table->integer('starts_day');
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
            $table->dropColumn('state');
            $table->dropColumn('expires_year');
            $table->dropColumn('expires_day');
            $table->dropColumn('starts_year');
            $table->dropColumn('starts_day');
        });
    }

}