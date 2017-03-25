<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContestsEntitiesIsNational extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('contests', function($table)
        {
            $table->boolean('is_national')->default(false);
        });
        Schema::table('entities', function($table)
        {
            $table->boolean('is_national')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contests', function($table)
        {
            $table->dropColumn('is_national');
        });
        Schema::table('entities', function($table)
        {
            $table->dropColumn('is_national');
        });
    }

}
