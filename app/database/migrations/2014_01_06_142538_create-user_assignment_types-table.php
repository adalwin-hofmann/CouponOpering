<?php

use Illuminate\Database\Migrations\Migration;

class CreateUserAssignmentTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('user_assignment_types', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('assignment_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_assignment_types');
    }

}