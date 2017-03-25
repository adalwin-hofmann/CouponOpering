<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdImpressionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('ad_impressions', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->integer('advertisement_id');
            $table->integer('user_id');
            $table->integer('nonmember_id');
            $table->string('city');
            $table->string('state');
            $table->double('latitude');
            $table->double('longitude');
            $table->double('latm');
            $table->double('lngm');
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
        Schema::dropIfExists('ad_impressions');
    }

}
