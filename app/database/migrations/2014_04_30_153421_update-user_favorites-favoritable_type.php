<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserFavoritesFavoritableType extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('user_favorites')->where('favoritable_type', 'LIKE', '%Location%')->update(array(
            'favoritable_type' => 'SOE\\DB\\Location'
        ));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('user_favorites')->where('favoritable_type', 'LIKE', '%Location%')->update(array(
            'favoritable_type' => 'Location'
        ));
	}

}
