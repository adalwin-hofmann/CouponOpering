<?php

class CategorySeeder extends ApiTestSeeder
{
    public function run()
    {
        $this->setTableName('categories');
        $this->prepare();

        $this->addData(
            array(
                'id' => 1,
                'name' => 'Category Name',
                'slug' => '',
                'tags' => '',
                'title' => '',
                'description' => '',
            )
        );

        $this->insert();
    }
}
