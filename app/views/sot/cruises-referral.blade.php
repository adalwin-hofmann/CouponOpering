@extends('master.templates.master')

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
<h1>Refer a Friend</h1>
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
    <li class="active">Refer a Friend</li>
@stop

@section('subheader-content')
    <!-- Insert New Subheader Content Block Here -->
    <p>Set subheader text here.</p>
@stop

@section('sidebar')

<div class="panel panel-default">
    <div class="panel-heading">
      <span class="h4 panel-title hblock">
        <a data-toggle="collapse" href="#collapseTwo">About SaveOn Travel<span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
        <div class="clearfix"></div>
      </span>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse in">
        <div class="panel-body explore-links">
            <div class="category-header">
                <p>SaveOn Cruises is a great way to find the cruise of your dreams for the right price. After booking a cruise, get $100 back for every person you refer. <a href="{{URL::abs('/')}}/travel/cruises/howitworks">Find out how it works.</a></p>
            </div> 
        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
      <span class="h4 hblock panel-title">
        <a data-toggle="collapse" class="" href="#questionsCollaspe">Got Questions?<span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
        <div class="clearfix"></div>
      </span>
    </div>
    <div id="questionsCollaspe" class="panel-collapse collapse in" style="height: auto;">
        <div class="panel-body explore-links">
            <div>
                <div class="col-xs-4">
                    <p>Phone</p>
                </div>
                <div class="col-xs-8">
                    <p>248.362.9119</p>
                </div>
            </div>
            <div>
                <div class="col-xs-4">
                    <p>Fax</p>
                </div>
                <div class="col-xs-8">
                    <p>248.362.2177</p>
                </div>
            </div>
            <div>
                <div class="col-xs-12">
                    <a onclick="showClassicWidget()">Send Feedback</a>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
@section('body')

<div class="content-bg">

    <h2>Congratulations, you are one step further to saving on your cruise!</h2>

    <p>You've selected the <strong>May 2 2015 7-night Western Caribbean Cruise from Miami (Roundtrip)</strong> cruise. Refer your friends for even further discounts.</p>

    <div class="row">
        <div class="col-sm-3">
            <strong>Departs:</strong><br>
            from Miami Saturday May-09-15 at 4:00 PM
        </div>
        <div class="col-sm-3">
            <strong>Returns:</strong><br>
            to Miami Saturday May-16-15 at 8:00 AM
        </div>
        <div class="col-sm-3">
            Total Price:
            <div class="h2 green">$900</div>
        </div>
    </div>

    <hr>

    <div class="row margin-bottom-5">
        <div class="col-xs-4">
            <input type="text" class="form-control" id="" placeholder="Name">
        </div>
        <div class="col-xs-4">
            <input type="email" class="form-control" id="" placeholder="Email">
        </div>
        <div class="col-sm-3">
            <a href="#" type="submit" class="btn btn-green">Send</a>
        </div>
    </div>
    <div class="row margin-bottom-5">
        <div class="col-xs-4">
            <input type="text" class="form-control" id="" placeholder="Name">
        </div>
        <div class="col-xs-4">
            <input type="email" class="form-control" id="" placeholder="Email">
        </div>
        <div class="col-sm-3">
            <a href="#" type="submit" class="btn btn-green">Send</a>
        </div>
    </div>
    <div class="row margin-bottom-5">
        <div class="col-xs-4">
            <input type="text" class="form-control" id="" placeholder="Name">
        </div>
        <div class="col-xs-4">
            <input type="email" class="form-control" id="" placeholder="Email">
        </div>
        <div class="col-sm-3">
            <a href="#" type="submit" class="btn btn-green">Send</a>
        </div>
    </div>

    <hr>

    <h2>Enter additional information so you can secure your trip.</h2>

    <div class="row margin-bottom-10">
        <div class="col-xs-4">
            <input type="text" class="form-control" id="" placeholder="First Name">
        </div>
        <div class="col-xs-4">
            <input type="text" class="form-control" id="" placeholder="Last Name">
        </div>
    </div>
    <div class="row margin-bottom-10">
        <div class="col-xs-4">
            <select class="form-control" id="" name="">
                <option value="">Citizenship</option>
                <option>United States</option>
                <option>Canada</option>
            </select>
        </div>
    </div>
    <div class="row margin-bottom-10">
        <div class="col-xs-4">
            <label>Birthday</label><br>
            <?php
            $day = Input::get('SignUpDateOfBirthDay', '');
            $month = Input::get('SignUpDateOfBirthMonth', '');
            $year = Input::get('SignUpDateOfBirthYear', '');
            $gender = Input::get('SignUpGender', '');
            ?>
            <select name="SignUpDateOfBirthMonth" id="SignUpDateOfBirthMonth" class="form-control inline" style="width: 31%;">
              <?php
                $selected = $month==''?'selected="selected"':'';
                echo ("<option $selected value=''> M </option>");
                for($i=1; $i<=12; $i++)
                {
                  $i = str_pad($i, 2, '0', STR_PAD_LEFT);
                  $spell = date('F', mktime(0,0,0,$i+1,0,0));
                  $selected = $i==$month?'selected="selected"':'';
                  echo ("<option ".$selected." value='$i'>$i</option>");
                }
              ?>
            </select>
            <select name="SignUpDateOfBirthDay" id="SignUpDateOfBirthDay" class="form-control inline" style="width: 31%;">
              <?php 
                $selected = $day==''?'selected="selected"':'';
                echo ("<option $selected value=''> D </option>");
                for($i=1; $i<=31; $i++)
                {
                  $i = str_pad($i, 2, '0', STR_PAD_LEFT);
                  $selected = $i==$day?'selected="selected"':'';
                  echo ("<option ".$selected." value='$i'>$i</option>");
                }
              ?>
            </select>
            <select name="SignUpDateOfBirthYear" id="SignUpDateOfBirthYear" class="form-control inline" style="width: 31%;">
              <?php
                $selected = $year==''?'selected="selected"':'';
                echo ("<option $selected value=''> Y </option>");
                for($i=date('Y'); $i>=1900; $i--)
                {
                  $datestring = $i.'-01-01';
                  $dateformat = date('y',strtotime($datestring));
                  $selected = $i==$year?'selected="selected"':'';
                  echo ("<option ".$selected." value='$i'>$dateformat</option>");
                }
              ?>
            </select>
        </div>
        <div class="col-xs-4">
            &nbsp;<br>
            <select name="signUpGender" id="signUpGender" class="form-control">
                <option value=''>Choose Gender</option>
                <option <?php echo $gender=='M'?'selected="selected"': '';?> value='M'>Male</option>
                <option <?php echo $gender=='F'?'selected="selected"': '';?> value='F'>Female</option>
            </select>
        </div>
    </div>
    <div class="row margin-bottom-5">
        <div class="col-xs-4">
            <input type="text" class="form-control" id="" placeholder="Phone Number">
        </div>
        <div class="col-xs-4">
            <input type="email" class="form-control" id="" placeholder="Your Email">
        </div>
    </div>

    <p>Would you like to create account so you can save your cruise and refer more friends?</p>

    <div class="row margin-bottom-10">
        <div class="col-xs-4">
            <input type="password" class="form-control" id="" placeholder="Enter a Password">
        </div>
        <div class="col-xs-4">
            <input type="password" class="form-control" id="" placeholder="Confirm the Password">
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <a href="#" type="submit" class="btn btn-green">Submit</a>
        </div>
    </div>

</div>

@stop