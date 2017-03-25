<?php namespace SOE\Repositories\Eloquent;

class TrainingPageRepository extends BaseRepository implements \TrainingPageRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'id',
        'name',
        'slug',
        'content',
        'type',
        'section_id',
        'order',
        'url',
    );

    protected $model = 'TrainingPage';

    public function findBySlug(\SOE\DB\User $user, $one, $two, $three = null)
    {
        $types = explode(',', $user->type);
        $pageSlug = $three ? $three : $two;
        $query = $this->query()->where('training_pages.slug', $pageSlug)
            ->join('training_sections', 'training_pages.section_id', '=', 'training_sections.id')
            ->where(function($query) use ($types)
            {
                foreach($types as $type)
                {
                    $query->orWhere('training_sections.roles', 'LIKE', '%'.$type.'%');
                }    
            })
            ->where('training_sections.slug', $three ? $two : $one);
            if($three)
            {
                $query->join(\DB::raw('training_sections as parent_sections'), 'training_sections.parent_id', '=', 'parent_sections.id')
                    ->where('training_sections.slug', $one);
            }
            return $query->first(array('training_pages.*'));
    }

    public function createPage($params)
    {
        $section = null;
        if(isset($params['name']))
            $params['slug'] = \SoeHelper::getSlug($params['name']);
        if(isset($params['section_id']))
            $section = \SOE\DB\TrainingSection::find($params['section_id']);
        if(!isset($params['page_id']) || $params['page_id'] == 0)
        {
            return $this->create($params);
        }

        $page = $this->find($params['page_id']);
        if(!$page)
            $return = $this->create($params);
        else
            $return = $this->update($params['page_id'], $params);
        $return->section_slug = $section ? $section->slug : '';

        return $return;
    }

}
