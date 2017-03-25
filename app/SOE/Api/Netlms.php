<?php
namespace SOE\Api;

class Netlms extends Api implements ApiInterface, NetlmsApi
{
    public function __construct(
        \FranchiseRepositoryInterface $franchises
    )
    {
        $this->api = \App::make('NetLMSAPIInterface');
        $this->franchises = $franchises;
    }

    public function find()
    {
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

    public function leadSearch()
    {
        $response = $this->api->curl(
            'GET', 
            'lead', 
            null, 
            array(
                'name' => \Input::get('name'), 
                'key' => \Config::get('integrations.netlms.key')), 
            'search'
        );
        if($response['status'] != 200)
        {
            return $this->format(array());
        }

        $return = array('objects' => $response['response']);
        return $this->format($return);
    }

    public function leadAssign()
    {
        $franchise = $this->franchises->find(\Input::get('franchise_id'));
        $response = $this->api->curl(
            'POST',
            'lead',
            null,
            array(
                'lead_id' => \Input::get('lead_id'),
                'key' => \Config::get('integrations.netlms.key'),
                'customer_key' => $franchise->netlms_id
                ),
            'assign'
        );

        return $this->format(array(array('status' => $response["status"])));
    }

    public function leadReport()
    {
        $franchise = $this->franchises->find(\Input::get('franchise_id'));
        $data = array(
            'start' => date('Y-m-d 00:00:00', strtotime(\Input::get('start', date('Y-m-1 00:00:00')))), 
            'end' => date('Y-m-d 23:59:59', strtotime(\Input::get('end', date('Y-m-d 23:59:59'))))
        );
        $response = $this->api->curl(
            'GET', 
            'report', 
            $franchise->netlms_id, 
            $data, 
            'leads'
        );

        $leads = $response['response'];
        $objects = array();

        foreach($leads as $category => $data)
        {
            $objects[] = array('category' => $category, 'leads' => $data);
        }

        return $this->format(array('objects' => $objects));
    }

}
