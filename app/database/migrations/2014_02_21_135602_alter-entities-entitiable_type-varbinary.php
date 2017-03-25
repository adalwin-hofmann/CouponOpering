<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEntitiesEntitiableTypeVarbinary extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '2048M');
        DB::statement("ALTER TABLE entities CHANGE entitiable_type entitiable_type VARBINARY(255) NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '2048M');
        DB::statement("ALTER TABLE entities CHANGE entitiable_type entitiable_type VARCHAR(255) NOT NULL");
    }

}
