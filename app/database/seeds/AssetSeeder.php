<?php

class AssetSeeder extends ApiTestSeeder
{
    public function run()
    {
        $this->setTableName('assets');
        $this->prepare();

        $this->addData(
            array(
                'id' => 3,
                'assetable_id' => 1,
                'assetable_type' => 'Merchant',
                'path' => 'http://example.com/path/blahblah.jpg',
                'name' => 'logo1',
            )
        );

        $this->insert();
    }
}
