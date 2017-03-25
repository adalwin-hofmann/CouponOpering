<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealerSyncTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('dealer_sync', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('method');
            $table->integer('batch');
            $table->dateTime('merged_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dealer_sync');
    }

}
