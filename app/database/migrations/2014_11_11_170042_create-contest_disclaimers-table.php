<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestDisclaimersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('contest_disclaimers', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->integer('contest_id');
            $table->integer('contest_award_date_id');
            $table->string('name');
            $table->boolean('check_1')->default(false);
            $table->boolean('check_2')->default(false);
            $table->boolean('check_3')->default(false);
            $table->boolean('check_4')->default(false);
            $table->boolean('check_5')->default(false);
            $table->boolean('check_6')->default(false);
            $table->dateTime('verified_at')->nullable();
            $table->string('winner_name');
            $table->string('birth_date');
            $table->string('address');
            $table->string('city_state_zip');
            $table->string('daytime_phone');
            $table->string('evening_phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contest_disclaimers');
    }

}
