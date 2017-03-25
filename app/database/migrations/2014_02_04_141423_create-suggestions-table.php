<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuggestionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('suggestions', function($t)
        {
            $t->timestamps();
            $t->increments('id');
            $t->string('type');
            $t->integer('category_id');
            $t->string('business');
            $t->string('city');
            $t->string('state');
            $t->string('address1');
            $t->string('address2');
            $t->string('zipcode');
            $t->integer('user_id');
            $t->integer('nonmember_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('suggestions');
    }

}
