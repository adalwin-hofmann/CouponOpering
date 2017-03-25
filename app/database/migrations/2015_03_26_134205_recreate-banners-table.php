<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RecreateBannersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::dropIfExists('banners');
        Schema::create('banners', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('name');
            $table->integer('merchant_id');
            $table->integer('franchise_id');
            $table->text('path');
            $table->string('type');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_demo')->default(false);
            $table->text('custom_url');
            $table->string('target');
            $table->integer('service_radius');
            $table->string('keywords');
            $table->boolean('is_location_specific')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }

}
