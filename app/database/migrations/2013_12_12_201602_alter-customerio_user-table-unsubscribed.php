<?php

use Illuminate\Database\Migrations\Migration;

class AlterCustomerioUserTableUnsubscribed extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        DB::statement('ALTER TABLE customerio_users CHANGE unsubscriped unsubscribed TINYINT(1)');
        DB::statement('ALTER TABLE customerio_users CHANGE unsubscriped_at unsubscribed_at DATETIME NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE customerio_users CHANGE unsubscribed unsubscriped TINYINT(1)');
        DB::statement('ALTER TABLE customerio_users CHANGE unsubscribed_at unsubscriped_at DATETIME NOT NULL');
    }

}