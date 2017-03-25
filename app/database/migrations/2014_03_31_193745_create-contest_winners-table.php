<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestWinnersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('contest_winners', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->integer('contest_id');
            $table->integer('user_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('city');
            $table->string('state');
            $table->dateTime('selected_at')->nullable();
            $table->dateTime('verify_by')->nullable();
            $table->dateTime('verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contest_winners');
    }

}
