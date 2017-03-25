<?php

use Illuminate\Database\Migrations\Migration;

class AlterFranchisesAdditionalFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('franchises', function($table)
        {
            $table->integer('merchant_id');
            $table->integer('maghub_id');
            $table->boolean('is_active')->default(true);
            $table->integer('max_prints');
            $table->boolean('mobile_redemption')->default(true);
            $table->string('primary_contact')->nullable();
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('franchises', function($table)
        {
            $table->dropColumn('merchant_id');
            $table->dropColumn('maghub_id');
            $table->dropColumn('is_active');
            $table->dropColumn('max_prints');
            $table->dropColumn('mobile_redemption');
            $table->dropColumn('primary_contact');
        });
    }

}