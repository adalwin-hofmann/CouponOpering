<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vehicle_assets', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('type');
            $table->string('path');
            $table->string('name');
            $table->string('description');
            $table->string('short_description');
            $table->integer('edmunds_style_id');
            $table->integer('style_id');
            $table->string('shot_type');
        });

        Schema::create('vehicle_makes', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->integer('edmunds_id');
            $table->boolean('is_active')->default(false);
        });

        Schema::create('vehicle_models', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('name');
            $table->integer('edmunds_id');
            $table->string('make_name');
            $table->integer('make_id');
            $table->string('edmunds_link');
            $table->string('slug');
            $table->string('make_slug');
            $table->text('about');
        });

        Schema::create('vehicle_styles', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('name');
            $table->integer('year');
            $table->string('model_name');
            $table->integer('model_id');
            $table->string('make_name');
            $table->integer('make_id');
            $table->integer('model_year_id');
            $table->float('price');
            $table->string('primary_body_type');
            $table->string('edmunds_link');
            $table->integer('edmunds_id');
            $table->integer('body_type_id');
            $table->integer('style_type_id');
            $table->float('city_epa');
            $table->float('highway_epa');
            $table->float('combined_epa');
            $table->string('engine_name');
            $table->string('transmission');
        });

        Schema::create('vehicle_years', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->integer('year');
            $table->string('model_name');
            $table->integer('model_id');
            $table->string('make_name');
            $table->integer('make_id');
            $table->integer('edmunds_default_style');
            $table->string('edmunds_link');
            $table->integer('edmunds_id');
            $table->string('model_slug');
            $table->string('make_slug');
            $table->integer('default_style_id');
        });

        Schema::create('vehicle_incentives', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('rebate_amount');
            $table->integer('edmunds_id');
            $table->string('type');
            $table->string('content_type');
            $table->string('incentive_type');
            $table->dateTime('starts_at');
            $table->dateTime('expires_at');
            $table->text('descripton');
            $table->text('restrictions');
            $table->string('edmunds_style_id');
            $table->integer('style_id');
            $table->string('style_name');
            $table->integer('model_year');
            $table->string('model_name');
            $table->integer('model_id');
            $table->string('make_name');
            $table->integer('make_id');
        });

        Schema::create('vehicle_command_history', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->boolean('is_finished')->default(false);
            $table->string('command_name');
            $table->string('last_query');
            $table->string('last_query_id');
        });

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vehicle_assets');
		Schema::drop('vehicle_makes');
		Schema::drop('vehicle_models');
		Schema::drop('vehicle_styles');
		Schema::drop('vehicle_years');
		Schema::drop('vehicle_incentives');
		Schema::drop('vehicle_command_history');
	}

}
