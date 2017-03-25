<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackedCallsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('tracked_calls', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('call_sid');
            $table->string('account_sid')->nullable();
            $table->string('call_from')->nullable();
            $table->string('call_to')->nullable();
            $table->string('direction')->nullable();
            $table->string('from_city')->nullable();
            $table->string('from_state')->nullable();
            $table->string('from_zip')->nullable();
            $table->string('from_country')->nullable();
            $table->string('to_city')->nullable();
            $table->string('to_state')->nullable();
            $table->string('to_zip')->nullable();
            $table->string('to_country')->nullable();
            $table->string('dialcall_sid')->nullable();
            $table->string('dialcall_duration')->nullable();
            $table->string('dialcall_status')->nullable();
            $table->string('recording_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tracked_calls');
    }

}
