<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOffersRequiresMember extends Migration {

	/**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offers', function($table)
        {
            $table->boolean('requires_member')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function($table)
        {
            $table->dropColumn('requires_member');            
        });
    }

}
