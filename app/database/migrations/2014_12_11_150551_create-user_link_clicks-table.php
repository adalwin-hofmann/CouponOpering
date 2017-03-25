<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLinkClicksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('user_link_clicks', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('nonmember_id');
            $table->integer('location_id');
            $table->integer('merchant_id');
            $table->integer('franchise_id');
            $table->string('url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_link_clicks');
    }

}
