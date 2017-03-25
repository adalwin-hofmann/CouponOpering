<?php

use Illuminate\Database\Migrations\Migration;

class CreateAssetTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('asset_tags', function($table)
        {
            $table->integer('asset_id');
            $table->integer('tag_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('asset_tags');
    }

}