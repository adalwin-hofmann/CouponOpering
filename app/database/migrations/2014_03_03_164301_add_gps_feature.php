<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGpsFeature extends Migration {

    const TYPE = 'config';
    const ENTITY = 'save';
    const FEATURE = 'mobile_gps_location';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('features')->insert(array(
            'type' => self::TYPE,
            'entity' => self::ENTITY,
            'name' => self::FEATURE,
            'value' => 0,
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
        DB::table('features')
            ->where('type', self::TYPE)
            ->where('entity', self::ENTITY)
            ->where('name', self::FEATURE)
            ->delete();
    }

}
