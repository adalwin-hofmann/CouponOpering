<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContestsAwardOptions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('contests', function($table)
        {
            $table->boolean('is_automated');
        });

        Schema::table('contest_winners', function($table)
        {
            $table->integer('award_date_id');
            $table->string('verify_key')->nullable();
            $table->dateTime('redeemed_at')->nullable();
        });

        Schema::create('contest_award_dates', function($table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->integer('contest_id');
            $table->dateTime('award_at')->nullable();
            $table->dateTime('all_awarded_at')->nullable();
            $table->dateTime('verify_by')->nullable();
            $table->integer('verify_attempts');
            $table->integer('winners');
            $table->boolean('has_prize')->default(false);
            $table->string('prize_company');
            $table->text('prize_description');
            $table->dateTime('prize_expiration_date');
            $table->string('prize_authorizer');
            $table->string('prize_authorizer_title');
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
            $table->dropColumn('is_automated');
        });

        Schema::table('contests', function($table)
        {
            $table->dropColumn('award_date_id');
            $table->dropColumn('verify_key');
        });

        Schema::dropIfExists('contest_award_dates');
    }

}
