<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('events', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->integer('merchant_id');
            $table->text('path');
            $table->text('description');
            $table->dateTime('event_start');
            $table->dateTime('event_end');
            $table->text('website');
            $table->dateTime('starts_at');
            $table->dateTime('expires_at');
            $table->boolean('is_demo')->default(true);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured');
            $table->integer('franchise_id');
            $table->boolean('is_location_specific');
            $table->string('short_name_line1');
            $table->string('short_name_line2');
            $table->integer('custom_category_id');
            $table->integer('custom_subcategory_id');
            $table->boolean('category_visible')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }

}
