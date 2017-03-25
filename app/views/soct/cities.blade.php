@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))

@section('page-title')
@if ($page_type == 'used')
<h1>Used Cars for Sale in {{ucwords(strtolower($stateName))}}</h1>
@else
<h1>{{$page_type == 'used' || $page_type == 'new' ? ucwords($page_type).' Cars' : ($page_type == 'auto-services' ? 'Service & Lease Specials' : 'Featured Dealers')}} by City</h1>
@endif
@stop

@section('breadcrumbs')
    @include('soct.templates.breadcrumbs')
@stop

@section('body')

<script>
    type = "{{$page_type}}";
</script>

<script type="text/ejs" id="template_recommendation">
<% list(entities, function(entity){
    if(type == 'new')
    { %>
        <%== can.view.render('template_new_car', {vehicles: [entity]}); %>
    <% }
    else if(type == 'used')
    { %>
        <%== can.view.render('template_grid_vehicle', {vehicles: [entity]}); %>
    <% }
 }); %>
</script>
    
    <div class="clearfix"></div>

    <div class="merchant-results-holder">
        @if(!empty($city_image))
        @if($city_image->path != '')
        <div class="region-banner" style="background-image: url('{{$city_image->path}}')">
        </div>
        <hr class="dark">
        @endif
        @endif
        <p class="spaced margin-bottom-20"><strong>{{$stateName}}</strong> - Find Local Used Cars</p>
        <div id="state-text">@if(!empty($city_image)){{isset($seoContent['Page-About']) && $seoContent['Page-About'] != '' ? SoeHelper::cityStateReplace($seoContent['Page-About'], $geoip) : $city_image->about}}@endif</div>
        <hr class="dark">
        <div class="state-result row">
            <ul class="col-sm-3">
                <?php $i=0; ?>
                @foreach($cities['objects'] as $city)
                <li><a href="{{URL::abs('/')}}/cars/{{$page_type}}/{{strtolower($city->state)}}/{{SoeHelper::getSlug(strtolower($city->city))}}">{{ucwords(strtolower($city->city))}}</a></li>
                @if(++$i % ceil(count($cities['objects']) / 4) == 0)
                </ul>
                <ul class="col-sm-3">
                @endif
                @endforeach
            </ul>
        </div>
        
    </div>
    <div class="clearfix"></div>
    @if($page_type == 'used' || $page_type == 'new')
    <div id="container" class="js-masonry soct-masonry" style="position: relative;">
        @foreach($vehicles as $vehicle)
            @if($page_type == 'used' && isset($vehicle->merchant))
                @include('soct.templates.grid-vehicle-entity', array('vehicle'=>$vehicle))
            @elseif($page_type == 'new')
                @include('soct.templates.new-car', array('vehicle'=>$vehicle))
            @endif
        @endforeach
    </div>

    <div class="clearfix"></div>
        @if(count($vehicles))
        <div>
            <a href="{{$moreLink}}" class="btn btn-block btn-lg btn-grey view-more center-block" data-loading-text="Loading...">View More</a>
        </div>
        @endif
    @endif
@stop