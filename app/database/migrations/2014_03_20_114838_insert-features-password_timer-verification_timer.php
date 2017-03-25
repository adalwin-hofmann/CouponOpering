<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertFeaturesPasswordTimerVerificationTimer extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('features', function($table)
        {
            $table->string('description');
        });

        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'password_recovery_timer',
            'value' => 60,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'The number of minutes before a password recovery email link expires.'
        ));
        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'email_verification_timer',
            'value' => 60*24,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'The number of minutes before an email verification link expires.'
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'password_recovery_timer')->delete();
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'email_verification_timer')->delete();
        Schema::table('features', function($table)
        {
            $table->dropColumn('description');
        });
    }

}
