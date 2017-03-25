<?php namespace SOE\Services\AppEmail;

use App;
use Config;
use User;
use CustomerioUser;
use Feature;
use Offer;

/**
*
* @api
*/

class CustIoAppEmail implements AppEmailInterface
{
    public function setAttributes($email, array $attributes = array())
    {
        $feature_filters = array();
        $feature_filters[] = array('key' => 'type', 'operator' => '=', 'value' => 'config');
        $feature_filters[] = array('key' => 'entity', 'operator' => '=', 'value' => 'save');
        $feature_filters[] = array('key' => 'name', 'operator' => '=', 'value' => 'customer_io');
        $custio = Feature::get($feature_filters, 1);
        if(!empty($custio['objects']) && $custio['objects'][0]->value == 1)
        {
            $customerio_url = Config::get('integrations.customerio.base_url');
            $site_id = Config::get('integrations.customerio.siteid');
            $api_key = Config::get('integrations.customerio.key');
            $session = curl_init();

            $custio_user = CustomerioUser::findByEmail($email);
            $user = User::findByEmail($email);
            if(empty($custio_user))
            {
                $version = time() % 2;
                $custio_user = CustomerioUser::blank();
                $custio_user->email = $email;
                $custio_user->custio_created_at = empty($user) ? '' : strtotime($user->created_at);
                $custio_user->user_id = empty($user) ? 0 : $user->id;
                $custio_user->zip = empty($user) ? '' : $user->homezip;
                $custio_user->latitude = empty($user) ? 0 : $user->latitude;
                $custio_user->longitude = empty($user) ? 0 : $user->longitude;
                $custio_user->version = $version;
                $custio_user->save();
                $attributes['email'] = $email;
                $attributes['entered_custio_at'] = time();
                if(!empty($user))
                {
                    $attributes['created_at'] = strtotime($user->created_at);
                    $attributes['Type'] = strtoupper($user->type);
                    $attributes['Name'] = $user->name;
                    $attributes['zip'] = $user->homezip;
                    $attributes['latitude'] = $user->latitude;
                    $attributes['longitude'] = $user->longitude;
                }
                $attributes['version'] = $version;
            }

            $customer_id = $custio_user->id;

            curl_setopt($session, CURLOPT_URL, $customerio_url.$customer_id);
            curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($session, CURLOPT_HTTPGET, 1);
            curl_setopt($session, CURLOPT_HEADER, false);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($session, CURLOPT_VERBOSE, 1);
            curl_setopt($session, CURLOPT_POSTFIELDS,http_build_query($attributes));
            curl_setopt($session, CURLOPT_USERPWD,$site_id . ":" . $api_key);

            curl_exec($session);
            curl_close($session);
        }
    }
}