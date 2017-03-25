<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterReviewsReviewableTypeVarbinary extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        DB::statement("ALTER TABLE reviews CHANGE reviewable_type reviewable_type VARBINARY(255) NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE reviews CHANGE reviewable_type reviewable_type VARCHAR(255) NOT NULL");
    }

}
