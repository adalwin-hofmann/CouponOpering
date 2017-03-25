<?php

use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('entities', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->integer('entitiable_id');
		    $table->string('entitiable_type');
		    $table->string('name');
		    $table->string('slug');
		    $table->integer('location_id');
		    $table->integer('category_id');
		    $table->integer('subcategory_id');
		    $table->float('latitude');
		    $table->float('longitude');
		    $table->text('path');
		    $table->boolean('is_dailydeal');
		    $table->float('rating');
		    $table->float('special_price');
		    $table->float('regular_price');
		    $table->boolean('is_demo');
		    $table->boolean('is_active')->default(true);
		});

		Schema::create('locations', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->string('name');
		    $table->string('slug');
		    $table->boolean('is_demo');
		    $table->boolean('is_active')->default(true);
		    $table->string('address')->nullable();
		    $table->string('address2')->nullable();
		    $table->string('city',100);
            $table->string('state',2);
            $table->string('zip',10);
		    $table->float('latitude');
		    $table->float('longitude');
		    $table->string('hours');
		    $table->string('phone');
		    $table->string('website');
		    $table->float('rating');
		    $table->float('rating_count');
		    $table->integer('merchant_id');
		    $table->integer('division_id');
		    $table->integer('franchise_id');
		    $table->boolean('is_national');
		    $table->integer('created_by');
		    $table->integer('updated_by');
		});

		Schema::create('merchants', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->string('name');
		    $table->string('display');
		    $table->string('slug');
            $table->string('type',20);
		    $table->text('about');
		    $table->string('catchphrase');
		    $table->string('facebook');
		    $table->string('twitter');
		    $table->string('website');
		    $table->string('hours');
		    $table->string('phone');
		    $table->integer('category_id');
		    $table->integer('subcategory_id');
		    $table->integer('default_location_id');
		    $table->integer('yipitbusiness_id');
		    $table->string('primary_contact')->nullable();
		    $table->string('coupon_tab_type')->default('Coupons');
		    $table->boolean('is_demo');
		    $table->boolean('is_active')->default(true);
		    $table->integer('created_by');
		    $table->integer('updated_by');
		});

		Schema::create('offers', function($table)
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
		});

		Schema::create('divisions', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->string('name');
		    $table->integer('company_id')->default(1);
		});

		Schema::create('franchines', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->string('name');
		    $table->integer('company_id')->default(1);
		});

		Schema::create('companies', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->string('name');
		    $table->string('slug');
		    $table->string('landing_image');
		    $table->string('logo_image');
		    $table->text('description')->nullable();
		    $table->string('slogan');
		    $table->boolean('own_market');
		    $table->boolean('has_corporate');
		    $table->boolean('has_custom_colors');
		    $table->integer('radius');
		    $table->float('latitude');
		    $table->float('longitude');
		    $table->string('address')->nullable();
		    $table->string('address2')->nullable();
		    $table->string('city',100)->nullable();
            $table->string('state',2)->nullable();
            $table->string('zip',10)->nullable();
		    $table->string('phone')->nullable();
		    $table->boolean('is_demo')->default(true);
		    $table->boolean('is_active')->default(true);
		});

		Schema::create('categories', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->string('name');
		    $table->string('slug');
		    $table->string('tags');
		    $table->integer('parent_id');
		    $table->text('above_heading')->nullable();
		    $table->text('footer_heading')->nullable();
		    $table->string('title');
		    $table->string('description');
		});

		Schema::create('assets', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->integer('assetable_id');
		    $table->string('assetable_type');
		    $table->text('path');
		    $table->string('name');
		    $table->text('long_description');
		    $table->text('short_description');
		    $table->integer('category_id');
		    $table->integer('subcategory_id');
		    $table->integer('sub_subcategory_id');
		});

		Schema::create('users', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
            $table->string('email',200)->unique();
		    $table->string('password',200);
		    $table->string('username',64);
		    $table->string('type', 20)->default('Member');     // employee or customer or member?
            $table->string('name');
		    $table->string('address');
		    $table->string('address2');
            $table->string('city',100);
            $table->string('state',2);
            $table->string('homezip',10);
            $table->string('workzip',10);       
            $table->float('latitude');
            $table->float('longitude');
		    $table->integer('age');
            $table->dateTime('birthday')->nullable();
            $table->string('sex',1);
		    $table->string('facebookid');
		    $table->string('accesskey');
		    $table->string('secretkey');
		    $table->string('ip',20);
		    $table->string('signup_source');
		    $table->integer('reputation')->default(20);
            $table->dateTime('badrep_date')->nullable();
		    $table->boolean('is_suspended');
		    $table->string('win5kid');
		});

		Schema::create('nonmenber', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		});

		Schema::create('company_users', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->integer('company_id')->default(1);
		});

		Schema::create('franchise_users', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->integer('franchise_id');
		});

		Schema::create('customerio_users', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->string('custio_created_at');
		    $table->boolean('unsubscriped');
            $table->dateTime('unsubscriped_at');
            $table->string('email',200)->unique();
		    $table->integer('user_id');
		    $table->string('zip',10);       
            $table->float('latitude');
            $table->float('longitude');
		    $table->integer('version');
		    $table->integer('franchise_id');
		});

		Schema::create('user_views', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->integer('merchant_id');
		    $table->integer('user_id');
		    $table->text('user_agent');
		    $table->integer('nonmember_id');
		    $table->integer('location_id');
		    $table->integer('franchise_id');
		});

		Schema::create('user_searches', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->string('search_text');
		    $table->string('city_zip');
		    $table->integer('user_id');
		    $table->integer('nonmember_id');
		    $table->boolean('is_anonymous');
		});

		Schema::create('user_prints', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->integer('offer_id');
		    $table->integer('user_id');
		    $table->integer('nonmember_id');
		    $table->string('code');
		});

		Schema::create('user_redeems', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->integer('offer_id');
		    $table->integer('user_id');
		    $table->integer('nonmember_id');
		    $table->string('code');
		    $table->string('city',100);
            $table->string('state',2);
            $table->string('zip',10);  
            $table->float('latitude');
            $table->float('longitude');
		});

		Schema::create('user_clipped', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->integer('offer_id');
		    $table->integer('user_id');
		    $table->integer('nonmember_id');
		    $table->string('code');
		});

		Schema::create('user_impressions', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->integer('offer_id');
		    $table->integer('user_id');
		    $table->integer('nonmember_id');
		});

		Schema::create('user_locations', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->integer('user_id');
		    $table->string('city',100);
            $table->string('state',2);
            $table->string('zip',10);  
            $table->float('latitude');
            $table->float('longitude');
            $table->string('ip'); 
		});

		DB::statement('ALTER TABLE user_locations ADD my_point POINT');  // Geometrical point (lat, lng);

		Schema::create('reviews', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->integer('reviewable_id');
		    $table->string('reviewable_type');
		    $table->integer('user_id');
		    $table->string('content');
		    $table->boolean('is_deleted');
		    $table->float('rating');
		});

		Schema::create('review_votes', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->integer('user_id');
		    $table->integer('review_id');
		    $table->string('vote');
		});

		Schema::create('contest_applications', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->integer('contest_id');
		    $table->integer('user_id');
		    $table->integer('nonmember_id');
		    $table->string('name');
		    $table->string('city',100);
            $table->string('state',2);
            $table->string('zip',10);  
            $table->string('phone');
		});

		Schema::create('contests', function($table)
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
		    $table->integer('views');
		    $table->string('logo_link');
		    $table->string('display_name');
		    $table->text('wufoo_link');
		    $table->text('contest_rules');
		    $table->integer('merchant_id');
		    $table->string('markets');
		    $table->integer('keyword_id');
		    $table->string('fields');
		    $table->text('tracking_code');
		    $table->integer('company_id')->default(1);
		    $table->integer('location_id');
		    $table->boolean('is_demo');
		    $table->boolean('is_active')->default(true);
		});

		Schema::create('role_users', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->integer('user_id');
		    $table->integer('role_id');
		});

		Schema::create('roles', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->string('name');
		});

		Schema::create('role_rules', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->integer('role_id');
		    $table->integer('rule_id');
		});

		Schema::create('rules', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->string('group');
		    $table->string('action');
		    $table->string('description');
		});

		Schema::create('gallery_categories', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->string('name');
		    $table->integer('parent_id');
		});

		Schema::create('gallery_asset_tags', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->integer('gallery_asset_id');
		    $table->integer('gallery_tag_id');
		});

		Schema::create('gallery_tags', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->string('name');
		});

		Schema::create('shares', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->integer('shareable_id');
		    $table->string('shareable_type');
		    $table->integer('user_id');
		    $table->string('type');
		});

		Schema::create('share_emails', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->integer('shareable_id');
		    $table->string('shareable_type');
		    $table->integer('share_id');
		    $table->string('share_email');
		});

		Schema::create('banner_clicks', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->integer('banner_id');
		    $table->integer('user_id');
		    $table->integer('category_id');
		    $table->integer('subcategory_id');
            $table->string('state',2);
            $table->float('latitude');
            $table->float('longitude');
            $table->string('type');
		    $table->integer('nonmember_id');
		});

		Schema::create('banners', function($table)
		{
		    $table->timestamps();
		    $table->increments('id');
		    $table->integer('company_id')->default(1);
		    $table->integer('location_id');
            $table->dateTime('post_date');
            $table->dateTime('expire_date');
		    $table->text('path');
		    $table->string('name');
            $table->integer('impressions');
            $table->string('type',25);
            $table->boolean('is_location_specific');
            $table->string('banner_link');
            $table->boolean('is_deleted');
            $table->boolean('is_paying_category');
            $table->boolean('is_paying_subcategory');
            $table->string('asset_type');
		});

		Schema::create('features', function($table) 
        {
            $table->increments('id');
            $table->string('type', 50);
            $table->string('entity', 50);
            $table->string('name', 75);
            $table->string('value', 200);
            $table->timestamps();
        });

        Schema::create('zipcodes', function($table) 
        {
            $table->increments('id');
            $table->string('recordnumber', 64);
            $table->string('zipcode', 20);
            $table->string('zipcodetype', 20);
            $table->string('city', 100);
            $table->string('state', 20);
            $table->string('locationtype', 20);
            $table->float('latitude');
            $table->float('longitude');
            $table->string('taxreturns2008', 20);
            $table->integer('estimatedpopulation');
            $table->integer('totalwages');
            $table->timestamps();
        });

        Schema::create('city_images', function($table) 
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('name');
            $table->string('zip', 10);
            $table->string('state', 2);
            $table->float('latitude');
            $table->float('longitude');
            $table->text('path');
            $table->text('img_415x258');
            $table->text('img_266x266');
            $table->text('about');
        });

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('entities');
		Schema::drop('locations');
		Schema::drop('merchants');
		Schema::drop('offers');
		Schema::drop('divisions');
		Schema::drop('franchines');
		Schema::drop('companies');
		Schema::drop('categories');
		Schema::drop('assets');
		Schema::drop('users');
		Schema::drop('nonmenber');
		Schema::drop('company_users');
		Schema::drop('franchise_users');
		Schema::drop('customerio_users');
		Schema::drop('user_views');
		Schema::drop('user_searches');
		Schema::drop('user_prints');
		Schema::drop('user_redeems');
		Schema::drop('user_clipped');
		Schema::drop('user_impressions');
		Schema::drop('user_locations');
		Schema::drop('reviews');
		Schema::drop('review_votes');
		Schema::drop('contest_applications');
		Schema::drop('contests');
		Schema::drop('role_users');
		Schema::drop('roles');
		Schema::drop('role_rules');
		Schema::drop('rules');
		Schema::drop('gallery_categories');
		Schema::drop('gallery_asset_tags');
		Schema::drop('gallery_tags');
		Schema::drop('shares');
		Schema::drop('share_emails');
		Schema::drop('banner_clicks');
		Schema::drop('banners');
		Schema::drop('features');
		Schema::drop('zipcodes');
		Schema::drop('city_images');
	}
}