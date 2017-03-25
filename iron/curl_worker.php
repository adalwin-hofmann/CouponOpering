<?php
// Worker code can be anything you want.
echo "Starting CurlWorker at ".date('r')."\n";
echo "payload:";
$payload = getPayload();
$p = $payload->key;
$p = ($payload->key=="")?"soct.parser":"$payload->key";
switch($p)
{
    case "soct.parser":
        $url = "http://netlms.com/leadparser";
        break;
    case "soct.leadassignment":
        $url = "http://netlms.com/leadassigner";
        break;
    case "soct.leadsender":
        $url = "http://netlms.com/leadsender";
        break;
    case "soe.dashboard.monthly":
        $url = "http://saveoneverything.com/backoffice/precache/month";
        break;
    case "soe.dashboard.weekly":
        $url = "http://saveoneverything.com/backoffice/precache/week";
        break;
    case "soe.popularity.daily":
        $url = "http://saveoneverything.com/calculate-popularity";
        break;
    case "soe.autopublish":
        $url = "http://saveoneverything.com/backoffice/content/wizard/auto_publish";
        break;
    case "soe3.import":
        $url = "http://saveon.com/filesync";
        break;
    case "soe3.scheduled.rank.user":
        $url = "http://www.saveon.com/scheduled/rank?type=user";
        break;
    case "soe3.scheduled.rank.entity":
        $url = "http://www.saveon.com/scheduled/rank?type=entity";
        break;
    case "soe3.scheduled.yipit":
        $url = "http://www.saveon.com/scheduled/yipit";
        break;
    case "soe3.scheduled.warmup.entity":
        $url = "http://www.saveon.com/scheduled/warmup";
        break;
    case "soe3.scheduled.search.merchant":
        $url = "http://www.saveon.com/scheduled/search?type=merchant";
        break;
    case "soe3.scheduled.search.entity":
        $url = "http://www.saveon.com/scheduled/search?type=entity";
        break;
    case "soe3.scheduled.trial.check":
        $url = "http://www.saveon.com/scheduled/trial_check";
        break;
    case "soe3.reports.sales.weekly":
        $url = "http://www.saveon.com/reports/send-sales-emails";
        break;
    case "soe3.scheduled.search.coupons":
        $url = "http://www.saveon.com/scheduled/search?type=has_coupons";
        break;
    case "soe3.scheduled.search.with_coupons":
        $url = "http://www.saveon.com/scheduled/search?type=coupons_index";
        break;
    case "soe3.scheduled.search.without_coupons":
        $url = "http://www.saveon.com/scheduled/search?type=no_coupons_index";
        break;
    case "netlms.dispatch":
        $url = "http://netlms.com/api/v2/dispatch";
        break;
    case "soe4.scheduled.newsletters.prep":
        $url = "http://www.saveon.com/scheduled/create_newsletter_content";
        break;
    case "soe4.scheduled.newsletters.send":
        $url = "http://www.saveon.com/scheduled/send_newsletter";
        break;
    case "soe4.scheduled.soct.popularity":
        $url = "http://www.saveon.com/scheduled/calculate_soct_popularity";
        break;
    case "soe4.scheduled.soct.dt_download":
        $url = "http://saveon-stage-j6z3btscpq.elasticbeanstalk.com:8080/scheduled/dt_download";
        break;
    case "soe4.scheduled.soct.dt_dealers":
        $url = "http://saveon-stage-j6z3btscpq.elasticbeanstalk.com:8080/scheduled/dt_dealers";
        break;
    case "soe4.scheduled.soct.dt_inventory":
        $url = "http://saveon-stage-j6z3btscpq.elasticbeanstalk.com:8080/scheduled/dt_inventory";
        break;
    case "soe4.scheduled.soct.dealers":
        $url = "http://saveon-stage-j6z3btscpq.elasticbeanstalk.com:8080/scheduled/soct_dealers";
        break;
    case "soe4.scheduled.soct.inventory":
        $url = "http://saveon-stage-j6z3btscpq.elasticbeanstalk.com:8080/scheduled/soct_inventory";
        break;
    case "soe4.scheduled.soct.dealer_specialties":
        $url = "http://saveon-stage-j6z3btscpq.elasticbeanstalk.com:8080/scheduled/dealer_specialties";
        break;
    case "soe4.scheduled.soct.vauto":
        $url = "http://saveon-stage-j6z3btscpq.elasticbeanstalk.com:8080/scheduled/vauto";
        break;
    case "soe4.scheduled.soct.merge_vehicles":
        $url = "http://saveon-stage-j6z3btscpq.elasticbeanstalk.com:8080/scheduled/merge_vehicles";
        break;
    case "soe4.scheduled.cache.clean":
        $url = "http://www.saveon.com/scheduled/cache?type=clean"; 
        break;
    case "soe4.scheduled.leads.purchased":
        $url = "http://www.saveon.com/scheduled/leads_purchased"; 
        break;
    case "soe4.scheduled.contests.check":
        $url = "http://www.saveon.com/scheduled/check_winners"; 
        break;
    case "soe4.scheduled.coupons.check":
        $url = "http://www.saveon.com/scheduled/check_coupons"; 
        break;
}
curlpost($url);
echo "Curl completed at ".date('r');


///////////////////////////////////////////////////

function curlpost($sub_req_url)
{
    $ch = curl_init($sub_req_url);
    $encoded = '';
    // include GET as well as POST variables; your needs may vary.
    foreach($_GET as $name => $value) {
      $encoded .= urlencode($name).'='.urlencode($value).'&';
    }
    foreach($_POST as $name => $value) {
      $encoded .= urlencode($name).'='.urlencode($value).'&';
    }
    // chop off last ampersand
    $encoded = substr($encoded, 0, strlen($encoded)-1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,  $encoded);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_exec($ch);
    curl_close($ch);

}
