<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterShareEmailsShareableTypeVarbinary extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        DB::statement("ALTER TABLE share_emails CHANGE shareable_type shareable_type VARBINARY(255) NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE share_emails CHANGE shareable_type shareable_type VARCHAR(255) NOT NULL");
    }

}
