@extends('master.templates.master', array('width'=>'full'))

@section('city-banner')
<div class="city-banner sot banner-menu">
    <div class="city-banner-img" style="background-image: url(http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/sot_banner.jpg)">
        <div class="fade-left"></div>
        <div class="fade-right"></div>
        <div class="row">
            <div class="col-xs-12 col-md-10 col-md-offset-1">
                <div class="content-bg">
                    <form>
                        <div class="row">
                            <div class="col-sm-4">
                                <label>Going to</label>
                                <select class="form-control" id="cruise-destination" name="cruise-destination">
                                    <option value="">Show all</option>
                                    <optgroup label="Most Popular">
                                        <option value="Caribbean"> Caribbean </option>
                                        <option value="Bahamas"> Bahamas </option>
                                        <option value="Mexico"> Mexico </option>
                                        <option value="Alaska"> Alaska </option>
                                        <option value="Europe"> Europe </option>
                                        <option value="Bermuda"> Bermuda </option>
                                        <option value="Hawaii"> Hawaii </option>
                                        <option value="Canada / New England"> Canada / New England </option>
                                    </optgroup>
                                    <optgroup label="Other Destinations">
                                        <option value="Africa"> Africa </option>
                                        <option value="Arctic / Antarctic"> Arctic / Antarctic </option>
                                        <option value="Asia"> Asia </option>
                                        <option value="Australia / New Zealand"> Australia / New Zealand </option>
                                        <option value="Central America"> Central America </option>
                                        <option value="Cruise To Nowhere"> Cruise To Nowhere </option>
                                        <option value="Galapagos"> Galapagos </option>
                                        <option value="Greenland/Iceland"> Greenland/Iceland </option>
                                        <option value="Middle East"> Middle East </option>
                                        <option value="Pacific Coastal"> Pacific Coastal </option>
                                        <option value="Panama Canal"> Panama Canal </option>
                                        <option value="South America"> South America </option>
                                        <option value="South Pacific"> South Pacific </option>
                                        <option value="Tahiti"> Tahiti </option>
                                        <option value="Transatlantic"> Transatlantic </option>
                                        <option value="World Cruises"> World Cruises </option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label>Departure month</label>
                                <select class="form-control" id="cruise-departure-month" name="cruise-departure-month">
                                    <option value="">Show all</option>
                                    @for($i=0;$i<=17;$i++)
                                        <option value="{{date('FY', strtotime('+'.$i.' month'))}}">{{date('F Y', strtotime('+'.$i.' month'))}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label>&nbsp;</label>
                                <a href="{{URL::abs('/')}}/travel/cruises/search" type="submit" class="btn btn-green btn-block">Search</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('page-title')
<h1>SaveOn Cruises - How it Works</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/travel" itemprop="url"><span itemprop="title">Travel</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/travel/cruises" itemprop="url"><span itemprop="title">Cruises</span></a>
    </li>
    <li class="active">How it Works</li>
@stop

@section('subheader-content')
    <!-- Insert New Subheader Content Block Here -->
    <p>Set subheader text here.</p>
@stop

@section('body')

<div class="content-bg">

    <h2>Learn how to book a cruise and save money at the same time.</h2>

    <ol>
        <li>Find a cruise
            <ul>
                <li>Search for any of our many cruise specials</li>
            </ul>
        </li>
        <li>Book a cruise
            <ul>
                <li>Select the perfect cruise for you!</li>
            </ul>
        </li>
        <li>Refer your friends
            <ul>
                <li>Select a few of your friends to refer to us. If they sign up, you get the savings.</li>
            </ul>
        </li>
        <li>Save on your cruise!</li>
    </ol>

</div>

@stop