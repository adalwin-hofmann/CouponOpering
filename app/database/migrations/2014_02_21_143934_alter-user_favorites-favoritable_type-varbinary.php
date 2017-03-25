<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserFavoritesFavoritableTypeVarbinary extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        DB::statement("ALTER TABLE user_favorites CHANGE favoritable_type favoritable_type VARBINARY(255) NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE user_favorites CHANGE favoritable_type favoritable_type VARCHAR(255) NOT NULL");
    }

}
