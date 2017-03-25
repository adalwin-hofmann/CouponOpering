<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealerApplicationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dealer_applications', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('business_name');
            $table->string('primary_contact');
            $table->string('contact_email');
            $table->string('contact_phone');
            $table->string('lead_email');
            $table->string('new_inventory_number');
            $table->string('used_inventory_number');
            $table->string('address1');
            $table->string('address2');
            $table->string('city');
            $table->string('state');
            $table->string('zipcode');
            $table->string('country');
            $table->string('account_password');
            $table->text('hours');
            $table->string('website');
            $table->text('description');
            $table->text('additional_info');
            $table->dateTime('approved_at');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('dealer_applications');
	}

}
