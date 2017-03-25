<?php

interface AssetRepository
{
    public function getLogo(MerchantRepository $merchant);

    public function getMerchantImages(MerchantRepository $merchant);

    public function getMerchantPdfs(MerchantRepository $merchant);

    public function getMerchantVideos(MerchantRepository $merchant);
    
    /***** API METHODS *****/

    public function apiGetLogo();

    public function apiGetMerchantImages();

    public function apiGetMerchantPdfs();

    public function apiGetMerchantVideos();
}