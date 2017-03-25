<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTackingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tracking', function($table)
		{
		    $table->timestamps();
			$table->increments('id');
		    $table->string('code');
		    $table->string('type');
		    $table->integer('days')->default(30);
		});

		Schema::table('user_views', function($table)
        {
            $table->integer('tracking_id')->nullable();
            $table->string('url')->nullable();
            $table->integer('refer_id')->nullable();
        });

        Schema::table('user_prints', function($table)
        {
            $table->integer('tracking_id')->nullable();
            $table->string('url')->nullable();
            $table->integer('refer_id')->nullable();
        });

        DB::table('tracking')->insert(array(
            'code' => 'fb',
            'type' => 'share',
            'days' => '1',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));

        DB::table('tracking')->insert(array(
            'code' => 'email',
            'type' => 'share',
            'days' => '1',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('tracking');

		Schema::table('user_views', function($table)
        {
            $table->dropColumn('tracking_id');
            $table->dropColumn('url');
            $table->dropColumn('refer_id');
        });

        Schema::table('user_prints', function($table)
        {
            $table->dropColumn('tracking_id');
            $table->dropColumn('url');
            $table->dropColumn('refer_id');
        });
	}

}
