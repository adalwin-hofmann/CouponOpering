<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVehicleStylesBodyType extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('vehicle_styles', function($table)
        {
            $table->string('body_type', 20);
        });

        DB::table('vehicle_styles')->where('primary_body_type', 'LIKE', '%van%')->update(array('body_type' => 'van'));
        DB::table('vehicle_styles')->where('primary_body_type', 'LIKE', '%cab%')->update(array('body_type' => 'truck'));
        DB::table('vehicle_styles')->where('primary_body_type', 'LIKE', '%suv%')->update(array('body_type' => 'suv'));
        DB::table('vehicle_styles')->where('body_type', '=', '')->update(array('body_type' => 'car'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_styles', function($table)
        {
            $table->dropColumn('body_type');
        });
    }

}
