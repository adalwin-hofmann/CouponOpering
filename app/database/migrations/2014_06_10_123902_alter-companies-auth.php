<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompaniesAuth extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('companies', function($table)
        {
            $table->string('username');
            $table->string('api_key');
        });

        $companies = \SOE\DB\Company::all();
        foreach($companies as $comp)
        {
            $comp->username = PseudoCrypt::hash($comp->id);
            $comp->api_key = bin2hex(openssl_random_pseudo_bytes(4));
            $comp->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function($table)
        {
            $table->dropColumn('username');
            $table->dropColumn('api_key');
        });
    }

}
