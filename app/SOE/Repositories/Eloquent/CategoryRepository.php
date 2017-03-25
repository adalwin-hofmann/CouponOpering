<?php namespace SOE\Repositories\Eloquent;

class CategoryRepository extends BaseRepository implements \CategoryRepositoryInterface, \BaseRepositoryInterface
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
        'sub_heading',
    );

    protected $model = 'Category';

    /**
     * Retrieve a category by its slug.
     *
     * @param  string $slug
     * @return SOE\DB\Category
     */
    public function findBySlug($slug)
    {
        $category = \SOE\DB\Category::where('slug', '=', $slug)->first();
        return $category;
    }

    public function listCategories()
    {
        $categories = $this->query()->orderBy('parent_id');
        $data = array();
        foreach($categories as $category)
        {
            if($category->parent_id == 0)
            {
                $data[$category->id] = array('category' => $category, 'subcategories' => array());
            }
            else
            {
                $data[$category->parent_id]['subcategories'][$category->id] = $category;
            }
        }

        return $data;
    }

    /**
     * Retrieve categories by their parent's slug.
     *
     * @param  string   $slug
     * @param  int      $page  Default 0.
     * @param  int      $limit Default no limit.
     * @return array
     */
    public function getByParentSlug($slug, $page = 0, $limit = 0)
    {
        $category = \SOE\DB\Category::where('slug', '=', $slug)->first();
        if(empty($category))
            return;
        $query = \SOE\DB\Category::where('parent_id', '=', $category->id)
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
            $results['objects'][] = $cat;
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
     * @return array
     */
    public function getByParentId($id, $page = 0, $limit = 0)
    {
        $query = \SOE\DB\Category::where('parent_id', '=', $id);
        if($id == 0)
            $query->orderBy('category_order');
        else
            $query->orderBy('name', 'asc');
        $stats = $this->getStats(clone $query, $page, $limit);
        if($limit != 0)
        {
            $query = $query->take($limit)->skip($limit*$page);
        }
        $categories = $query->get();
        $results = array('objects' => array());
        foreach($categories as $cat)
        {
            $results['objects'][] = $cat;
        }
        $stats['stats']['returned'] = count($categories);
        $results = array_merge($results, $stats);
        return $results;
    }

}