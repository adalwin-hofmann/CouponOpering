<?php
$geoip = json_decode(GeoIp::getGeoIp('json'));
$features = App::make('FeatureRepositoryInterface');
$full_detroit_only = $features->findByName('full_soct_detroit_only');
$full_detroit_only = empty($full_detroit_only) ? 120 : $full_detroit_only->value;
if($full_detroit_only)
{
    $distance = GeometryHelper::getDistance($geoip->latitude, $geoip->longitude, 42.38, -83.10);
    $full_detroit_only = ($distance > $full_detroit_only || $geoip->region_name != 'MI') ? 1 : 0;
}

?>
<div class="panel panel-default">
    <div class="panel-heading">
      <span class="h4 hblock panel-title">
        <a data-toggle="collapse" class="collapsed" href="#exploreCollapse">Explore<span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
        <div class="clearfix"></div>
      </span>
    </div>
    <div id="exploreCollapse" class="panel-collapse collapse">
        <div class="panel-body explore-links">
            <ul>
                <li><a href="{{URL::abs('/')}}/cars/new/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}">New Cars</a></li>
                @if(isset($full_detroit_only) && !$full_detroit_only)
                <li><a href="{{URL::abs('/')}}/cars/used">Used Cars</a></li>
                <li><a href="{{URL::abs('/')}}/cars/auto-services">Service & Lease Specials</a></li>
                <li><a href="{{URL::abs('/')}}/cars/featured-dealers">Featured Dealers</a></li>
                @endif
                <li><a class="link-red" href="{{URL::abs('/')}}/cars/dealer-signup">Become a Participating Partner</a></li>
            </ul>
        </div>
    </div>
</div>