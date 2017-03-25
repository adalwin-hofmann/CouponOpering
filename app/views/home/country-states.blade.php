@extends('master.templates.master')

@section('page-title')
<h1>Offers by State</h1>
<?php $subheadDropdown = 'sort' ?>
@stop

@section('sidebar')
    <div class="panel panel-default">
        <div class="panel-heading">
          <span class="panel-title h4 hblock">
            <a data-toggle="collapse" href="#collapseOne">Explore {{!isset($type) || $type == 'coupon' ? 'Coupons' : ($type == 'dailydeal' ? 'Daily Deals' : 'Contests')}} <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
            <div class="clearfix"></div>
          </span>
        </div>
        <div id="collapseOne" class="panel-collapse">
            <div class="panel-body explore-links">
                <ul>
                    @include('master.templates.explore', array('active' => $active, 'type' => 'coupon'))
                </ul>
            </div>
        </div>
    </div>
    @if(!empty($category) && $type == 'coupon')
    <div class="panel panel-default">
        <div class="panel-heading">
          <span class="panel-title h4 hblock">
            <a data-toggle="collapse" href="#collapseTwo" class="collapsed">About {{ empty($category) ? '' : $category->name.' '}}<span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
            <div class="clearfix"></div>
          </span>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse">
            <div class="panel-body explore-links">
                <div class="category-header">
                    <p>{{$category->above_heading}}</p>
                </div> 
            </div>
        </div>
    </div>
    @endif
@stop

@section('body')

    <div class="clearfix"></div>

    <div class="merchant-results-holder">
        @if($city_image->path != '')
        <div class="region-banner" style="background-image: url('{{$city_image->path}}')">
        </div>
        <hr class="dark">
        @endif
        <p class="spaced margin-bottom-20"><strong>{{$countries[$country]['name']}}</strong></p>
        <div id="state-text">{{isset($seoContent['Page-About']) && $seoContent['Page-About'] != '' ? SoeHelper::cityStateReplace($seoContent['Page-About'], $geoip) : $city_image->about}}</div>
        <hr class="dark">
        <div class="country-result row">
            <ul class="col-sm-3">
                <?php $i=0; ?>
                @foreach($countries[$country]['states'] as $abbr => $name)
                <li><a href="{{URL::abs('/')}}/coupons/in/{{strtolower($abbr)}}">{{ucwords(strtolower($name))}}</a></li>
                @if(++$i % 15 == 0)
                </ul>
                <ul class="col-sm-3">
                @endif
                @endforeach
            </ul>
        </div>
        
    </div>
    <div class="clearfix"></div>
@stop