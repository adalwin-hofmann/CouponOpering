<?php namespace SOE\Repositories\Eloquent;

class AssetRepository extends BaseRepository implements \AssetRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'assetable_id',
        'assetable_type',
        'path',
        'name',
        'long_description',
        'short_description',
        'category_id',
        'subcategory_id',
        'sub_subcategory_id',
        'original_id',
        'type',
        'original_path',
    );

    protected $model = 'Asset';

    /**
     * Get the Logo for the given merchant;
     *
     * @param  \SOE\DB\Merchant $merchant
     * @return \SOE\DB\Asset
     */
    public function getLogo(\SOE\DB\Merchant $merchant, $remember = true)
    {
        $query = $this->query()
                    ->where('assetable_type', '=', 'Merchant')
                    ->where('assetable_id', '=', $merchant->id)
                    ->where('name', '=', 'logo1');
        if($remember)
            $query = $query->remember(\Config::get('soe.cache', 60*60*24));
        return $query->first();
    }

    /**
     * Get the Banner for the given merchant;
     *
     * @param  \SOE\DB\Merchant $merchant
     * @return \SOE\DB\Asset
     */
    public function getBanner(\SOE\DB\Merchant $merchant, $remember = true)
    {
        $query = $this->query()
                    ->where('assetable_type', '=', 'Merchant')
                    ->where('assetable_id', '=', $merchant->id)
                    ->where('name', '=', 'banner');
        if($remember)
            $query = $query->remember(\Config::get('soe.cache', 60*60*24));
        return $query->first();
    }

    /**
     * Get the Logo for the given merchant;
     *
     * @param  \SOE\DB\Location $merchant
     * @return \SOE\DB\Asset
     */
    public function getLocationLogo(\SOE\DB\Location $location, $remember = true)
    {
        $query = $this->query()
                    ->where('assetable_type', '=', 'Location')
                    ->where('assetable_id', '=', $location->id)
                    ->where('name', '=', 'logo1');
        if($remember)
            $query = $query->remember(\Config::get('soe.cache', 60*60*24));
        return $query->first();
    }

    /**
     * Get the Banner for the given merchant;
     *
     * @param  \SOE\DB\Location $merchant
     * @return \SOE\DB\Asset
     */
    public function getLocationBanner(\SOE\DB\Location $location, $remember = true)
    {
        $query = $this->query()
                    ->where('assetable_type', '=', 'Location')
                    ->where('assetable_id', '=', $location->id)
                    ->where('name', '=', 'banner');
        if($remember)
            $query = $query->remember(\Config::get('soe.cache', 60*60*24));
        return $query->first();
    }

    /**
     * Get the gallery images for the given merchant;
     *
     * @param  \SOE\DB\Merchant $merchant
     * @return array Merchant gallery images.
     */
    public function getMerchantImages(\SOE\DB\Merchant $merchant)
    {
        $query = $this->query()
                    ->where('assetable_type', '=', 'Merchant')
                    ->where('assetable_id', '=', $merchant->id)
                    ->where('name', 'LIKE', '%smallImage%');
        $stats = $this->getStats(clone $query, 0, 0);
        $assets = $query->remember(\Config::get('soe.cache', 60*60*24))->get();
        $aOrdered = array();
        foreach($assets as $asset)
        {
            $number = substr($asset->name, 10);
            $aOrdered[$number] = $asset;
        }
        ksort($aOrdered);
        $assets = array();
        foreach($aOrdered as $key => $object)
        {
            $assets[] = $object;
        }
        $stats['stats']['returned'] = count($assets);
        $return = array('objects' => $assets);                         
        return array_merge($return, $stats);
    }

    /**
     * Get the gallery images for the given location;
     *
     * @param  \SOE\DB\Location $location
     * @return array Merchant gallery images.
     */
    public function getLocationImages(\SOE\DB\Location $location)
    {
        $query = $this->query()
                    ->where('assetable_type', '=', 'Location')
                    ->where('assetable_id', '=', $location->id)
                    ->where('name', 'LIKE', '%smallImage%');
        $stats = $this->getStats(clone $query, 0, 0);
        $assets = $query->remember(\Config::get('soe.cache', 60*60*24))->get();
        $aOrdered = array();
        foreach($assets as $asset)
        {
            $number = substr($asset->name, 10);
            $aOrdered[$number] = $asset;
        }
        ksort($aOrdered);
        $assets = array();
        foreach($aOrdered as $key => $object)
        {
            $assets[] = $object;
        }
        $stats['stats']['returned'] = count($assets);
        $return = array('objects' => $assets);                         
        return array_merge($return, $stats);
    }

    /**
     * Get the pdfs for the given merchant;
     *
     * @param  \SOE\DB\Merchant $merchant
     * @return array Merchant pdfs.
     */
    public function getMerchantPdfs(\SOE\DB\Merchant $merchant)
    {
        $query = $this->query()
                    ->where('assetable_type', '=', 'Merchant')
                    ->where('assetable_id', '=', $merchant->id)
                    ->where('name', 'LIKE', '%pdf%');
        $stats = $this->getStats(clone $query, 0, 0);
        $assets = $query->get();
        $stats['stats']['returned'] = count($assets);
        $return = array('objects' => $assets);                      
        return array_merge($return, $stats);
    }

    /**
     * Get the pdfs for the given location;
     *
     * @param  \SOE\DB\Location $location
     * @return array Location pdfs.
     */
    public function getLocationPdfs(\SOE\DB\Location $location)
    {
        $query = $this->query()
                    ->where('assetable_type', '=', 'Location')
                    ->where('assetable_id', '=', $location->id)
                    ->where('name', 'LIKE', '%pdf%');
        $stats = $this->getStats(clone $query, 0, 0);
        $assets = $query->get();
        $stats['stats']['returned'] = count($assets);
        $return = array('objects' => $assets);                      
        return array_merge($return, $stats);
    }

    /**
     * Get the videos for the given merchant;
     *
     * @param  \SOE\DB\Merchant $merchant
     * @return array Merchant videos.
     */
    public function getMerchantVideos(\SOE\DB\Merchant $merchant)
    {
        $query = $this->query()
                    ->where('assetable_type', '=', 'Merchant')
                    ->where('assetable_id', '=', $merchant->id)
                    ->where('type', '=', 'video');
        $stats = $this->getStats(clone $query, 0, 0);
        $assets = $query->get();
        $stats['stats']['returned'] = count($assets);
        $return = array('objects' => $assets);                        
        return array_merge($return, $stats);
    }

    /**
     * Get the videos for the given location;
     *
     * @param  \SOE\DB\Location $location
     * @return array Location videos.
     */
    public function getLocationVideos(\SOE\DB\Location $location)
    {
        $query = $this->query()
                    ->where('assetable_type', '=', 'Location')
                    ->where('assetable_id', '=', $location->id)
                    ->where('type', '=', 'video');
        $stats = $this->getStats(clone $query, 0, 0);
        $assets = $query->get();
        $stats['stats']['returned'] = count($assets);
        $return = array('objects' => $assets);                        
        return array_merge($return, $stats);
    }
}