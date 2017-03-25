@extends('master.templates.master')
@section('page-title')
<h1>My Cars</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">My Cars</li>
@stop

@section('sidebar')

    <div class="panel panel-default explore-sidebar">
        <div class="panel-heading">
          <span class="h4 hblock panel-title">
            <a data-toggle="collapse" href="#collapseOne" class="collapsed">Explore Coupons <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
            <div class="clearfix"></div>
          </span>
        </div>
        <div id="collapseOne" class="panel-collapse collapse">
            <div class="panel-body explore-links">
                <ul>
                    @include('master.templates.explore', array('active' => 'all', 'type' => 'coupon'))
                </ul>
            </div>
        </div>
    </div>
    @include('master.templates.member-sidebar')
    @include('master.templates.sidebar-offers')
    <div class="ad"><-- Insert Image Here --></div>

@stop
@section('body')
<script>
    currentPage = 0;
</script>

<script type="text/ejs" id="template_car">
<% list(vehicles, function(vehicle){ 
if(vehicle.favoritable_type == 'VehicleStyle'){ %>
    <%== can.view.render('template_new_car', {vehicles: [vehicle.favoritable]}); %>
<% }else{ %>
    <%== can.view.render('template_grid_vehicle', {vehicles: [vehicle.favoritable]}); %>
<% }}); %>
</script>

<div>
    <div id="container" class="js-masonry soct-masonry">
        <p class="ajax-loader"><img src="/img/loader-transparent.gif" alt=""></p>
    </div>

    <div class="">
        <button class="btn btn-block btn-lg btn-green view-more center-block" data-loading-text="Loading...">View More</button>
    </div>
    <div class="no-saved-offers">
        <p class="spaced"><strong>You currently have 0 saved vehicles!</strong></p>
        <p>Find some vehicles you may like <a href="{{URL::abs('/')}}/cars">here</a>.</p>
    </div>
</div>
@stop