<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertFeatureEventValues extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'view_event_value',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'The event value of a location view in dollars.'
        ));

        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'print_event_value',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'The event value of a offer print in dollars.'
        ));

        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'signup_event_value',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'The event value of a user signup in dollars.'
        ));

        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'contest_event_value',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'The event value of a contest application in dollars.'
        ));

        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'lead_event_value',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'The event value of a lead submission in dollars.'
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'view_event_value')->delete();
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'print_event_value')->delete();
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'signup_event_value')->delete();
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'contest_event_value')->delete();
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'lead_event_value')->delete();
    }

}
