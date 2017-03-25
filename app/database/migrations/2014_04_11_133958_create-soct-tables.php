<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoctTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('vehicle_makes', function($table)
        {
            $table->integer('old_id');
        });

        Schema::table('vehicle_incentives', function($table)
        {
        	$table->string('subPrice');
        	$table->integer('year');
        	$table->integer('model_name');
        	$table->integer('model_id');
        	$table->string('path');
        	$table->integer('old_dealer_id');
            $table->integer('merchant_id');
        });

        Schema::table('franchises', function($table)
        {
        	$table->integer('new_inventory_provider_number');
        	$table->integer('used_inventory_provider_number');
        	$table->integer('lead_email');
        });

        Schema::table('merchants', function($table)
        {
        	$table->integer('tnl_id');
        });

        Schema::table('offers', function($table)
        {
        	$table->string('old_dealer_id');
        });

        Schema::create('dealer_brands', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->integer('merchant_id');
            $table->integer('make_id');
            $table->integer('old_dealer_id');
            $table->integer('old_make_id');
        });

        Schema::create('used_vehicles', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->integer('tnl_id');
            $table->string('dealer_name');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('phone');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('stock_number');
            $table->string('vin');
            $table->string('year');
            $table->integer('model_year_id')->nullable();
            $table->string('make');
            $table->integer('make_id')->nullable();
            $table->string('model');
            $table->integer('model_id')->nullable();
            $table->integer('mileage');
            $table->boolean('is_certified')->default(false);
            $table->string('class');
            $table->string('body_style');
            $table->string('fuel');
            $table->string('engine');
            $table->string('cylinders');
            $table->string('transmission');
            $table->string('drive_type');
            $table->string('trim_level');
            $table->string('exterior_color');
            $table->string('interior_color');
            $table->text('dealer_specified_features');
            $table->text('standard_interior_features');
            $table->text('standard_exterior_features');
            $table->text('standard_safety_features');
            $table->text('dealer_comments');
            $table->float('msrp');
            $table->float('internet_price');
            $table->integer('image_count');
            $table->text('image_urls');
        });


	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('vehicle_makes', function($table)
        {
            $table->dropColumn('old_id');
        });

        Schema::table('vehicle_incentives', function($table)
        {
        	$table->dropColumn('subPrice');
        	$table->dropColumn('year');
        	$table->dropColumn('model_name');
        	$table->dropColumn('model_id');
        	$table->dropColumn('path');
        	$table->dropColumn('old_dealer_id');
            $table->dropColumn('merchant_id');
        });

        Schema::table('franchises', function($table)
        {
        	$table->dropColumn('new_inventory_provider_number');
        	$table->dropColumn('used_inventory_provider_number');
        	$table->dropColumn('lead_email');
        });

        Schema::table('merchants', function($table)
        {
        	$table->dropColumn('tnl_id');
        });

        Schema::table('offers', function($table)
        {
        	$table->dropColumn('old_dealer_id');
        });

        Schema::dropIfExists('dealer_brands');

        Schema::dropIfExists('used_vehicles');
	}

}
