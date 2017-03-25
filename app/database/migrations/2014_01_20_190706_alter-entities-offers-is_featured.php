<?php

use Illuminate\Database\Migrations\Migration;

class AlterEntitiesOffersIsFeatured extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('entities', function($table)
        {
            $table->boolean('is_featured');
        });

        Schema::table('offers', function($table)
        {
            $table->boolean('is_featured');
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entities', function($table)
        {
            $table->dropColumn('is_featured');
        });

        Schema::table('offers', function($table)
        {
            $table->dropColumn('is_featured');
        });
    }

}