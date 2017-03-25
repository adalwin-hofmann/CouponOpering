<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserSearchesLocationFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        DB::statement("ALTER TABLE user_searches CHANGE city_zip city VARCHAR(255)");
        Schema::table('user_searches', function($table)
        {
            $table->integer('results');
            $table->string('state', 2);
            $table->float('latitude');
            $table->float('longitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE user_searches CHANGE city city_zip VARCHAR(255)");
        Schema::table('user_searches', function($table)
        {
            $table->dropColumn('results');
            $table->dropColumn('state');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }

}
