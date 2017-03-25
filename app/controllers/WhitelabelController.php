<?php

class WhitelabelController extends BaseController {

    public function __construct(
        CompanyPostRepositoryInterface $companyPosts,
        CompanyRepositoryInterface $companies
    )
    {
        $this->companies = $companies;
        $this->companyPosts = $companyPosts;
        parent::__construct();
    }

    public function postMerchants()
    {
        $username = Input::get('username');
        $api_key = Input::get('api_key');
        $whitelabel = $this->companies->authenticate($username, $api_key);
        if(!$whitelabel)
            return Response::json(array('error' => 'invalid username or api_key'), 401);

        if(Input::hasFile('file'))
        {
            $file = Input::file('file');
            try
            {
                $postfile = $this->companyPosts->postFile($whitelabel, 'merchants', $file);
            }
            catch(\Exception $e)
            {
                return Response::error($e, 'error uploading file');
            }

            $parser = \App::make('\SOE\Whitelabels\Parsers\CSV\Merchants');
            try
            {
                $response = $parser->parse($whitelabel->id, $postfile->path);
            }
            catch(\SOE\Whitelabels\Parsers\ParserException $e)
            {
                return Response::error($e);
            }
            
            return $response;
        }
        else
            return Response::json(array('error' => 'no file uploaded'), 500);
    }

    public function postLocations()
    {
        $username = Input::get('username');
        $api_key = Input::get('api_key');
        $whitelabel = $this->companies->authenticate($username, $api_key);
        if(!$whitelabel)
            return Response::json(array('error' => 'invalid username or api_key'), 401);

        if(Input::hasFile('file'))
        {
            $file = Input::file('file');
            try
            {
                $postfile = $this->companyPosts->postFile($whitelabel, 'locations', $file);
            }
            catch(\Exception $e)
            {
                return Response::error($e, 'error uploading file');
            }

            $parser = \App::make('\SOE\Whitelabels\Parsers\CSV\Locations');
            try
            {
                $response = $parser->parse($whitelabel->id, $postfile->path);
            }
            catch(\SOE\Whitelabels\Parsers\ParserException $e)
            {
                return Response::error($e);
            }
            
            return $response;
        }
        else
            return Response::json(array('error' => 'no file uploaded'), 400);
    }

    public function postOffers()
    {
        $username = Input::get('username');
        $api_key = Input::get('api_key');
        $whitelabel = $this->companies->authenticate($username, $api_key);
        if(!$whitelabel)
            return Response::json(array('error' => 'invalid username or api_key'), 401);

        if(Input::hasFile('file'))
        {
            $file = Input::file('file');
            try
            {
                $postfile = $this->companyPosts->postFile($whitelabel, 'offers', $file);
            }
            catch(\Exception $e)
            {
                return Response::error($e, 'error uploading file');
            }

            $parser = \App::make('\SOE\Whitelabels\Parsers\CSV\Offers');
            try
            {
                $response = $parser->parse($whitelabel->id, $postfile->path);
            }
            catch(\SOE\Whitelabels\Parsers\ParserException $e)
            {
                return Response::error($e);
            }
            
            return $response;
        }
        else
            return Response::json(array('error' => 'no file uploaded'), 400);
    }

}