<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCjProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('cj_products', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('programname');
            $table->string('programurl');
            $table->string('lastupdated');
            $table->string('name');
            $table->string('keywords');
            $table->string('description');
            $table->string('sku');
            $table->string('manufacturer');
            $table->string('manufacturerid');
            $table->string('upc');
            $table->string('isbn');
            $table->string('currency');
            $table->float('saleprice');
            $table->float('price');
            $table->float('retailprice');
            $table->string('fromprice');
            $table->string('buyurl');
            $table->string('impressionurl');
            $table->string('imageurl');
            $table->string('advertisercategory');
            $table->string('thirdpartyid');
            $table->string('thirdpartycategory');
            $table->string('author');
            $table->string('artist');
            $table->string('title');
            $table->string('publisher');
            $table->string('label');
            $table->string('format');
            $table->string('special');
            $table->string('gift');
            $table->string('promotionaltext');
            $table->string('startdate');
            $table->string('enddate');
            $table->string('offline');
            $table->string('online');
            $table->string('instock');
            $table->string('condition');
            $table->string('warranty');
            $table->float('standardshippingcost');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cj_products');
    }

}
