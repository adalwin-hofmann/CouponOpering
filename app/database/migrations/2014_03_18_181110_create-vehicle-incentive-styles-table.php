<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleIncentiveStylesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vehicle_incentive_styles', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->integer('vehicle_incentive_id');
            $table->integer('vehicle_style_id');
        });

        Schema::table('vehicle_incentives', function($table)
        {
            $table->boolean('is_active')->default(true);
            $table->text('description');
            $table->dropColumn('descripton');
            $table->dropColumn('edmunds_style_id');
            $table->dropColumn('style_id');
            $table->dropColumn('style_name');
            $table->dropColumn('model_year');
            $table->dropColumn('model_name');
            $table->dropColumn('model_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vehicle_incentive_styles');

		Schema::table('vehicle_incentives', function($table)
        {
            $table->dropColumn('is_active');
            $table->dropColumn('description');
            $table->text('descripton');
            $table->string('edmunds_style_id');
            $table->integer('style_id');
            $table->string('style_name');
            $table->integer('model_year');
            $table->string('model_name');
            $table->integer('model_id');
        });
	}

}
