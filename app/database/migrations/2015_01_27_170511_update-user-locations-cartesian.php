<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserLocationsCartesian extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '2048M');
		$users = DB::table('users')->where('latitude', '!=', 0)->where('longitude', '!=', 0)->get(array('id', 'latitude', 'longitude'));
        foreach($users as $user)
        {
            $cartesian = \SoeHelper::getCartesian($user->latitude, $user->longitude);
            DB::table('users')->where('id', $user->id)->update(array(
                'updated_at' => date('Y-m-d H:i:s'),
                'latm' => $cartesian['latm'],
                'lngm' => $cartesian['lngm']
            ));
        }

        $locations = DB::table('user_locations')->where('latitude', '!=', 0)->where('longitude', '!=', 0)->where('is_deleted', 0)->get(array('id', 'latitude', 'longitude'));
        foreach($locations as $location)
        {
            $cartesian = \SoeHelper::getCartesian($location->latitude, $location->longitude);
            DB::table('user_locations')->where('id', $location->id)->update(array(
                'updated_at' => date('Y-m-d H:i:s'),
                'latm' => $cartesian['latm'],
                'lngm' => $cartesian['lngm']
            ));
        }
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
