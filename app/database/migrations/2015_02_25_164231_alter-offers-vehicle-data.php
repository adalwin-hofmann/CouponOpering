<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOffersVehicleData extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('offers', function($table)
        {
            $table->integer('year');
            $table->string('make');
            $table->integer('make_id');
            $table->string('model');
            $table->integer('model_id');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function($table)
        {
            $table->dropColumn('year');
            $table->dropColumn('make');
            $table->dropColumn('make_id');
            $table->dropColumn('model');
            $table->dropColumn('model_id');
        });
    }

}
