<?php
namespace Tests\Func;

class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        $unitTesting = true;

        $_ENV['APP_MODE'] = 'Content';
        $testEnvironment = 'testing';

        return require __DIR__ . '/../../../bootstrap/start.php';
    }

    public function setUp()
    {
        parent::setUp();

        // Determine if this test is a repository test
        // Instantiate the data provider if it is
        $this_class = get_class($this);
        $repo_class_path = "Tests\\Func\\SOE\\Repositories\\Eloquent\\Api\\";

        if (strstr($this_class, $repo_class_path)) {
            $name = str_replace($repo_class_path, "", $this_class);
            $file = __DIR__ ."/Data/$name" . '.json';
            $this->provider = new \Tests\Func\Data\Provider($file);
        }
    }

    /**
     * Test API Calls
     *
     * @param $type string GET or POST
     * @param $url string the route (eg, api/asset/get-logo)
     * @param $request array the request sent to the route
     * @param $json_label string used to identify the label in the json file
     */
    protected function apiCallTest($type, $url, $request, $json_label)
    {
        ob_start();
        $this->call($type, $url, $request);
        $response = ob_get_clean();
        $response = json_decode($response, true);

        $this->assertEquals($this->provider->getJson($json_label), $response);
    }
}
