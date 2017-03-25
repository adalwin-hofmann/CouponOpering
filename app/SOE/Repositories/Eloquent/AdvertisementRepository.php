<?php namespace SOE\Repositories\Eloquent;

class AdvertisementRepository extends BaseRepository implements \AdvertisementRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'franchise_id',
        'merchant_id',
        'location_id',
        'adable_type',
        'adable_id',
        'ad_type',
        'ad_level',
        'ad_section',
        'path',
        'category_id',
        'subcategory_id',
        'title',
        'content',
        'url_override',
        'latm',
        'lngm',
        'is_active',
        'starts_at',
        'expires_at',
        'is_demo',
    );

    public function __construct(
        \FeatureRepositoryInterface $features
    )
    {
        $this->features = $features;
        parent::__construct();
    }

    protected $model = 'Advertisement';

    /**
     * Retrieve ads matching the given paramenters.
     *
     * @param string $type The ad type
     * @param string $level The ad level
     * @param string $section The ad section
     * @param integer $category_id
     * @param integer $subcategory_id
     * @param boolean $show_demo
     * @param integer $page
     * @param integer $limit
     * @return mixed
     */
    public function search($type, $level, $section, $adable_type = null, $category_id = null, $subcategory_id = null, $show_demo = false, $page = 0, $limit = 1)
    {
        $query = $this->with(array('merchant','category', 'subcategory'))
            ->where('ad_type', $type)
            ->where('ad_level', $level)
            ->where('ad_section', $section);

        $geoip = json_decode(\GeoIp::getGeoIp('json'));
        $cartesian = \SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
        $distance = \DB::raw('(sqrt(pow(latm - '.$cartesian['latm'].', 2) + pow(lngm - '.$cartesian['lngm'].', 2)))');
        $ad_distance = $this->features->findByName('ad_distance');
        $query = $query->where($distance, '<', ($ad_distance ? $ad_distance->value : 48000));

        if($adable_type)
        {
            $adables = explode(',', $adable_type);
            $query->where(function($query) use ($adables)
            {
                $query->where('adable_type', $adables[0]);
                for($i=1; $i<count($adables); $i++)
                {
                    $query->orWhere('adable_type', $adables[$i]);
                }
            });
        }
        if($category_id)
            $query->where('category_id', $category_id);
        if($subcategory_id)
            $query->where('subcategory_id', $subcategory_id);
        if(!$show_demo)
            $query->where('is_demo', 0);
        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit)
            $query->take($limit)->skip($page*$limit);
        $ads = $query->orderBy(\DB::raw('RAND()'))->get();

        $results = array('objects' => array());
        foreach($ads as &$ad)
        {
            //echo $ad->adable->entitiable_type;
            $adable = $ad->adable;
            $ad->adable = $adable;

            if($ad->adable_type == 'VehicleEntity')
            {
                $images = explode(',', str_replace('|', ',', $ad->adable->image_urls));
                if(count($images))
                    $ad->adable->display_image = $images[0];
                else
                {
                    $ad->adable->display_image = '';
                }
            }
            else if($ad->adable_type == 'VehicleStyle')
            {
                if(count($ad->adable->display_image))
                    $ad->adable->display_image = $ad->adable->display_image[0]->path;
                else
                {
                    $image = count($ad->adable->assets) ? $ad->adable->assets[0]['path'] : '';
                    $ad->adable->display_image = $image;
                }
            }
            else if($ad->adable->entitiable_type == 'Contest')
            {
                $contest = \SOE\DB\Contest::where('id', $ad->adable->entitiable_id)->remember(\Config::get('soe.cache', 60*24))->first(array('id', 'display_name'));
                //echo $contest->display_name.'<br>';
                $ad->adable->display_name = $contest->display_name;
            }

            $results['objects'][] = $ad;
        }
        $stats['stats']['returned'] = count($results['objects']);
        return array_merge($results, $stats);
    }
}