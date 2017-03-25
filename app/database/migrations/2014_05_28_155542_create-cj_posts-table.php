<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCjPostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('cj_posts', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('file_location');
            $table->boolean('is_locked');
            $table->string('status')->default('parse');
            $table->dateTime('parsed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cj_posts');
    }

}
