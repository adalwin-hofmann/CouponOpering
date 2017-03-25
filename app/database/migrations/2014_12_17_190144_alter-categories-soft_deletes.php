<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCategoriesSoftDeletes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('categories', function($table)
        {
            $table->integer('category_order');
            $table->softDeletes();
        });
        DB::table('categories')->where('slug', 'home-improvement-old')
            ->orWhere('slug', 'around-the-house')
            ->orWhere('slug', 'national-brands')
            ->update(array('deleted_at' => date('Y-m-d H:i:s')));

        \Eloquent::unguard();

        $community = \SOE\DB\Category::create(array(
            'name' => 'Community',
            'slug' => 'community',
            'tags' => 'community'
        ));

        \SOE\DB\Category::create(array(
            'parent_id' => $community->id,
            'name' => 'Arts, Culture & Humanities',
            'slug' => 'arts-culture-humanities'
        ));

        \SOE\DB\Category::create(array(
            'parent_id' => $community->id,
            'name' => 'Education & Research',
            'slug' => 'education-research'
        ));

        \SOE\DB\Category::create(array(
            'parent_id' => $community->id,
            'name' => 'Environment & Animals',
            'slug' => 'environment-animals'
        ));

        \SOE\DB\Category::create(array(
            'parent_id' => $community->id,
            'name' => 'Health',
            'slug' => 'health'
        ));

        \SOE\DB\Category::create(array(
            'parent_id' => $community->id,
            'name' => 'Human Services',
            'slug' => 'human-services'
        ));
        
        \SOE\DB\Category::create(array(
            'parent_id' => $community->id,
            'name' => 'Organizations',
            'slug' => 'organizations'
        ));
        
        \SOE\DB\Category::create(array(
            'parent_id' => $community->id,
            'name' => 'Public, Societal Benefit',
            'slug' => 'public-societal-benefit'
        ));

        $categories = DB::table('categories')->where('parent_id', '0')->orderBy('name')->get();
        $count = 0;
        foreach($categories as $category)
        {
            DB::table('categories')->where('id', $category->id)->update(array('category_order' => $count++));
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function($table)
        {
            $table->dropColumn('category_order');
            $table->dropColumn('deleted_at');
        });
    }

}
