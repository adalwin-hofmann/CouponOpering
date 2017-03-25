<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('reports', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('name');
            $table->string('link');
            $table->integer('parent_id');
            $table->string('parent_link');
        });

        DB::table('reports')->insert(array('name' => 'Sales Report', 'link' => '/reports/salesreport'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }

}
