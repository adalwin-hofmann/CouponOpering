<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoQuotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('auto_quotes', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->integer('quoteable_id');
            $table->string('quoteable_type');
            $table->integer('franchise_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('zip');
            $table->integer('user_id');
            $table->dateTime('posted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auto_quotes');
    }

}
