<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProjectTagsSlugSeoFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('project_tags', function($table)
        {
            $table->string('slug');
            $table->string('tags');
            $table->text('above_heading');
            $table->text('footer_heading');
            $table->string('title');
            $table->string('description');
        });

        $tags = DB::table('project_tags')->get();
        foreach($tags as $tag)
        {
            DB::table('project_tags')->where('id', '=', $tag->id)->update(array('slug' => SoeHelper::getSlug($tag->name)));
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_tags', function($table)
        {
            $table->dropColumn('slug');
            $table->dropColumn('tags');
            $table->dropColumn('above_heading');
            $table->dropColumn('footer_heading');
            $table->dropColumn('title');
            $table->dropColumn('description');
        });
    }

}
