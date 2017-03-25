<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSohiSurveysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('sohi_surveys', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('user_name');
            $table->integer('user_id');
            $table->string('business_name');
            $table->string('expected_completion');
            $table->integer('rating');
            $table->string('work_begun', 20);
            $table->string('completion_expected', 20);
            $table->text('feedback')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sohi_surveys');
    }

}
