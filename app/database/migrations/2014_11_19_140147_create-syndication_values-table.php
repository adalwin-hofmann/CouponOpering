<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSyndicationValuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('syndication_values', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->integer('syndicatable_id');
            $table->string('syndicatable_type');
            $table->float('value',6,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('syndication_values');
    }

}
