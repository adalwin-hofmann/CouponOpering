<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('action_logs', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('object_type');
            $table->integer('object_id');
            $table->integer('user_id');
            $table->text('data_snapshot');
            $table->string('action_description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('action_logs');
    }

}
