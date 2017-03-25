<?php namespace SOE\Repositories\Eloquent;

class TrainingSectionRepository extends BaseRepository implements \TrainingSectionRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'id',
        'name',
        'slug',
        'roles',
        'order',
        'parent_id',
    );

    protected $model = 'TrainingSection';

    public function findBySlug($slug)
    {
        return $this->query()->where('slug', $slug)->first();
    }

    public function createSection($params)
    {
        if(isset($params['name']))
            $params['slug'] = \SoeHelper::getSlug($params['name']);
        if(!isset($params['section_id']) || $params['section_id'] == 0)
        {
            return $this->create($params);
        }

        $section = $this->find($params['section_id']);
        if(!$section)
            return $this->create($params);

        return $this->update($params['section_id'], $params);
    }

    public function listSections($page = 0, $limit = 0, $order = null, $roles = null)
    {
        $query = $this->query();
        if($order)
            $query->orderBy($order);
        else
            $query->orderBy('order');
        if($roles)
        {
            if(!is_array($roles))
                $roles = explode(',', $roles);

            $query->where(function($query) use ($roles)
            {
                foreach($roles as $role)
                {
                    $query->orWhere('roles', 'LIKE', '%'.$role.'%');
                }
            });
        }
        $stats = $this->getStats(clone $query, $limit, $page);
        $query = $this->paginator($query, $limit, $page);
        $sections = $query->get();
        return array_merge(array('objects' => $sections), $stats);
    }

    public function getUserSections(\SOE\DB\User $user, $slug = '')
    {
        $types = explode(',', $user->type);
        $pages = $this->query()->orderBy('training_sections.order')
            ->orderBy('training_pages.order')
            ->where(function($query) use ($types)
            {
                foreach($types as $type)
                {
                    $query->orWhere('roles', 'LIKE', '%'.$type.'%');
                }    
            })
            ->join('training_pages', 'training_sections.id', '=', 'training_pages.section_id')
            ->where('training_sections.parent_id', 0)
            ->get(array(
                'training_pages.id',
                'training_pages.name',
                'training_pages.slug',
                'training_pages.content',
                'training_pages.type',
                'training_pages.section_id',
                'training_pages.url',
                \DB::raw('training_sections.name as section_name'),
                \DB::raw('training_sections.slug as section_slug')
            ));

        $aSections = [];
        foreach($pages as $page)
        {
            if(!isset($aSections[$page->section_name]))
                $aSections[$page->section_name] = array('pages' => array($page), 'active' => $page->slug == $slug ? 1 : 0);
            else
            {
                $aSections[$page->section_name]['pages'][] = $page;
                $aSections[$page->section_name]['active'] = $page->slug == $slug ? 1 : $aSections[$page->section_name]['active'];
            }
        }

        return $aSections;
    }

    public function getUserSectionsByParent(\SOE\DB\User $user, $parent = '', $slug = '')
    {
        $get = array(
            'training_pages.id',
            'training_pages.name',
            'training_pages.slug',
            'training_pages.content',
            'training_pages.type',
            'training_pages.section_id',
            'training_pages.url',
            \DB::raw('training_sections.name as section_name'),
            \DB::raw('training_sections.name as section_name'),
        );
        $types = explode(',', $user->type);
        $pages = $this->query()->orderBy('training_sections.order')
            ->orderBy('training_pages.order')
            ->where(function($query) use ($types)
            {
                foreach($types as $type)
                {
                    $query->orWhere('training_sections.roles', 'LIKE', '%'.$type.'%');
                }    
            })
            ->join('training_pages', 'training_sections.id', '=', 'training_pages.section_id');
        if($parent != '')
        {
            $pages->join(\DB::raw('training_sections parent_sections'), 'parent_sections.id', '=', 'training_sections.parent_id')
                ->where('parent_sections.slug', $parent);
            $get[] = \DB::raw('parent_sections.name as parent_name');
            $get[] = \DB::raw('parent_sections.slug as parent_slug');
        }
        $pages = $pages->get($get);

        $aSections = [];
        foreach($pages as $page)
        {
            if(!isset($aSections[$page->section_name]))
                $aSections[$page->section_name] = array('pages' => array($page), 'active' => $page->slug == $slug ? 1 : 0);
            else
            {
                $aSections[$page->section_name]['pages'][] = $page;
                $aSections[$page->section_name]['active'] = $page->slug == $slug ? 1 : $aSections[$page->section_name]['active'];
            }
        }

        return $aSections;
    }

    public function getChildrenByParent(\SOE\DB\User $user, $parent_id)
    {
        $parent = $this->find($parent_id);
        $types = explode(',', $user->type);
        $pages = $this->query()->orderBy('training_sections.order')
            ->orderBy('training_pages.order')
            ->where(function($query) use ($types)
            {
                foreach($types as $type)
                {
                    $query->orWhere('training_sections.roles', 'LIKE', '%'.$type.'%');
                }    
            })
            ->where('training_pages.section_id', $parent_id)
            ->join('training_pages', 'training_sections.id', '=', 'training_pages.section_id')
            ->get(array(
                'training_pages.id',
                'training_pages.name',
                'training_pages.slug',
                'training_pages.content',
                'training_pages.type',
                'training_pages.section_id',
                'training_pages.url',
                'training_pages.order',
                \DB::raw('training_sections.slug as section_slug'),
                \DB::raw('"'.$parent->slug.'" as parent_slug')
        )   );

        $sections = $this->query()->orderBy('training_sections.order')
            ->orderBy('training_sections.order')
            ->where(function($query) use ($types)
            {
                foreach($types as $type)
                {
                    $query->orWhere('training_sections.roles', 'LIKE', '%'.$type.'%');
                }    
            })
            ->where('training_sections.parent_id', $parent_id)
            ->get(array(
                'training_sections.id',
                'training_sections.name',
                'training_sections.slug',
                'training_sections.parent_id',
                'training_sections.order',
                \DB::raw('"'.$parent->slug.'" as parent_slug')
            ));

        $aChildren = array();
        foreach($pages as $page)
        {
            $aChildren[$page->name] = array('type' => 'page', 'order' => $page->order, 'object' => $page->toArray());
        }

        foreach($sections as $section)
        {
            $aChildren[$section->name] = array('type' => 'section', 'order' => $section->order, 'object' => $section->toArray());
        }

        usort($aChildren, function($a, $b){
            if($a['order'] == $b['order'])
                return 0;
            return $a['order'] < $b['order'] ? -1 : 1;
        });

        return $aChildren;
    }

}
