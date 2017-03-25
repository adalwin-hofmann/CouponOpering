<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLocationsImageSpecific extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('locations', function($table)
        {
            $table->boolean('is_logo_specific')->default(false);
            $table->boolean('is_banner_specific')->default(false);
            $table->boolean('is_about_specific')->default(false);
            $table->boolean('is_pdf_specific')->default(false);
            $table->boolean('is_video_specific')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function($table)
        {
            $table->dropColumn('is_logo_specific');
            $table->dropColumn('is_banner_specific');
            $table->dropColumn('is_about_specific');
            $table->dropColumn('is_pdf_specific');
            $table->dropColumn('is_video_specific');
        });
    }

}
