<?php namespace SOE\Repositories\Eloquent;

class SeoContentRepository extends BaseRepository implements \SeoContentRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'page_url',
        'content_type',
        'content',
    );

    protected $model = 'SeoContent';

    /**
     * Create a new Object with given attributes.
     *
     * @param  array $attributes
     * @return mixed $object
     */
    public function create(array $attributes = array())
    {
        if( $this->isValid('create', $attributes) )
        {
            return parent::create($attributes);
        }

        return false;
    }

    /**
     * Find seo content by page url and content type.
     *
     * @param string $url
     * @param string $type
     * @return mixed
     */
    public function findByUrlAndType($url, $type = null)
    {
        $url = $this->cleanUrl($url);
        if($type)
        {
            return $this->query()
                ->where('page_url', $url)
                ->where('content_type', $type)
                ->first();
        }

        $contents = $this->query()
            ->where('page_url', $url)
            ->get(array('content_type', 'content'));
        $aContent = array();
        foreach($contents as $content)
        {
            $aContent[$content->content_type] = $content->content;
        }
        return $aContent;
    }

    /**
     * Retrieve list of existing page url groupings.
     *
     * @return mixed
     */
    public function getPageUrls()
    {
        return $this->query()
            ->groupBy('page_url')
            ->orderBy('page_url')
            ->get();
    }

    /**
     * Create or update seo content.
     *
     * @param array $params
     * @return mixed
     */
    public function modifySeo(array $params)
    {
        if(isset($params['id']) && $params['id'] != 0)
            $seo = $this->update($params['id'], $params);
        else
            $seo = $this->create($params);
        return $seo;
    }

    public function getByWildUrl($specific, $generic)
    {
        if($specific == '/')
            return array();
        
        $specific = $this->cleanUrl($specific);
        $specificPieces = explode('/', $specific);
        $genericPieces = explode('/', $generic);
        $aReplacements = array();
        for($i=0; $i<count($genericPieces); $i++)
        {
            if(isset($specificPieces[$i]) && stristr($genericPieces[$i], '{'))
                $aReplacements[] = array('key' => $i, 'generic' => $genericPieces[$i], 'specific' => $specificPieces[$i]);
        }
        if(! count($aReplacements))
            return array();
        $modified = $specific;
        /*for($i=0; $i<count($aReplacements); $i++)
        {
            $modified = str_replace($aReplacements[$i]['specific'], $aReplacements[$i]['generic'], $modified);
            print_r($modified);
        }
        exit;*/
        $contents = $this->query()
            ->where(function($query) use ($aReplacements,$specific)
            {
                $modified = $specific;
                for($i=0; $i<count($aReplacements); $i++)
                {
                    $modified = str_replace($aReplacements[$i]['specific'], $aReplacements[$i]['generic'], $modified);
                    $query->orWhere('page_url', \DB::raw("'".$modified."'"));
                }
            })
            ->orderBy('page_url')
            ->get(array('page_url', 'content_type', 'content'));
        $aContent = array();
        foreach($contents as $content)
        {
            if(isset($aContent[$content->page_url]))
                $aContent[$content->page_url][$content->content_type] = $content->content;
            else
                $aContent[$content->page_url] = array($content->content_type => $content->content);
        }
        return $aContent;
    }

    protected function cleanUrl($url)
    {
        $pos = strpos($url, "%");
        $pos = $pos ? $pos : strlen($url);
        return substr($url, 0, $pos);
    }
}