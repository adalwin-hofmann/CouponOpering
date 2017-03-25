<?php
namespace SOE\Api;

class VehicleStyle extends Api implements ApiInterface, VehicleStyleApi
{
    public function __construct(
        \AutoQuoteRepositoryInterface $autoQuoteRepository,
        \UserRepositoryInterface $users,
        \VehicleStyleRepositoryInterface $repository
    )
    {
        $this->autoQuoteRepository = $autoQuoteRepository;
        $this->repository = $repository;
        $this->users = $users;
    }

    public function find()
    {
        $vehicle = $this->repository->with(array('assets', 'displayImage', 'incentives' => function($query)
            {
                $query->orderBy('rebate_amount', 'desc');
            }))->where('id', \Input::get('id'))->first();
        //$vehicle->incentives = $vehicle->incentives()->orderBy('rebate_amount', 'desc')->get();
        return $this->format($vehicle);
    }

    public function create()
    {
    }

    public function get()
    {
    }

    public function update()
    {
    }

    public function search()
    {
        $ordering = \Input::get('order', 'rand');
        return $this->format($this->repository->search(
            \Input::get('year', null),
            \Input::get('make', null),
            \Input::get('model', null),
            \Input::get('min', null),
            \Input::get('max', null),
            \Input::get('page', 0),
            \Input::get('limit', 0),
            $ordering,
            \Input::get('body', null)
        ));
    }

    public function searchRelated()
    {
        $ordering = \Input::get('order', 'rand');
        return $this->format($this->repository->searchRelated(
            \Input::get('year', null),
            \Input::get('make', null),
            \Input::get('model', null),
            \Input::get('min', null),
            \Input::get('max', null),
            \Input::get('page', 0),
            \Input::get('limit', 0),
            $ordering
        ));
    }

    public function incentives()
    {
        return $this->format($this->repository->getIncentives(
            \Input::get('id'),
            \Input::get('page', 0),
            \Input::get('limit', 0)
        ));
    }

    public function getByMakeModel()
    {
        return $this->format($this->repository->getByMakeModel(
            \Input::get('make'),
            \Input::get('model')
        ));
    }

    public function newAutoQuote()
    {
        $inputs = \Input::all();
        $validator = \Validator::make(
            $inputs,
            array(
                'newQuoteVehicle' => 'required',
                'newQuoteFirst' => 'required',
                'newQuoteLast' => 'required',
                'newQuoteEmail' => 'required|email',
                'newQuotePhone' => 'required',
                'newQuoteZipcode' => 'required'
            )
        );
        if($validator->fails())
        {
            $data = new \StdClass;
            $data->data = array();
            $data->status = 'invalid';
            foreach($validator->messages()->getMessages() as $field => $messages)
            {
                $data->data[] = $field;
            }
            return json_encode($data);
        }

        $vehicle = $this->repository->find(\Input::get('newQuoteVehicle'));
        if(!\Auth::check())
        {
            try
            {
                $user = $this->users->create(array(
                    'email' => \Input::get('newQuoteEmail'),
                    'username' => \Input::get('newQuoteEmail'),
                    'type' => 'Member',
                    'name' => \Input::get('newQuoteFirst').' '.\Input::get('newQuoteLast'),
                    'zipcode' => \Input::get('newQuoteZipcode')
                ));
                \Auth::login($user);                
            }
            catch(\Exception $e)
            {
                //Unable to create user, might be a repeat
            }
        }

        $data = array(
            'quoteable_id' => \Input::get('newQuoteVehicle'),
            'quoteable_type' => 'VehicleStyle',
            'franchise_id' => \Input::get('newQuoteFranchise', 0),
            'first_name' => \Input::get('newQuoteFirst'),
            'last_name' => \Input::get('newQuoteLast'),
            'email' => \Input::get('newQuoteEmail'),
            'phone' => \Input::get('newQuotePhone'),
            'user_id' => (\Auth::check() ? \Auth::User()->id : 0),
            'zip' => \Input::get('newQuoteZipcode')
        );
        $quote = $this->autoQuoteRepository->create($data);

        $data = new \StdClass;
        $data->data = array();
        if( ! $quote )
        {
            $data->status = 'error';
            $data->error_messages = $this->autoQuoteRepository->errors()->toArray();
            return json_encode($data);
        }

        $response = $this->autoQuoteRepository->postQuote($quote);

        if(!$response['quote']->posted_at)
        {
            $data->status = 'error';
        }
        else if(isset($response['response']->id))
            $data->status = 'sent';
        else
        {
            $data->status = 'dealers';
            $data->data = $response['response']->dealers;
            $data->lead_id = $response['response']->lead_id;
            $data->seller = $response['response']->seller;
        }
        return json_encode($data);
    }

    public function newAutoQuoteDealers()
    {
        $dealers = \Input::get('dealers');
        $dealers = explode(',', $dealers);
        if(empty($dealers))
            return;
        foreach($dealers as $dealer)
        {
            $data = array(
                'dealer' => $dealer,
                'lead_id' => \Input::get('lead_id'),
                'seller' => \Input::get('seller')
            );
            $this->autoQuoteRepository->postDealer($data);
        }

        $data = new \StdClass;
        $data->data = array();
        $data->status = 'sent';
        return json_encode($data);
    }
}
