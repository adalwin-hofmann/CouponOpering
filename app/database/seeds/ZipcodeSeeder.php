<?php

class ZipcodeSeeder extends ApiTestSeeder
{
    public function run()
    {
        $this->setTableName('zipcodes');
        $this->prepare();

        $this->addData(
            array(
                'id' => 66535,
                'recordnumber' => 66535,
                'zipcode' => 48828,
                'zipcodetype' => 'STANDARD',
                'city' => 'DETROIT',
                'state' => 'MI',
                'locationtype' => 'PRIMARY',
                'latitude' => 42.38,
                'longitude' => '-83.10',
                'taxreturns2008' => 0.08,
                'estimatedpopulation' => 409137660,
                'latm' => 4709816.54,
                'lngm' => -6821920.8545214
            )
        );

        $this->insert();
    }
}
