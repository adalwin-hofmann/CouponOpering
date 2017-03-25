<?php

class EloquentAssetRepository extends BaseEloquentRepository implements AssetRepository, RepositoryInterface
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
    );

    protected $model = 'Asset';

    /**
     * Get the Logo for the given merchant;
     *
     * @param  MerchantRepository $merchant
     * @return mixed Merchant logo.
     */
    public function getLogo(MerchantRepository $merchant)
    {
        $asset = SOE\DB\Asset::where('assetable_type', '=', 'Merchant')
                            ->where('assetable_id', '=', $merchant->id)
                            ->where('name', '=', 'logo1')
                            ->remember(Config::get('soe.cache', 60*60*24))
                            ->first();
        if(empty($asset))
        {
            $asset = SOE\DB\Offer::where('merchant_id', '=', $merchant->id)->remember(Config::get('soe.cache', 60*60*24))->first();

            $return = Asset::blank();
            // We need to check if an asset object is not created.
            $return->path = (isset($asset->path))?$asset->path:"";
            return $return;
        }                          
        $return = Asset::blank();
        $return = $return::createFromModel($asset);
        return $return;
    }

    /**
     * Get the gallery images for the given merchant;
     *
     * @param  MerchantRepository $merchant
     * @return array Merchant gallery images.
     */
    public function getMerchantImages(MerchantRepository $merchant)
    {
        $query = SOE\DB\Asset::where('assetable_type', '=', 'Merchant')
                            ->where('assetable_id', '=', $merchant->id)
                            ->where('name', 'LIKE', '%smallImage%');
        $stats = $this->getStats(clone $query, 0, 0);
        $assets = $query->remember(Config::get('soe.cache', 60*60*24))->get();
        $stats['stats']['returned'] = count($assets);
        $return = array('objects' => array());
        foreach($assets as $asset)
        {
            $object = Asset::blank();
            $object = $object->createFromModel($asset);
            $return['objects'][] = $object;
            unset($object);
        }                           
        $return = array_merge($return, $stats);
        return $return;
    }

    /**
     * Get the pdfs for the given merchant;
     *
     * @param  MerchantRepository $merchant
     * @return array Merchant pdfs.
     */
    public function getMerchantPdfs(MerchantRepository $merchant)
    {
        $query = SOE\DB\Asset::where('assetable_type', '=', 'Merchant')
                            ->where('assetable_id', '=', $merchant->id)
                            ->where('name', 'LIKE', '%pdf%');
        $stats = $this->getStats(clone $query, 0, 0);
        $assets = $query->get();
        $stats['stats']['returned'] = count($assets);
        $return = array('objects' => array());
        foreach($assets as $asset)
        {
            $object = Asset::blank();
            $object = $object->createFromModel($asset);
            $return['objects'][] = $object;
            unset($object);
        }                           
        $return = array_merge($return, $stats);
        return $return;
    }

    /**
     * Get the videos for the given merchant;
     *
     * @param  MerchantRepository $merchant
     * @return array Merchant videos.
     */
    public function getMerchantVideos(MerchantRepository $merchant)
    {
        $query = SOE\DB\Asset::where('assetable_type', '=', 'Merchant')
                            ->where('assetable_id', '=', $merchant->id)
                            ->where('type', '=', 'video');
        $stats = $this->getStats(clone $query, 0, 0);
        $assets = $query->get();
        $stats['stats']['returned'] = count($assets);
        $return = array('objects' => array());
        foreach($assets as $asset)
        {
            $object = Asset::blank();
            $object = $object->createFromModel($asset);
            $return['objects'][] = $object;
            unset($object);
        }                           
        $return = array_merge($return, $stats);
        return $return;
    }

    /***** API METHODS *****/

    /**
     * Retrieve a logo for a merchant based on merchant_id.
     *
     * @api
     *
     * @return mixed A merchant logo.
     */
    public function apiGetLogo()
    {
        $merchant_id = Input::get('merchant_id', 0);
        $merchant = Merchant::find($merchant_id);
        if(empty($merchant))
            return;
        return $this->format($this->getLogo($merchant));
    }

    /**
     * Retrieve the gallery images for the given merchant based on merchant_id.
     *
     * @api
     *
     * @return mixed A formatted array of merchant images.
     */
    public function apiGetMerchantImages()
    {
        $merchant_id = Input::get('merchant_id', 0);
        $merchant = Merchant::find($merchant_id);
        if(empty($merchant))
            return;
        return $this->format($this->getMerchantImages($merchant));
    }

    /**
     * Retrieve the pdfs for the given merchant based on merchant_id.
     *
     * @api
     *
     * @return mixed A formatted array of merchant images.
     */
    public function apiGetMerchantPdfs()
    {
        $merchant_id = Input::get('merchant_id', 0);
        $merchant = Merchant::find($merchant_id);
        if(empty($merchant))
            return;
        return $this->format($this->getMerchantPdfs($merchant));
    }

    /**
     * Retrieve the pdfs for the given merchant based on merchant_id.
     *
     * @api
     *
     * @return mixed A formatted array of merchant images.
     */
    public function apiGetMerchantVideos()
    {
        $merchant_id = Input::get('merchant_id', 0);
        $merchant = Merchant::find($merchant_id);
        if(empty($merchant))
            return;
        return $this->format($this->getMerchantVideos($merchant));
    }
}