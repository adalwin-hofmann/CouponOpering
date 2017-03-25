<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealerSpecialtiesEntitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('dealer_entities', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('vendor');
            $table->integer('vendor_dealer_id');
            $table->string('vendor_dealer_key');
            $table->string('vendor_inventory_id');
            $table->string('condition');
            $table->string('stock_number');
            $table->string('vin');
            $table->integer('year');
            $table->integer('model_year_id');
            $table->integer('make_id');
            $table->string('make', 50);
            $table->integer('model_id');
            $table->string('model', 50);
            $table->boolean('is_certified');
            $table->string('price');
            $table->integer('mileage');
            $table->string('body_type', 50);
            $table->string('vehicle_type');
            $table->string('class');
            $table->string('fuel');
            $table->string('engine');
            $table->integer('cylinders');
            $table->string('transmission', 50);
            $table->string('drive_type', 50);
            $table->string('trim_level');
            $table->string('exterior_color');
            $table->string('interior_color');
            $table->float('city_mpg');
            $table->float('highway_mpg');
            $table->text('options');
            $table->text('dealer_comments');
            $table->float('msrp');
            $table->float('internet_price');
            $table->integer('image_count');
            $table->mediumText('image_urls');
            $table->string('display_image');
            $table->string('dealer_name');
            $table->string('dealer_slug');
            $table->integer('merchant_id');
            $table->integer('location_id');
            $table->string('address');
            $table->string('city', 75);
            $table->string('state',25);
            $table->string('zipcode', 15);
            $table->string('phone',25);
            $table->decimal('latitude', 8,6);
            $table->decimal('longitude', 8,6);
            $table->double('latm');
            $table->double('lngm');
            $table->boolean('is_prequalified');
            $table->string('source');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dealer_entities');
    }

}
