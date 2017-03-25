<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('quotes', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->integer('franchise_id');
            $table->integer('project_tag_id');
            $table->string('project_tag_name');
            $table->string('timeframe');
            $table->text('description');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone', 20);
            $table->string('address1');
            $table->string('address2');
            $table->string('city', 50);
            $table->string('state', 50);
            $table->string('zipcode', 20);
            $table->string('country', 50);
            $table->integer('offer_id');
            $table->dateTime('submitted_at')->nullable();
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
        Schema::dropIfExists('quotes');
    }

}
