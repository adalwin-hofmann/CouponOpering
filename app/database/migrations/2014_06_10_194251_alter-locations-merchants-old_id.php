<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLocationsMerchantsOldId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('locations', function($table)
        {
            $table->string('old_id')->default(0);
        });

        DB::statement("ALTER TABLE merchants CHANGE old_id old_id VARCHAR(255) NOT NULL DEFAULT '0'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function($table)
        {
            $table->dropColumn('old_id');
        });

        DB::statement("ALTER TABLE merchants CHANGE old_id old_id INT NOT NULL");
    }

}
