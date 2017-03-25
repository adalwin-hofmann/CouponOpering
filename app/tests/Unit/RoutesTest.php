<?php

class RoutesTest extends Tests\Unit\TestCase
{
    function setUp()
    {
        $_ENV['APP_MODE'] = 'Content';
        parent::setUp();
    }

    function testApiBind()
    {
//        $this->call('GET', '/');
    }
}

