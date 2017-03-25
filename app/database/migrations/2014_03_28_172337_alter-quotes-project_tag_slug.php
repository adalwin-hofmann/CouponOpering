<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQuotesProjectTagSlug extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE quotes CHANGE project_tag_name project_tag_slug VARCHAR(255)");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("ALTER TABLE quotes CHANGE project_tag_slug project_tag_name VARCHAR(255)");
	}

}
