<?php
namespace Tests\Func\SOE\Repositories\Eloquent\Api;

class AssetRepositoryTest extends \Tests\Func\TestCase
{
    public function testGetLogo()
    {
        $request = array('merchant_id' => 1);
        $this->apiCallTest('GET', 'api/asset/get-logo', $request, 'getLogo');
    }

    /*

    public function testGetMerchantImages()
    {
        $response = $this->call('GET', 'asset/get-merchant-images', $request);
    }

    public function testGetMerchantPdfs()
    {
        $response = $this->call('GET', 'asset/get-merchant-pdfs', $request);
    }

    public function testGetMerchantVideos()
    {
        $response = $this->call('GET', 'asset/get-merchant-videos', $request);
    }
     */
}

