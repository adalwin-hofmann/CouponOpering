<?php
$geoip = json_decode(GeoIp::getGeoIp('json'));
/*$quote_control = Feature::findByName('master_quotes_control');
$quote_control = empty($quote_control) ? 0 : $quote_control->value;*/

$quote_control = Feature::findByName('master_quotes_control');
$quote_control = empty($quote_control) ? 0 : $quote_control->value;
$detroit_quote_control = Feature::findByName('detroit_quotes_only');
$detroit_quote_control = empty($detroit_quote_control) ? 120 : $detroit_quote_control->value;
if($detroit_quote_control)
{
    $distance = GeometryHelper::getDistance($geoip->latitude, $geoip->longitude, 42.38, -83.10);
    $detroit_quote_control = ($distance < $detroit_quote_control && $geoip->region_name == 'MI') ? 1 : 0;
    $quote_control = $quote_control && $detroit_quote_control;
}
else
{
    $quote_control = $quote_control;
}
$full_detroit_only = Feature::findByName('full_soct_detroit_only');
$full_detroit_only = empty($full_detroit_only) ? 120 : $full_detroit_only->value;
if($full_detroit_only)
{
    $distance = GeometryHelper::getDistance($geoip->latitude, $geoip->longitude, 42.38, -83.10);
    $full_detroit_only = ($distance > $full_detroit_only || $geoip->region_name != 'MI') ? 1 : 0;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        @include('master.templates.header')
    </head>
    <body>
        <script>
            abs_base = "{{URL::abs('/')}}";
        </script>
        <div id="fb-root"></div>
        @include('master.templates.nav')
        <div class="page">
            @yield('full-section')
            @yield('404error')
            <div class="<?php if((isset($width)) && ($width == 'full')) {?>full-width<?php } ?> <?php if((isset($special_merchant)) && ($special_merchant == 'soct')) {?>soct<?php } ?>">
                <div class="sidebar main-menu">
                @include('master.templates.sidebar')
                @yield('sidebar')
                </div>
                <div class="sidebar user-menu-mobile user-sidebar visible-xs">
                @include('master.templates.usernav')
                </div>
                <div class="sidebar location-menu location-sidebar visible-xs">
                @include('master.templates.locationnav')
                </div>
                <div class="main-content">
                @yield('body')
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
        
        @include('master.templates.footer')
        
    </body>
    <script src="/js/jquery-1.11.2.min.js"></script>
    <!--<script src="/js/jquery-ui-datepicker.min.js"></script>-->
    <script src="/js/can.custom.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <?php $cancache = Feature::findByName('canmodels_cache_version'); ?>
    <script src="/js/canmodels.js?version={{empty($cancache) ? '0' : $cancache->value}}"></script>
    <script src="/js/jquery.placeholder.js"></script>
    <script src="/js/owl.carousel.min.js"></script>
    <script src="/js/leaflet-src.js"></script>
    <script>
        <?php
            $featureRepo = App::make('FeatureRepositoryInterface');
            $contest_event_value = $featureRepo->findByName('contest_event_value');
            $print_event_value = $featureRepo->findByName('print_event_value');
            $view_event_value = $featureRepo->findByName('view_event_value');
            $signup_event_value = $featureRepo->findByName('signup_event_value');
            $lead_event_value = $featureRepo->findByName('lead_event_value');
            $expired_default = $featureRepo->findByName('expired_default');
        ?>
        contest_event_value = "{{empty($contest_event_value) ? 1 : $contest_event_value->value;}}";
        print_event_value = "{{empty($print_event_value) ? 1 : $print_event_value->value;}}";
        view_event_value = "{{empty($view_event_value) ? 1 : $view_event_value->value;}}";
        signup_event_value = "{{empty($signup_event_value) ? 1 : $signup_event_value->value;}}";
        lead_event_value = "{{empty($lead_event_value) ? 1 : $lead_event_value->value;}}";
        expired_default = "{{empty($expired_default) ? 0 : $expired_default->value;}}"; // Update with feature
    </script>
    <?php $jscache = Feature::findByName('js_cache_version'); ?>
    <script src="/js/jscode/master.js?version={{empty($jscache) ? '0' : $jscache->value}}"></script>
    @section('code')
        {{isset($code) ? $code : ''}}
    @show
    <script src="/js/jquery-effects.min.js"></script>
    <script src="/js/modernizr.custom.js"></script>
    <script src="/js/cbpHorizontalMenu.js"></script>
    <script src="/js/addtohomescreen.js"></script>
    <script>
        // addToHomescreen.removeSession();     // use this to remove the localStorage variable
        var ath = addToHomescreen({
            //debug: 'ios',           // activate debug mode in ios emulation
            skipFirstVisit: false,  // show at first access
            startDelay: 0,          // display the message right away
            lifespan: 0,            // do not automatically kill the call out
            displayPace: 0,         // do not obey the display pace
            maxDisplayCount: 1      // do not obey the max display count
        });
        //ath.clearSession();      // reset the user session
    </script>

    <script>
    $(document).ready(function() {
        cbpHorizontalMenu.init();

        if (/Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor)) {
            $('.cbp-hrsub-inner .coupon-text .fancy').html('Print, s&ensp;ave, and s&ensp;hare.');
            $('.cbp-hrsub-inner .contest-text .fancy').html('Enter to win s&ensp;ome great prizes.');
        }

    });
    </script>
    <script>
    $(document).ready(function() {
        window.fbAsyncInit = function() {
            FB.init({
              appId      : '<?php echo Config::get("integrations.facebook.app_id") ?>',
              status     : true,
              xfbml      : true,
              version    : 'v2.1'
            });
        };

      (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = "//connect.facebook.net/en_US/sdk.js";
         fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));
    });
    </script>
    <script src="/js/respond.min.js"></script>
    <script src="/js/jscode/content-loader.js"></script>
</html>
