<?php

use Illuminate\Database\Migrations\Migration;

class AlterReviewVotesIsDeletedNonmemberId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('review_votes', function($table)
        {
            $table->integer('nonmember_id');
            $table->boolean('is_deleted');
        });

        DB::statement("ALTER TABLE review_votes CHANGE `vote` `vote` INT NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('review_votes', function($table)
        {
            $table->dropColumn('nonmember_id');
            $table->dropColumn('is_deleted');
        });

        DB::statement("ALTER TABLE review_votes CHANGE `vote` `vote` VARCHAR(255) NOT NULL");
    }

}