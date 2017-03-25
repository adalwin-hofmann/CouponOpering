<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEntitiesSohiTrial extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(30*60);
        Schema::table('entities', function($table)
        {
            $table->boolean('is_sohi_trial');
            $table->dateTime('sohi_trial_starts_at')->nullable();
            $table->dateTime('sohi_trial_ends_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entities', function($table)
        {
            $table->dropColumn('is_sohi_trial');
            $table->dropColumn('sohi_trial_starts_at');
            $table->dropColumn('sohi_trial_ends_at');
        });
    }

}
