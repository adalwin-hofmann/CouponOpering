<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArtworkTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('artwork', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('name');
            $table->string('client_contact');
            $table->string('phone');
            $table->string('advertiser');
            $table->string('city');
            $table->string('month');
            $table->string('sales_rep');
            $table->string('email');
            $table->string('filename');
            $table->text('s3_link');
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('artwork');
    }

}
