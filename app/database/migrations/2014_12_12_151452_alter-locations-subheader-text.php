<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLocationsSubheaderText extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('locations', function($table)
        {
            $table->text('subheader');
        });
        DB::table('locations')->update(array(
            'subheader' => '{Online|Free} [merchant] coupons{, deals & sweepstakes} from SaveOn help you save money on [category] and more. Click here to {print|search for} <a href="/coupons/[state_lower]/[city_slug]/[category_slug]/[subcategory_slug]">[subcategory] coupons</a> {near you|in [city], [state]|close by|in your local area}.'
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function($table)
        {
            $table->dropColumn('subheader');
        });
    }

}
