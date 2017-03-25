@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))

@section('page-title')
<h1>Find Used {{(isset($make))?$make->name.' ':''}}Cars Under ${{number_format($price)}} For Sale</h1>
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
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/cars/used/under" itemprop="url"><span itemprop="title">Under</span></a>
    </li>
    @if(isset($make))
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/cars/used/under/{{$price}}" itemprop="url"><span itemprop="title">${{number_format($price)}}</span></a>
    </li>
    <li class="active">{{$make->name}}</li>
    @else
    <li class="active">${{number_format($price)}}</li>
    @endif
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

    @if(isset($make))
    <div class="merchant-results-holder content-bg">
        <p class="spaced margin-bottom-20"><strong>Filter By State:</strong></p>
        <div class="state-result row">
            <ul class="col-sm-3">
                <li><a href="{{URL::abs('/')}}/cars/used/under/{{$price}}/{{$make->slug}}/mi">Michigan</a></li>
                <li><a href="{{URL::abs('/')}}/cars/used/under/{{$price}}/{{$make->slug}}/il">Illinois</a></li>
                <li><a href="{{URL::abs('/')}}/cars/used/under/{{$price}}/{{$make->slug}}/mn">Minnesota</a></li>
            </ul>
        </div>
    </div>
    @else
    <div class="merchant-results-holder content-bg">
        <p class="spaced margin-bottom-20"><strong>Filter By Used Make:</strong></p>
        <div class="state-result row">
            <ul class="col-sm-3">
                <?php $i=0; ?>
                @foreach($makes as $make)
                <li><a href="{{URL::abs('/')}}/cars/used/under/{{$price}}/{{$make->slug}}">{{$make->name}}</a></li>
                @if(++$i % ceil(count($makes) / 4) == 0)
                </ul>
                <ul class="col-sm-3">
                @endif
                @endforeach
            </ul>
        </div>
    </div>
    @endif

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