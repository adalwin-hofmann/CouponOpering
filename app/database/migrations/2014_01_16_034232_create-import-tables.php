<?php

use Illuminate\Database\Migrations\Migration;

class CreateImportTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('offers_import', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->integer('location_id');
            $table->integer('merchant_id');
            $table->integer('yipitdeal_id');
            $table->text('path');
            $table->text('path_small');
            $table->boolean('is_dailydeal');
            $table->float('special_price');
            $table->float('regular_price');
            $table->string('code');
            $table->text('description');
            $table->dateTime('starts_at');
            $table->dateTime('expires_at');
            $table->float('rating');
            $table->float('rating_count');
            $table->integer('max_redeems')->default(1);
            $table->integer('max_prints')->default(3);
            $table->string('url');
            $table->text('print_override');
            $table->boolean('is_demo');
            $table->boolean('is_active')->default(true);
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->float('savings');
            $table->boolean('merchant_wide')->default(true);
        });

        Schema::create('contests_import', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('name');
            $table->string('type',32);
            $table->string('path');
            $table->dateTime('starts_at');
            $table->dateTime('expires_at');
            $table->boolean('require_user_id');
            $table->string('banner');
            $table->string('logo');
            $table->string('landing');
            $table->string('contest_logo');
            $table->text('about_us');
            $table->text('contest_description');
            $table->string('slug');
            $table->string('customerio_non_member_attr');
            $table->string('customerio_member_attr');
            $table->string('logo_link');
            $table->string('display_name');
            $table->text('wufoo_link');
            $table->text('contest_rules');
            $table->integer('merchant_id');
            $table->string('markets');
            $table->string('fields');
            $table->text('tracking_code');
            $table->boolean('is_demo');
            $table->boolean('is_active')->default(true);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
    {
        Schema::drop('offers_import');
        Schema::drop('contests_import');
    }

}