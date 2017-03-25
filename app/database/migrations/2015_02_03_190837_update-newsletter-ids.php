<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNewsletterIds extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('newsletters', function($table)
        {
            $table->boolean('converted')->default(false);
        });

		$zipcodes = DB::table('newsletter_schedules')->where('schedule_name', '!=', '')
            ->groupBy('zipcode')
            ->lists('zipcode');

        foreach($zipcodes as $zipcode)
        {
            $first = DB::table('newsletter_schedules')->where('zipcode', $zipcode)
                ->where('schedule_name', '!=', '')
                ->orderBy('id')
                ->first();

            $zipall = DB::table('newsletter_schedules')->where('zipcode', $zipcode)
                ->where('schedule_name', '!=', '')
                ->lists('batch_id');
            $zipall[] = 0;

            DB::table('newsletter_schedules')->where('zipcode', $zipcode)
                ->where('schedule_name', '!=', '')
                ->update(array(
                    'batch_id' => $first->id
                ));

            DB::table('newsletters')->whereIn('batch_id', $zipall)
                ->where('converted', 0)
                ->update(array(
                    'batch_id' => $first->id,
                    'converted' => 1
                ));
        }

        DB::table('newsletter_schedules')->where('schedule_name', '=', '')
            ->update(array(
                'batch_id' => 1
            ));

        Schema::table('newsletters', function($table)
        {
            $table->dropColumn('converted');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
