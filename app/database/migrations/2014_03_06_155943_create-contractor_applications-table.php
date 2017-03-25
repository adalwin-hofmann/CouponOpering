<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractorApplicationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('contractor_applications', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('business_name');
            $table->string('primary_contact');
            $table->string('contact_email');
            $table->string('contact_phone');
            $table->string('lead_email');
            $table->string('address1');
            $table->string('address2');
            $table->string('city');
            $table->string('state');
            $table->string('zipcode');
            $table->string('country');
            $table->string('account_password');
            $table->string('license_number');
            $table->string('bond_number');
            $table->string('insurance_company');
            $table->string('policy_number');
            $table->string('agent');
            $table->boolean('has_outside_labor');
            $table->boolean('is_outside_insured');
            $table->boolean('is_bbb_accredited');
            $table->boolean('does_background_checks');
            $table->text('background_explaination');
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
        Schema::dropIfExists('contractor_applications');
    }

}
