<?php

use Illuminate\Database\Migrations\Migration;

class CreateSyncTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('sync', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('import_type');
            $table->boolean('is_imported');
            $table->boolean('is_running');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sync');
    }

}