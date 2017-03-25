<?php namespace SOE\Services\NetLMS;

class NetLMSAPI implements NetLMSAPIInterface
{
    const API_VERSION = 2;

    /**
     * Send a curl call to the NETLMS API.
     *
     * @param string    $method POST, PUT, GET, DELETE 
     * @param string    $object The api endpoint.
     * @param integer   $id Id of the object for PUT, GET, DELETE requests.
     * @param mixed     $data Array of data or json string to send with the request.
     * @return array    Array of response and status data.
     */
    public function curl($method, $object, $id = null, $data = '', $property = null)
    {
        $base_url = \Config::get('integrations.netlms.base_url');
        $base_url .= '/api/v'.self::API_VERSION.'/'.$object;
        $base_url .= $property ? '/'.$property : '';
        $base_url .= $id ? '/'.$id : '';
        $session = curl_init();
        if(is_array($data) && $method == 'GET')
            $base_url .= '?'.http_build_query($data);
        curl_setopt($session, CURLOPT_URL, $base_url);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($session, CURLOPT_VERBOSE, 1);
        if(is_array($data) && ($method == 'POST' || $method = 'PUT'))
        {
            $data = json_encode($data);
            curl_setopt($session, CURLOPT_POSTFIELDS, $data);
            curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        }

        $response = curl_exec($session);
        $status = curl_getinfo($session, CURLINFO_HTTP_CODE);
        curl_close($session);
        \DB::table('sys_logs')->insert(array(
            'created_at' => \DB::raw('NOW()'),
            'updated_at' => \DB::raw('NOW()'),
            'type' => 'netlms_api_call',
            'message' => 'Status: '.$status.' - '.$method.' - '.$base_url.' - '.(is_array($data) ? json_encode($data) : $data).' - Reponse: '.$response
        ));
        
        return array('response' => json_decode($response), 'status' => $status);
    }
}