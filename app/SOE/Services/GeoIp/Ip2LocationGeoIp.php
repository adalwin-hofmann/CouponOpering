<?php namespace SOE\Services\GeoIp;

use App;
use Auth;
use Config;
use Session;
use Cookie;
use ip2location;
use DB;
use SOE\DB\Ipcache as IPCache;
use GeometryHelper;
use GeoIp2\WebService\Client;

class StdClass{}

/**
*
* @api
*/
class Ip2LocationGeoIp implements GeoIpInterface
{
    public function getGeoIP($output = 'js', $use_current = false, UserRepository $user = null)
    {
        $ip = $this->getIp();
        if($user)
        {
            $ip = ($user->ip != "") ? $user->ip : $ip;
        }
        if(isset($_GET["ip"])){$ip = $_GET["ip"];}
        $userLocations = App::make('UserLocationRepositoryInterface');

        //Check if we have cached the IP Address Info
        $ipc_record = DB::table('ipcache')->where('ipaddress', $ip)->first();
        if($ipc_record=="" && $ip!='127.0.0.1')
        {
            // This creates a Client object that can be reused across requests.
            // Replace "42" with your user ID and "license_key" with your license
            // key.
            $client = new Client(86612, 'BBn3ahx1S1TI');

            // Replace "city" with the method corresponding to the web service that
            // you are using, e.g., "country", "cityIspOrg", "omni".
            try
            {
                $record = $client->city($ip);
            }
            catch(\GeoIp2\Exception\AddressNotFoundException $e)
            {
                $record = new StdClass;
                $record->city = new StdClass;
                $record->location = new StdClass;
                $record->postal = new StdClass;
                $record->mostSpecificSubdivision = new StdClass;
                $record->country = new StdClass;
                $record->city->name ="Troy";
                $record->mostSpecificSubdivision->isoCode ="MI";
                $record->location->latitude = "42.58";
                $record->location->longitude = "-83.14";
                $record->postal->code = "48084";
                $record->country->isoCode = 'US';
            }
            catch(\GeoIp2\Exception\InvalidRequestException $e)
            {
                $record = new StdClass;
                $record->city = new StdClass;
                $record->location = new StdClass;
                $record->postal = new StdClass;
                $record->mostSpecificSubdivision = new StdClass;
                $record->country = new StdClass;
                $record->city->name ="Troy";
                $record->mostSpecificSubdivision->isoCode ="MI";
                $record->location->latitude = "42.58";
                $record->location->longitude = "-83.14";
                $record->postal->code = "48084";
                $record->country->isoCode = 'US';
            }
             //Save Info to ip cache table
            $ipc_record = new Ipcache();
            $ipc_record->ipaddress     = $ip;
            $ipc_record->latitude      = $record->location->latitude;
            $ipc_record->longitude     = $record->location->longitude;
            $ipc_record->city          = $record->city->name;
            $ipc_record->postalcode    = $record->postal->code;
            $ipc_record->state         = $record->mostSpecificSubdivision->isoCode;
            $ipc_record->country       = $record->country->isoCode;

            //Check if city is blank. Default to one of our markets based upon state.
            if($ipc_record->city =="")
            {
                switch($ipc_record->state)
                {
                    case "MI":$ipc_record->city ="Troy";break;
                    case "IL":$ipc_record->city ="Chicago";break;
                    case "MN":$ipc_record->city ="Minneapolis";break;
                    default:
                        // Force User to reside in default city
                        $ipc_record->city ="Troy";
                        $ipc_record->state ="MI";
                        $ipc_record->latitude = "42.58";
                        $ipc_record->longitude = "-83.14";
                        $ipc_record->postalcode = "48084";
                        break;
                }
            }

            try
            {
                $ipc_record->save();
            }
            catch(\Exception $e)
            {
                $ipc_record = $ipc_record;
            }
        }

        // If on a local environment, use local IP2Location functionality
        $env = App::environment();
        if($env == 'local' || $ip=='127.0.0.1')
        {

            $response = $this->getLocalGeoIP($output, $use_current);
            return $response;
        }

        $record = new StdClass();

        $record->region = $ipc_record->state;
        $record->city = $ipc_record->city;
        $record->latitude = $ipc_record->latitude;
        $record->longitude = $ipc_record->longitude;

        if(($current_location = Cookie::get('current_location')))
        {
            $record->region = $current_location['region_name'];
            $record->city = $current_location['city_name'];
            $record->latitude = $current_location['latitude'];
            $record->longitude = $current_location['longitude'];
        }
        else if($user)
        {
            $fav = $userLocations->getMostRecent($user);
            if($fav)
            {
                $record->region = $fav->state;
                $record->city = $fav->city;
                $record->latitude = $fav->latitude;
                $record->longitude = $fav->logitude;
            }
        }

        if($output == 'json')
        {
            $response = array('region_name' => $record->region , 'latitude' => $record->latitude , 'longitude' => $record->longitude , 'city_name' => $record->city );
            $response = json_encode($response);
        }
        else
        {
            $response = $this->buildJsResponse($record->city, $record->region, $record->latitude, $record->longitude);
        }

        return $response;
    }

    public function getMarket()
    {
        $aMarkets = array('CHI' => array('lat' => 41.83, 'lng' => -87.68, 'radius' => 50),
                        'DET' => array('lat' => 42.38, 'lng' => -83.1, 'radius' => 35),
                        'TWIN' => array('lat' => 44.96, 'lng' => -93.16, 'radius' => 40)
                    );
        $geoip = json_decode($this->getGeoIP('json'));
        $my_market = '';
        foreach($aMarkets as $market => $data)
        {
            $dist = GeometryHelper::getDistance($geoip->latitude, $geoip->longitude, $data['lat'], $data['lng']);
            if($dist < $data['radius'])
            {
                $my_market = $market;
            }
        }
        return $my_market == '' ? 'NAT' : $my_market;
    }

    public function getIp()
    {
        $ip = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'UNKNOWN';

        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        return $ip;
    }

    protected function getLocalGeoIP($output = 'js', $use_current = false, UserRepository $user = null)
    {
        $states = array(
            "alabama" => "AL",
            "alaska" => "AK",
            "arizona" => "AZ",
            "arkansas" => "AR",
            "california" => "CA",
            "colorado" => "CO",
            "connecticut" => "CT",
            "delaware" => "DE",
            "district of columbia" => "DC",
            "florida" => "FL",
            "georgia" => "GA",
            "hawaii" => "HI",
            "idaho" => "ID",
            "illinois" => "IL",
            "indiana" => "IN",
            "iowa" => "IA",
            "kansas" => "KS",
            "kentucky" => "KY",
            "louisiana" => "LA",
            "maine" => "ME",
            "montana" => "MT",
            "nebraska" => "NE",
            "nevada" => "NV",
            "new hampshire" => "NH",
            "new jersey" => "NJ",
            "new mexico" => "NM",
            "new york" => "NY",
            "north carolina" => "NC",
            "north dakota" => "ND",
            "ohio" => "OH",
            "oklahoma" => "OK",
            "oregon" => "OR",
            "maryland" => "MD",
            "massachusetts" => "MA",
            "michigan" => "MI",
            "minnesota" => "MN",
            "mississippi" => "MS",
            "missouri" => "MO",
            "pennsylvania" => "PA",
            "rhode island" => "RI",
            "south carolina" => "SC",
            "south dakota" => "SD",
            "tennessee" => "TN",
            "texas" => "TX",
            "utah" => "UT",
            "vermont" => "VT",
            "virginia" => "VA",
            "washington" => "WA",
            "west Virginia" => "WV",
            "wisconsin" => "WI",
            "wyoming" => "WY"
        );

        $ip = $this->getIp();
        if($user)
        {
            $ip = ($user->ip != "") ? $user->ip : $ip;
        }

        $oIP = new ip2location();
        $oIP->open(app_path().'/storage/database/IP-COUNTRY-REGION-CITY-LATITUDE-LONGITUDE.BIN');
        $record = $oIP->getAll(isset($_GET['ip']) ? $_GET['ip'] :$ip);

        if(!empty($record))
        {
            if($record->latitude == 0 && $record->longitude == 0)
            {
                $record->region = 'michigan';
                $record->city = 'Troy';
                $record->latitude = 42.58;
                $record->longitude = -83.14;
            }

            if(array_key_exists(strtolower($record->region), $states))
            {
                $record->region = $states[strtolower($record->region)];
            }
            else
            {
                $record->region = '';
            }

            if(($current_location = Cookie::get('current_location')))
            {
                $record->region = $current_location['region_name'];
                $record->city = $current_location['city_name'];
                $record->latitude = $current_location['latitude'];
                $record->longitude = $current_location['longitude'];
            }
            else if($user)
            {
                $fav = $userLocations->getMostRecent($user);
                if($fav)
                {
                    $record->region = $fav->state;
                    $record->city = $fav->city;
                    $record->latitude = $fav->latitude;
                    $record->longitude = $fav->logitude;
                }
            }

            $zip = $user ? $user->zipcode : '';
            if ($output == 'json') {
                $attrs = array('region_name' => $record->region, 'latitude' => $record->latitude, 'longitude' => $record->longitude, 'city_name' => $record->city);
                $response = json_encode($attrs);
            } else {
                $response = $this->buildJsResponse($record->city, $record->region, $record->latitude, $record->longitude, $zip);
            }
            return $response;
        }
    }

    private function generateUseGpsFunc()
    {
        $features = new \EloquentFeatureRepository();
        $detect = new \Mobile_Detect;
        if ($features->mobileGpsEnabled() && $detect->isMobile()) {
            return 'geoip_use_gps = function() {return true;};';
        } else {
            return 'geoip_use_gps = function() {return false;};';
        }
    }

    private function buildJsResponse($city, $region, $latitude, $longitude, $zip = '')
    {
        $mobile = $this->generateUseGpsFunc();
        $script = <<<JAVASCRIPT
<script type="text/javascript">
geoip_city = function() {return "{$city}"};
geoip_region = function() {return "{$region}";};
geoip_latitude = function() {return {$latitude};};
geoip_longitude = function() {return {$longitude};};
geoip_postal_code = function() {return "{$zip}";};
{$mobile}
</script>
JAVASCRIPT;

        return $script;
    }
}
