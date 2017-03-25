<?php

use Illuminate\Database\Migrations\Migration;

class CreateUserFavoritesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('user_favorites', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->integer('favoritable_id');
            $table->string('favoritable_type');
            $table->integer('user_id');
            $table->boolean('is_deleted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_favorites');
    }

}