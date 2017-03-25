@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))

@section('page-title')
<h1>Find Inexpensive Used Cars</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/').'/cars'}}" itemprop="url"><span itemprop="title">Cars &amp; Trucks</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/cars/used" itemprop="url"><span itemprop="title">Used Cars</span></a>
    </li>
    <li class="active">Under</li>
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

    <div class="merchant-results-holder content-bg">
        <p class="spaced margin-bottom-20"><strong>Cars Under:</strong></p>
        <div class="state-result">
            
            <ul class="list-inline">
                <li><a href="{{URL::abs('/')}}/cars/used/under/20000">$20,000</a></li>
                <li><a href="{{URL::abs('/')}}/cars/used/under/10000">$10,000</a></li>
                <li><a href="{{URL::abs('/')}}/cars/used/under/5000">$5,000</a></li>
                <li><a href="{{URL::abs('/')}}/cars/used/under/3000">$3,000</a></li>
                <li><a href="{{URL::abs('/')}}/cars/used/under/1000">$1,000</a></li>
            </ul>
        </div>
        
    </div>
    <hr class="dark">
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