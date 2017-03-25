<?php

use Illuminate\Database\Migrations\Migration;

class CreateFranchiseAssignmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('franchise_assignments', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->integer('franchise_id');
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
        Schema::drop('franchise_assignments');
    }

}