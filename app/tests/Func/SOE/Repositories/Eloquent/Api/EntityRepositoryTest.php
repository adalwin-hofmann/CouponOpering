<?php
namespace Tests\Func\SOE\Repositories\Eloquent\Api;

class EntityRepositoryTest extends \Tests\Func\TestCase
{
    public function testGetByCategory()
    {
        // Test Parent // category->parent_id == 0
        $request = array(
            'category_id' => 1,
            'city' => 'DETROIT',
            'state' => 'MI',
            'limit' => 1
        );

        // Test Child // category->parent_id != 0

        // TODO: this call shouldn't fail if the REMOTE_ADDR isn't set.
        $_SERVER['REMOTE_ADDR'] = "173.14.51.137";

        $this->apiCallTest('GET', 'api/entity/get-by-category', $request, 'getByCategory');
    }

    public function testGetFeatured()
    {

    }
}

