<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFranchisesCertifiedFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('franchises', function($table)
        {
            $table->boolean('is_certified');
            $table->dateTime('certified_at')->nullable();
            $table->dateTime('uncertified_at')->nullable();
        });
        DB::statement("ALTER TABLE franchises ADD service_plan VARBINARY(60) NOT NULL");
        Schema::table('franchises', function($table)
        {
            $table->string('zipcode', 10);
            $table->float('radius');
            $table->float('monthly_budget');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('franchises', function($table)
        {
            $table->dropColumn('is_certified');
            $table->dropColumn('certified_at');
            $table->dropColumn('uncertified_at');
            $table->dropColumn('service_plan');
            $table->dropColumn('zipcode');
            $table->dropColumn('radius');
            $table->dropColumn('monthly_budget');
        });
    }

}
