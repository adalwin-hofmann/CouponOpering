<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertisementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('advertisements', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->integer('franchise_id');
            $table->integer('merchant_id');
            $table->integer('location_id');
            $table->string('adable_type');
            $table->string('adable_id');
            $table->string('ad_type');
            $table->string('ad_level');
            $table->string('ad_section');
            $table->string('path');
            $table->integer('category_id');
            $table->integer('subcategory_id');
            $table->string('title');
            $table->string('content');
            $table->string('url_override');
            $table->double('latm');
            $table->double('lngm');
            $table->boolean('is_active');
            $table->dateTime('starts_at');
            $table->dateTime('expires_at');
            $table->boolean('is_demo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advertisements');
    }

}
