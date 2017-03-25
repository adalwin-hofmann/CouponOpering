<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEntitiesCategorySlugSubcategorySlug extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '2048M');
        Schema::table('entities', function($table)
        {
            $table->string('category_slug');
            $table->string('subcategory_slug');
            $table->index('category_id', 'category_index');
            $table->index('subcategory_id', 'subcategory_index');
        });
        $categories = SOE\DB\Category::where('parent_id', '=', '0')->get(array('id', 'slug'));
        $subcategories = SOE\DB\Category::where('parent_id', '>', '0')->get(array('id', 'slug'));
        foreach($categories as $cat)
        {
            DB::table('entities')->where('category_id', '=', $cat->id)->update(array('category_slug' => $cat->slug));
        }

        foreach($subcategories as $subcat)
        {
            DB::table('entities')->where('subcategory_id', '=', $subcat->id)->update(array('subcategory_slug' => $subcat->slug));
        }
        Schema::table('entities', function($table)
        {
            $table->dropIndex('category_index');
            $table->dropIndex('subcategory_index');
        });
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
        Schema::table('entities', function($table)
        {
            $table->dropColumn('category_slug');
            $table->dropColumn('subcategory_slug');
        });
    }

}
