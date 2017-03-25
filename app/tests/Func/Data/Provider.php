<?php
namespace Tests\Func\Data;

class Provider
{
    private $expected;

    public function __construct($file)
    {
        $this->json = file_get_contents($file);
        $this->expected = json_decode($this->json, true);
    }

    public function getJson($name)
    {
        return $this->expected[$name];
    }
}
