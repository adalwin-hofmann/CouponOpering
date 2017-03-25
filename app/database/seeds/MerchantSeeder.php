<?php

class MerchantSeeder extends ApiTestSeeder
{
    public function run()
    {
        $this->setTableName('merchants');
        $this->prepare();

        $this->addData(
            array(
                'id' => 1,
                'name' => 'Test Merchant',
            )
        );

        $this->insert();
    }
}
