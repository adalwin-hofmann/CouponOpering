<?php

use Illuminate\Database\Migrations\Migration;

class AlterReviewsNonmemberId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('reviews', function($table)
        {
            $table->integer('nonmember_id');
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function($table)
        {
            $table->dropColumn('nonmember_id');
        });
    }

}