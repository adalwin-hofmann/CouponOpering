<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFranchisesNetlmsId extends Migration {

	/**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchises', function($table)
        {
            $table->integer('netlms_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('franchises', function($table)
        {
            $table->dropColumn('netlms_id');
        });
    }

}
