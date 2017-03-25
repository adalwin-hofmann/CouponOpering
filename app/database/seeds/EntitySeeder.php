<?php

class EntitySeeder extends ApiTestSeeder
{
    public function run()
    {
        $this->setTableName('entities');
        $this->prepare();

        // NOTE: this needs to be re-seeded every so often because
        // the function it tests is date sensitive
        $this->addData(
            array(
                'deleted_at' => null,
                'state' => 'MI',
                'category_id' => 1,
                'is_demo' => 0,
                'franchise_demo' => 0,
                'starts_year' => date('Y'),
                'starts_day' => date('z'),
                'expires_year' => date('Y')+1,
                'expires_day' => date('z')+300, // arbitrary time far in the future
                'is_active' => 1,
                'location_active' => 1,
                'franchise_active' => 1,
                'entitiable_id' => 1,
                'latm' => 4732043.14,
                'lngm' => '-6802605.24987',
            )
        );

        $this->insert();
    }
}
