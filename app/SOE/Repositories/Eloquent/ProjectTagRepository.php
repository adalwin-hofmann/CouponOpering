<?php namespace SOE\Repositories\Eloquent;

class ProjectTagRepository extends BaseRepository implements \ProjectTagRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'name',
        'slug',
        'tags',
        'above_heading',
        'footer_heading',
        'title',
        'description',
    );

    protected $model = 'ProjectTag';

    /**
     * Get the project tags for a franchise, or all tags if no franchise is given.
     *
     * @param SOE\DB\Franchise
     * @return array
     */
    public function getFranchiseTags(\SOE\DB\Franchise $franchise = null)
    {
        $query = \SOE\DB\ProjectTag::orderBy('name', 'asc');
        if($franchise)
        {
            $franchise_tags = \DB::table('franchise_project_tag')->where('franchise_id', '=', $franchise->id)->get();
            $aTags = array(0);
            foreach($franchise_tags as $tag)
            {
                $aTags[] = $tag->project_tag_id;
            }
            $query = $query->whereIn('id', $aTags);
        }

        return $query->get();
    }

    /**
     * Retrieve a Project Tag by slug.
     *
     * @param string    $slug The slug to search by.
     * @return SOE\DB\ProjectTag
     */
    public function findBySlug($slug)
    {
        return \SOE\DB\ProjectTag::where('slug', '=', $slug)->first();
    }

    /**
     * Retrieve parent project tags.
     *
     * @param   int     $page
     * @param   int     $limit
     * @return  array
     */
    public function getParentTags($page = 0, $limit = 0)
    {
        $parents = \DB::table('project_tag_relation')->groupBy('parent_id')->get(array('parent_id'));
        $aParents = array(0);
        foreach($parents as $parent)
        {
            $aParents[] = $parent->parent_id;
        }
        $tags = \SOE\DB\ProjectTag::whereIn('id', $aParents);
        $stats = $this->getStats(clone $tags, $limit, $page);
        if($limit)
        {
            $tags = $tags->skip($page*$limit)->take($limit);
        }
        $tags = $tags->orderBy('name')->get();
        $stats['stats']['returned'] = count($tags);
        return array('objects' => $tags, $stats);
    }

    /**
     * Retrieve child project tags.
     *
     * @param   int     $page
     * @param   int     $limit
     * @return  array
     */
    public function getChildTags($page = 0, $limit = 0)
    {
        $parents = \DB::table('project_tag_relation')->groupBy('parent_id')->get(array('parent_id'));
        $aParents = array(0);
        foreach($parents as $parent)
        {
            $aParents[] = $parent->parent_id;
        }
        $childTags = \SOE\DB\ProjectTag::whereNotIn('id', $aParents);
        $stats = $this->getStats(clone $childTags, $limit, $page);
        if($limit)
        {
            $childTags = $childTags->skip($page*$limit)->take($limit);
        }
        $childTags = $childTags->orderBy('name')->get();
        $stats['stats']['returned'] = count($childTags);
        return array('objects' => $childTags, $stats);
    }

    /**
     * Retrieve the children project tags of the given parent.
     *
     * @param   SOE\DB\ProjectTag   $parent
     * @param   int                 $page
     * @param   int                 $limit
     * @return  array
     */
    public function getChildren(\SOE\DB\ProjectTag $parent, $page = 0, $limit = 0)
    {
        $children = \DB::table('project_tag_relation')->where('parent_id', '=', $parent->id)->get();
        $aChildren = array(0);
        foreach($children as $child)
        {
            $aChildren[] = $child->child_id;
        }
        $tags = \SOE\DB\ProjectTag::whereIn('id', $aChildren);
        $stats = $this->getStats(clone $tags, $limit, $page);
        if($limit)
        {
            $tags = $tags->skip($page*$limit)->take($limit);
        }
        $tags = $tags->orderBy('name')->get();
        $stats['stats']['returned'] = count($tags);
        return array('objects' => $tags, $stats);
    }
}

