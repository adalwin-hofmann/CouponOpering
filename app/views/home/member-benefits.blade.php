@extends('master.templates.master', array('width'=>'full'))
@section('page-title')
<h1>Being a Member has Benefits</h1>
@stop
@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Being a Member has Benefits</li>
@stop
@section('sidebar')
<div class="panel panel-default">
    <div class="panel-heading">
      <span class="h4 hblock panel-title">
        <a data-toggle="collapse" href="#collapseOne">Explore Coupons <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
        <div class="clearfix"></div>
      </span>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in">
	    <div class="panel-body explore-links">
	      	<ul>
                 @include('master.templates.explore', array('active' => 'all', 'type' => 'coupon'))
			</ul>
	    </div>
    </div>
</div>
@stop
@section('body')
<div class="content-bg">
    <div class="row">
        <div class="col-sm-8 col-md-6">
            <div class="row">
            <div class="col-sm-6">
                  <div id="benefitsContests" class="row margin-bottom-15">
                      <div class="col-xs-4">
                        <img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/benefits-contest.jpg" class="img-responsive center-block" alt="Contests">
                      </div>
                      <div class="col-xs-8">
                        <div class="h2">Contests</div>
                        <p>Win something for nothing. All contests are free to enter and give you a chance to win big.</p>
                      </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div id="benefitsSavingCoupons" class="row margin-bottom-15">
                      <div class="col-xs-4">
                        <img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/benefits-saving-coupons.jpg" class="img-responsive center-block" alt="Saving Coupons">
                      </div>
                      <div class="col-xs-8">
                        <div class="h2">Saving Coupons</div>
                        <p>Find a coupon you like? Save and easily find it later.</p>
                      </div>
                  </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                  <div id="benefitsSavingLocations" class="row margin-bottom-15">
                      <div class="col-xs-4">
                        <img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/benefits-saving-locations.jpg" class="img-responsive center-block" alt="Saving Locations">
                      </div>
                      <div class="col-xs-8">
                        <div class="h2">Saving Locations</div>
                        <p>Whether you travel a lot or hop between different devices, you can easily keep your favorite locations saved. Just click the heart and that location is saved.</p>
                      </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div id="benefitsSharingCoupons" class="row margin-bottom-15">
                      <div class="col-xs-4">
                        <img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/benefits-sharing-coupons.jpg" class="img-responsive center-block" alt="Sharing Coupons">
                      </div>
                      <div class="col-xs-8">
                        <div class="h2">Sharing Coupons</div>
                        <p>Let your friends know more about all of your savings. You can easily share through email or Facebook.</p>
                      </div>
                  </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                  <div id="benefitsNewsletter" class="row margin-bottom-15">
                      <div class="col-xs-4">
                        <img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/benefits-newsletter.jpg" class="img-responsive center-block" alt="Stay Up to Date">
                      </div>
                      <div class="col-xs-8">
                        <div class="h2">Stay Up to Date</div>
                        <p>Stay up to date on the latest coupons, deals and contest. Get updates right to your email!</p>
                      </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div id="benefitsNewsletter" class="row margin-bottom-15">
                      <div class="col-xs-4">
                        <img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/benefits-mobile.jpg" class="img-responsive center-block" alt="Stay Up to Date">
                      </div>
                      <div class="col-xs-8">
                        <div class="h2">Access Your Coupons Anywhere</div>
                        <p>Quickly access your saved coupons and redeem them right from your mobile device.</p>
                      </div>
                  </div>
                </div>
            </div>

        </div>
        <div class="col-sm-4 col-md-6 sign-up-page">

        <div class="h1 modal-title fancy margin-bottom-10"id="signUpModalLabel">Sign Up</div>

            <?php
            $day = Input::get('SignUpDateOfBirthDay', '');
            $month = Input::get('SignUpDateOfBirthMonth', '');
            $year = Input::get('SignUpDateOfBirthYear', '');
            $gender = Input::get('SignUpGender', '');
            ?>

            <form id="signUpForm" role="form" method="post" action="/signup" onsubmit="return master_control.ValidateSignup();">
                <div class="row">
                    <div class="col-md-6">
                        <div style="position:relative;" class="form-group">
                          <input type="text" class="form-control" id="signUpFirstName" name="signUpFirstName" placeholder="* First Name" tabindex="1">
                          <div style="position:absolute; top:6px; right:10px;">
                            <button type="button" data-trigger="focus" data-content="What should we call you?" data-placement="bottom" class="popover-info btn-link"><span class="glyphicon glyphicon-question-sign"></span></button>
                          </div>
                          <span id="first_name_message" class="signup-message" style="color: red;"></span>
                        </div>

                        <div class="form-group">
                          <input type="text" class="form-control" id="signUpLastName" name="signUpLastName" placeholder="Last Name" tabindex="2">
                        </div>
                        <div style="position:relative;" class="form-group">
                          <input type="email" class="form-control" id="signUpEmail" name="signUpEmail" placeholder="* Email" tabindex="3">
                          <div style="position:absolute; top:6px; right:10px;">
                            <button type="button" data-trigger="focus" data-content="Your email address is your username." data-placement="bottom" class="popover-info btn-link"><span class="glyphicon glyphicon-question-sign"></span></button>
                          </div>
                          <span id="email_message" class="signup-message" style="color: red;"></span>
                          <div class="forgot-password-message hidden">
                            Perhaps you've <a data-dismiss="modal" data-toggle="modal" data-target="#forgotPasswordModal" href="#">forgotten your password</a>?
                          </div>
                        </div>
                        <div class="form-group">
                          <input type="text" maxlength="5" class="form-control" id="signUpLastZip" name="signUpLastZip" placeholder="* Zip Code" tabindex="4">
                          <span id="zipcode_message" class="signup-message" style="color: red;"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <input type="password" class="form-control" id="signUpPassword" name="signUpPassword" placeholder="* Password" tabindex="5">
                          <span id="password_message" class="signup-message" style="color: red;"></span>
                        </div>
                        <div class="form-group">
                          <input type="password" class="form-control" id="signUpPasswordConfirm" name="signUpPasswordConfirm" placeholder="* Confirm Password" tabindex="6">
                        </div>
                        <div class="form-group">
                            <select name="signUpGender" id="signUpGender" class="form-control" tabindex="7">
                                <option value=''>Choose Gender</option>
                                <option <?php echo $gender=='M'?'selected="selected"': '';?> value='M'>Male</option>
                                <option <?php echo $gender=='F'?'selected="selected"': '';?> value='F'>Female</option>
                            </select>
                        </div>
                        <div style="position:relative;" class="row form-group">
                          <div class="col-xs-3"><label for="">Birthday</label></div>
                          <div class="col-xs-8">
                            <select name="SignUpDateOfBirthMonth" id="SignUpDateOfBirthMonth" class="form-control inline" tabindex="8">
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
                            <select name="SignUpDateOfBirthDay" id="SignUpDateOfBirthDay" class="form-control inline" tabindex="9">
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
                            <select name="SignUpDateOfBirthYear" id="SignUpDateOfBirthYear" class="form-control inline" tabindex="10">
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
                          <div style="position:absolute; top:6px; right:15px;">
                            <button type="button" data-trigger="focus" data-content="Please enter your birthday to verify you are at least 13 years of age." data-placement="bottom" class="popover-info btn-link"><span class="glyphicon glyphicon-question-sign"></span></button>
                          </div>
                          <div id="bdate_message" class="signup-message col-xs-12" style="color: red;"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                            <select name="signUpSource" id="signUpSource" class="form-control" tabindex="11">
                                <option value=''>How Did You Hear About Us?</option>
                                <option value="magazine">Magazine</option>
                                <option value="internet">Internet</option>
                                <option value="tradeshow">Tradeshow</option>
                                <option value="business">Business</option>
                                <option value="friend-family">Friend/Family</option>
                            </select>
                        </div>
                      </div>
                </div>
                <div class="clearfix"></div>
                <p class="required-disclaimer">* These fields are required</p>
            
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                          <input id="signUpTerms" type="checkbox" tabindex="11" checked>* I have read and agree to the <a data-toggle="modal" data-target="#termsModal">terms of use</a>.
                        </label>
                    </div>
                    <span id="terms_message" class="signup-message" style="color: red;"></span>
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                          <input type="checkbox" tabindex="12" checked> I would like to receive newsletters and promotions through email.
                        </label>
                    </div>
                </div>
                <input type="hidden" id="redirectUrl" name="currentUrl" value="{{Request::url()}}"/>
                <input type="hidden" class="show-eid" name="signUpEid" value="0"/>
                <input type="hidden" class="eid-type" name="signUpType" value="0"/>
                <input type="hidden" id="signUpRedirect" name="signUpRedirect" value="true"/>
                <div class="form-group">
                    <p class="text-center"><button id="signUpButton" type="submit" class="btn btn-black btn-lg center-block" data-loading="Signing Up...">Sign Up</button></p>
                    <p class="text-center">Already a member? <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#signInModal">Sign In!</a></p>
                </div>
            </form>

        </div>
    </div>

    <!--<p class="text-center"><button type="button" class="btn btn-red center-block member-benefits" data-dismiss="modal" data-toggle="modal" data-target="#signUpModal">Become a Member Today!</button></p> -->

</div>
@stop