<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMerchantsTnlIdType extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        DB::statement("ALTER TABLE merchants CHANGE tnl_id tnl_id VARCHAR(255)");
        DB::table('merchants')->where('tnl_id', '=', '0')->update(array('tnl_id' => DB::raw('NULL')));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE merchants CHANGE tnl_id tnl_id INT NOT NULL");
    }

}
