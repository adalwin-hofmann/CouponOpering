<?php

use Illuminate\Database\Migrations\Migration;

class AlterOffersContestsIsFeatured extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('offers_import', function($table)
        {
            $table->integer('is_featured');
        });

        Schema::table('contests', function($table)
        {
            $table->integer('is_featured');
        });

        Schema::table('contests_import', function($table)
        {
            $table->integer('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers_import', function($table)
        {
            $table->dropColumn('is_featured');
        });

        Schema::table('contests', function($table)
        {
            $table->dropColumn('is_featured');
        });

        Schema::table('contests_import', function($table)
        {
            $table->dropColumn('is_featured');
        });
    }

}