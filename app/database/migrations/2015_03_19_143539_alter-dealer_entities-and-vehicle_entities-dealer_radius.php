<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDealerEntitiesAndVehicleEntitiesDealerRadius extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::connection('mysql-used')->table('dealer_entities', function($table)
        {
            $table->integer('dealer_radius');
            $table->float('web_payout_price');
            $table->string('phone_extension');
            $table->float('phone_payout_price');
            $table->text('ppc_url');
            $table->float('ppc_payout');
        });

        Schema::connection('mysql-used')->table('vehicle_entities', function($table)
        {
            $table->integer('dealer_radius');
            $table->float('web_payout_price');
            $table->string('phone_extension');
            $table->float('phone_payout_price');
            $table->text('ppc_url');
            $table->float('ppc_payout');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql-used')->table('dealer_entities', function($table)
        {
            $table->dropColumn('dealer_radius');
            $table->dropColumn('web_payout_price');
            $table->dropColumn('phone_extension');
            $table->dropColumn('phone_payout_price');
            $table->dropColumn('ppc_url');
            $table->dropColumn('ppc_payout');
        });

        Schema::connection('mysql-used')->table('vehicle_entities', function($table)
        {
            $table->dropColumn('dealer_radius');
            $table->dropColumn('web_payout_price');
            $table->dropColumn('phone_extension');
            $table->dropColumn('phone_payout_price');
            $table->dropColumn('ppc_url');
            $table->dropColumn('ppc_payout');
        });
    }

}
