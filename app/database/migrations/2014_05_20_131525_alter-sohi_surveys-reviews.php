<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSohiSurveysReviews extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('sohi_surveys', function($table)
        {
            $table->string('type')->default('survey');
            $table->string('completed_on_time', 20);
            $table->string('would_recommend', 20);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sohi_surveys', function($table)
        {
            $table->dropColumn('type');
            $table->dropColumn('completed_on_time');
            $table->dropColumn('would_recommend');
        });
    }

}
