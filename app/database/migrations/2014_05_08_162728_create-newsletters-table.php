<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewslettersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('newsletters', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('batch_id');
            $table->string('email');
            $table->string('type');
            $table->text('data_objects');
            $table->boolean('is_locked')->default(false);
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
        Schema::dropIfExists('newsletters');
    }

}
