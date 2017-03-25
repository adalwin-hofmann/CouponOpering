<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertFeatureNewsletterSubjectIntro extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        DB::statement("ALTER TABLE features MODIFY value TEXT");
        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'member_newsletter_subject',
            'value' => 'SaveOn Local Deals Just For You',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'Subject line of member newsletter.'
        ));
        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'member_newsletter_intro',
            'value' => 'Hi SaveOn Member! We’ve done a little homework and found a few of our favorite deals just for you...',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'Intro paragraph of member newsletter.'
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'member_newsletter_subject')->delete();
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'member_newsletter_intro')->delete();
    }

}
