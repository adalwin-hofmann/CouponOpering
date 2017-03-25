<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNewsletterSchedules extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('newsletter_schedules', function($table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->string('type');
            $table->integer('batch_id');
            $table->dateTime('prep_at')->nullable();
            $table->dateTime('prepped_at')->nullable();
            $table->dateTime('send_at')->nullable();
            $table->dateTime('sent_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('newsletter_schedules');
    }

}
