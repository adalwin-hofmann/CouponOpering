<?php

class EloquentCategoryRepository extends BaseEloquentRepository implements CategoryRepository, RepositoryInterface
{
    protected $columns = array(
        'name',
        'slug',
        'tags',
        'parent_id',
        'above_heading',
        'footer_heading',
        'title',
        'description',
        'sub_heading'
    );

    protected $model = 'Category';

    /**
     * Retrieve a category by its slug.
     *
     * @param  string $slug
     * @return CategoryRepository
     */
    public function findBySlug($slug)
    {
        $category = SOE\DB\Category::where('slug', '=', $slug)->first();
        $cat = Category::blank();
        $cat = $cat->createFromModel($category);
        return $cat;
    }

    /**
     * Retrieve categories by their parent's slug.
     *
     * @param  string   $slug
     * @param  int      $page  Default 0.
     * @param  int      $limit Default no limit.
     * @return array CategoryRepositories
     */
    public function getByParentSlug($slug, $page = 0, $limit = 0)
    {
        $category = SOE\DB\Category::where('slug', '=', $slug)->first();
        if(empty($category))
            return;
        $query = SOE\DB\Category::where('parent_id', '=', $category->id)
                                    ->orderBy('name', 'asc');
        $stats = $this->getStats(clone $query, $page, $limit);
        if($limit != 0)
        {
            $query = $query->take($limit)->skip($limit*$page);
        }
        $categories = $query->get();
        $results = array('objects' => array());
        foreach($categories as $cat)
        {
            $subcat = Category::blank();
            $results['objects'][] = $subcat->createFromModel($cat);
        }
        $stats['stats']['returned'] = count($categories);
        $results = array_merge($results, $stats);
        return $results;
    }

    /**
     * Retrieve categories by their parent's id.
     *
     * @param  int      $id
     * @param  int      $page  Default 0.
     * @param  int      $limit Default no limit.
     * @return array CategoryRepositories
     */
    public function getByParentId($id, $page = 0, $limit = 0)
    {
        $query = SOE\DB\Category::where('parent_id', '=', $id)
                                    ->orderBy('name', 'asc');
        $stats = $this->getStats(clone $query, $page, $limit);
        if($limit != 0)
        {
            $query = $query->take($limit)->skip($limit*$page);
        }
        $categories = $query->get();
        $results = array('objects' => array());
        foreach($categories as $cat)
        {
            $subcat = Category::blank();
            $results['objects'][] = $subcat->createFromModel($cat);
        }
        $stats['stats']['returned'] = count($categories);
        $results = array_merge($results, $stats);
        return $results;
    }

    /***** API METHODS *****/

    /**
     * Retrieve categories by their parent's slug, page, limit.
     *
     * @api
     *
     * @return mixed Formatted array of categories.
     */
    public function apiGetByParentSlug()
    {
        $slug = Input::get('slug');
        $page = Input::get('page', 0);
        $limit = Input::get('limit', 0);
        return $this->format($this->getByParentSlug($slug, $page, $limit));
    }

    /**
     * Retrieve categories by their parent's category_id, page, limit.
     *
     * @api
     *
     * @return mixed Formatted array of categories.
     */
    public function apiGetByParentId()
    {
        $category_id = Input::get('category_id');
        $page = Input::get('page', 0);
        $limit = Input::get('limit', 0);
        return $this->format($this->getByParentId($category_id, $page, $limit));
    }

}
