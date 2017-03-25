<footer class="footer">
  <hr>
  <div class="row row1">
    <div class="col-md-3 col-sm-2 corporate-links">
      <span class="h4 hblock">Corporate</span>
      <ul>
        <li><a href="{{URL::abs('/')}}/faqs">FAQs</a></li>
        <li><a href="{{URL::abs('/')}}/heritage">Our Story</a></li>
        <li><a href="{{URL::abs('/')}}/whyadvertise">Advertising</a></li>
        <li><a href="{{URL::abs('/')}}/presskit">Press Kit</a></li>
        <li><a href="{{URL::abs('/')}}/news">News &amp; Views</a></li>
        <li><a href="{{URL::abs('/')}}/careers">Careers</a></li>
        <li><a href="{{URL::abs('/')}}/contact">Contact</a></li>
      </ul>
    </div>
    <div class="col-md-3 col-sm-4">
      <span class="h4 hblock">Who We Are</span>
      <p>SaveOn<sup>&reg;</sup> provides free printable coupons near you for the restaurants and businesses you love. We help you save time and money! We are a comprehensive direct mail and digital marketing company specializing in providing solutions through a variety of products including: DAL Cards, Inserts, Web and Mobile Phone applications a Direct Mail Magazine.<br>
          <a href="{{URL::abs('/')}}/heritage">Read More</a></p>
    </div>
    <div class="col-sm-3">
      <span class="h4 hblock">Save On Everything<sup>&reg;</sup></span>
      <p>Find the best local deals, coupons and offers in your area!<br>
      
      <a href="{{URL::abs('/')}}/headquarters">View on Map</a><br></p>
      <p><a href="{{URL::abs('/')}}/coupons/mi">Deals In Michigan</a></p>
      <p><a href="{{URL::abs('/')}}/coupons/il">Coupons In Chicago</a></p>
      <p><a href="{{URL::abs('/')}}/coupons/mn">Offers In Minneapolis</a></p>

      <p><a href="{{URL::abs('/')}}/commercials">Look For SaveOn<sup>&reg;</sup> on TV</a></p>
      
    </div>
    <div class="col-sm-3">
      <span class="h4 hblock">Get In Touch</span>
      <address>
      <p><strong>Headquarters:</strong><br>
        1000 W. Maple<br>
        Suite 200<br>
        Troy, Michigan 48084<br>
        Offices in Chicago and Minneapolis</p>
      <p>Phone: 800.495.5464<br>
        Fax: 248.362.2177<br>
      <a onclick="showClassicWidget()">Send Feedback</a><br></p>
      <p><a href="{{URL::abs('/')}}/suggestmerchant">Suggest a Merchant</a></p>
      <p><a class="hidden-xs tour-start hidden" onclick="tourFirst();tour.restart()">Take the tour</a></p>
      </address>
    </div>
  </div>
  @if(isset($city_desc))
      <hr>
      <div class="city-desc">
        {{$city_desc}}
      </div>
  @endif
  <hr>
  <div class="row row2">
    <div class="col-md-1 col-sm-2 col-xs-3">
      <p><a href="{{URL::abs('/')}}"><img src="{{(Session::has('new_logo'))?'/img/logo2-greyscale.png':'/img/logo-greyscale.png'}}" alt="Save On" class="img-responsive"></a>&nbsp;</p>
      <p class="visible-xs"><a target="_blank" title="Saveoneverything.com BBB Business Review" href="http://www.bbb.org/detroit/business-reviews/coupon-book-promotions/saveoneverything-com-in-troy-mi-13069127/#bbbonlineclick"><img alt="Saveoneverything.com BBB Business Review" style="border: 0;" src="http://seal-easternmichigan.bbb.org/seals/black-seal-63-134-saveoneverything-com-13069127.png" class="img-responsive" /></a></p>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-9">
      <p>&copy; {{date('Y')}} SaveOn<sup>&reg;</sup>. All Rights Reserved.</p>
      <p><a href="{{URL::abs('/')}}/terms">Terms</a> | <a href="{{URL::abs('/')}}/privacy">Privacy</a> | <a href="{{URL::abs('/')}}/sitemap">Sitemap</a></p>
    </div>
    <div class="col-md-5 hidden-sm hidden-xs">
      <p class="text-center"><a target="_blank" title="Saveoneverything.com BBB Business Review" href="http://www.bbb.org/detroit/business-reviews/coupon-book-promotions/saveoneverything-com-in-troy-mi-13069127/#bbbonlineclick"><img alt="Saveoneverything.com BBB Business Review" style="border: 0;" src="http://seal-easternmichigan.bbb.org/seals/black-seal-63-134-saveoneverything-com-13069127.png" class="img-responsive" /></a></p>
    </div>
    <div class="col-sm-4 col-md-3 col-xs-9 pull-right">
      <p><a href="http://www.facebook.com/saveoneverything"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/social-icon-facebook.png" alt="Facebook" class="img-circle"></a>
        <a href="http://twitter.com/saveonevery"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/social-icon-twitter.png" alt="Twitter" class="img-circle"></a>
        <a href="http://www.pinterest.com/saveonevery/"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/social-icon-pinterest.png" alt="Pinterest" class="img-circle"></a>
        <a href="{{URL::abs('/')}}/blog"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/social-icon-blog.png" alt="Blog" class="img-circle"></a></p>
    </div>
  </div>
</footer>

<div class="modal fade" id="signInModal" tabindex="-1" role="dialog" aria-labelledby="signInModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="signInModalLabel">Sign In</span>
      </div>
      <div class="modal-body">
        <form action="/login" method="post" role="form">
          <div class="alert alert-info alert-dismissable login-message" style="{{Session::has('signInError') ? '' : 'display:none;'}}">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <span>{{Session::has('signInError') ? 'Your login credentials are incorrect.' : ''}}</span>
          </div>
        <div class="form-group">
          <input type="email" class="form-control" id="signInEmail" name="signInEmail" placeholder="Email">
        </div>
        <div class="form-group">
          <input type="password" class="form-control" id="signInPassword" name="signInPassword" placeholder="Password">
        </div>
        <a class="pull-right" data-dismiss="modal" data-toggle="modal" data-target="#forgotPasswordModal" href="#">Forgot Password?</a>
        <div class="form-group">
          <input type="hidden" id="currentUrl" name="currentUrl" value="{{Request::url()}}"/>
          <input type="hidden" class="show-eid" name="signInEid" value="0"/>
          <input type="hidden" class="eid-type" name="signInType" value="0"/>
          <input type="hidden" id="signInRedirect" name="signInRedirect" value="false"/>
        </div>
      </div>

      <div class="modal-footer">
        <div class="form-group">
          <p class="text-center"><button type="submit" class="btn btn-lg btn-black btn-block center-block">Login</button></p>

          <p class="text-center"><button type="button" class="btn btn-red center-block sign-in-modal" data-dismiss="modal" data-toggle="modal" data-target="#signUpModal">Not a member? Sign Up for Free!</button></p>
          </div>
        </div>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="clickToCallModal" tabindex="-1" role="dialog" aria-labelledby="clickToCallModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="clickToCallModal">Click To Call</span>
      </div>
      <div class="modal-body">
          <div class="alert alert-dismissable click-to-call-message" style="display:none;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <span class="click-to-call-message-content"></span>
          </div>
        <p>
            Enter your phone number to receive a call from <span class="click-to-call-merchant"></span>.
        </p>
        <div class="form-group">
          <input type="tel" class="form-control" id="clickToCallNumber" placeholder="Phone">
        </div>
        <div class="form-group">
          <input type="hidden" id="clickToCallLocationID" value="0"/>
        </div>
      </div>

      <div class="modal-footer">
        <div class="form-group">
          <p class="text-center"><button type="button" class="btn btn-lg btn-black btn-block center-block btn-click-to-call-submit" data-loading-text="Sumitting...">Submit</button></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="forgotPasswordModalLabel">Reset Your Password</span>
      </div>
      <div class="modal-body">
        <form>
          <div class="alert alert-danger alert-dismissable no-email-alert" style="display:none">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            There is no account associated with this email.
          </div>
          <div class="alert alert-danger alert-dismissable no-email-alert-entered" style="display:none">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            You must enter an email.
          </div>
          <p>To reset your password, enter the email associated with your Save On account.</p>
          <div class="form-group email-group">
            <input type="email" class="form-control" id="signInEmail" name="signInEmail" placeholder="Email">
          </div>
          <div class="form-group hidden">
            <input type="email" class="form-control" id="" name="" placeholder="Email">
          </div>
        </div>

        <div class="modal-footer">
          <div class="form-group">
            <button type="button" class="pull-left btn btn-large btn-green">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="forgotPasswordThankYouModal" tabindex="-1" role="dialog" aria-labelledby="forgotPasswordThankYouModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="forgotPasswordThankYouModalLabel">Thank You</span>
      </div>
      <div class="modal-body">
        <span class="h2">An email was sent to <span class="user-email"></span></span>
        <p>To get back into your account, follow the instructions we've sent to your email address.</p>
        <em>Didn't receive the password reset email? Check your spam folder for an email from webmaster@saveon.com. If you still don't see the email, <a data-dismiss="modal" data-toggle="modal" data-target="#forgotPasswordModal" href="#" ><strong>Try Again.</strong></a></em>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="homeLocationModal" tabindex="-1" role="dialog" aria-labelledby="homeLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
                <span class="h1 modal-title fancy" id="homeLocationModalLabel">Edit Location</span>
            </div>
            <div class="modal-body">
                <div class="remind-location-modal">
                    <div class="form-group">
                        <label><span class="glyphicon glyphicon-home"></span> Tell Us Where Home Is</p></label>
                        <input type="text" class="form-control" id="remindLocation" name="changeLocation" placeholder="Search for a new city or zip code...">
                        <ul class="remind-location-dropdown dropdown-menu">
                        </ul>
                    </div>
                        <p class="margin-bottom-0"><strong>Saved Locations</strong></p>
                        <ul class="remind-saved-location-area">
                            <li class="dropdown-disclaimer">You have no saved locations.</li>
                        </ul>
                </div>
                <hr>
                <div class="remind-location-modal">
                  <div class="h2 block">Suggested Locations</div>
                  <div class="row">
                    <img src="http://s3.amazonaws.com/saveoneverything_assets/images/ajax-loader.gif" alt="Loading...">
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="preferencesModal" tabindex="-1" role="dialog" aria-labelledby="preferencesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
                <span class="h1 modal-title fancy" id="preferencesModalLabel">Modify Your Preferences</span>
                <p class="margin-top-20">Tell us what interests you most!</p>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <label>
                            <input class="preference" data-preference="auto_transportation_preference" type="checkbox">
                            Auto & Transportation
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label>
                            <input class="preference" data-preference="community_preference" type="checkbox">
                            Community
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label>
                            <input class="preference" data-preference="food_dining_preference" type="checkbox">
                            Food & Dining
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>
                            <input class="preference" data-preference="health_beauty_preference" type="checkbox">
                            Health & Beauty
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label>
                            <input class="preference" data-preference="home_services_preference" type="checkbox">
                            Home Improvement
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label>
                            <input class="preference" data-preference="retail_fashion_preference" type="checkbox">
                            Retail & Fashion
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>
                            <input class="preference" data-preference="special_services_preference" type="checkbox">
                            Special Services
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label>
                            <input class="preference" data-preference="travel_entertainment_preference" type="checkbox">
                            Travel & Fun
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <p class="text-center"><button class="btn btn-black btn-block center-block preferences-save" data-loading="Saving...">Save</button></p>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
$day = Input::get('SignUpDateOfBirthDay', '');
$month = Input::get('SignUpDateOfBirthMonth', '');
$year = Input::get('SignUpDateOfBirthYear', '');
$gender = Input::get('SignUpGender', '');
?>

<div class="modal fade" id="signUpModal" tabindex="-1" role="dialog" aria-labelledby="signUpModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy"id="signUpModalLabel">Sign Up</span>
        <p class="margin-top-20">Becoming a member is quick and easy! Please complete the form below:<br>
        Not convinced? <a class="inline" href="#" data-toggle="modal" data-target="#signUpBenefitsModal">Find out more about becoming a member.</a></p>
      </div>
      <div class="modal-body">
        <form id="signUpForm" role="form" method="post" action="/signup" onsubmit="return master_control.ValidateSignup();">
          <div class="row">
            <div class="col-sm-6">
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
          <div class="col-sm-6">
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
          <div class="col-sm-6">
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
        <input type="hidden" id="signUpRedirect" name="signUpRedirect" value="false"/>
        <div class="form-group">
          <p class="text-center"><button id="signUpButton" type="submit" class="btn btn-black btn-block center-block" data-loading="Signing Up...">Sign Up</button></p>
          <p class="text-center">Already a member? <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#signInModal">Sign In!</a></p>
        </div>
      </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="signUpBenefitsModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="signUpBenefitsModalLabel">Being a Member has Benefits</span>
      </div>
      <div class="modal-body">

        <div class="row">
          <div class="col-sm-6">
            <div class="row margin-bottom-15">
              <div class="col-xs-3">
                <img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/benefits-contest.jpg" class="img-responsive center-block">
              </div>
              <div class="col-xs-9">
                <div class="h2">Contests</div>
                <p>Win something for nothing. All contests are free to enter and give you a chance to win big. <a class="read-more" href="{{url('/')}}/member-benefits#benefitsContests">Read More</a></p>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="row margin-bottom-15">
              <div class="col-xs-3">
                <img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/benefits-saving-coupons.jpg" class="img-responsive center-block">
              </div>
              <div class="col-xs-9">
                <div class="h2">Saving Coupons</div>
                <p>Find a coupon you like? Save and easily find it later. <a class="read-more" href="{{url('/')}}/member-benefits#benefitsSavingCoupons">Read More</a></p>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6">
            <div class="row margin-bottom-15">
              <div class="col-xs-3">
                <img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/benefits-saving-locations.jpg" class="img-responsive center-block">
              </div>
              <div class="col-xs-9">
                <div class="h2">Saving Locations</div>
                <p>Whether you travel a lot or hop between different devices, you can easily keep your favorite locations saved. <a class="read-more" href="{{url('/')}}/member-benefits#benefitsSavingLocations">Read More</a></p>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="row margin-bottom-15">
              <div class="col-xs-3">
                <img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/benefits-sharing-coupons.jpg" class="img-responsive center-block">
              </div>
              <div class="col-xs-9">
                <div class="h2">Sharing Coupons</div>
                <p>Let your friends know more about all of your savings. <a class="read-more" href="{{url('/')}}/member-benefits#benefitsSharingCoupons">Read More</a></p>
              </div>
            </div>
          </div>
        </div>


      <p class="text-center"><button type="button" class="btn btn-red center-block signup-benefits" data-dismiss="modal" data-toggle="modal" data-target="#signUpModal">Become a Member Today!</button></p>

      </div>
    </div>
  </div>
</div>

<script type="text/ejs" id="template_nearby_modal">
  <div class="col-sm-4">
  <ul>
  <% for(var i=0;i<nearbyModal.data.length;i++)
  { %>
    <li class="other-city" data-latitude="<%= nearbyModal.data[i].latitude %>" data-longitude="<%= nearbyModal.data[i].longitude %>"  data-city="<%= nearbyModal.data[i].city %>"  data-state="<%= nearbyModal.data[i].state %>"><a><%= nearbyModal.data[i].city.toLowerCase() %>, <%= nearbyModal.data[i].state %></a></li>
    <% if(((i+1) % 3 === 0) && (i != (nearbyModal.data.length - 1)))
    {
      %>
        </ul>
        </div>
        <div class="col-sm-4">
        <ul>
      <%
    }
  } %>
  </ul>
  </div>
</script>

<script type="text/ejs" id="template_remind_modal">
  <div class="col-sm-4">
  <ul>
  <% for(var i=0;i<locations.data.length;i++)
  { %>
    <li class="remind-city" data-latitude="<%= locations.data[i].latitude %>" data-longitude="<%= locations.data[i].longitude %>"  data-city="<%= locations.data[i].city %>"  data-state="<%= locations.data[i].state %>"><a><%= master_control.UcWords(locations.data[i].city) %>, <%= locations.data[i].state %><span href="" class="glyphicon glyphicon-heart favorite pull-right"></span></a></li>
    <% if(((i+1) % 3 === 0) && (i != (locations.data.length - 1)))
    {
      %>
        </ul>
        </div>
        <div class="col-sm-4">
        <ul>
      <%
    }
  } %>
  </ul>
  </div>
</script>

<script type="text/ejs" id="template_location_modal">
  <% list(searchLocations.data, function(searchLocation)
  { %>
    <li class="other-city" data-latitude="<%= searchLocation.latitude %>" data-longitude="<%= searchLocation.longitude %>"  data-city="<%= searchLocation.city %>"  data-state="<%= searchLocation.state %>"><a><%= searchLocation.city.toLowerCase() %>, <%= searchLocation.state %></a></li>
  <% }); %>
</script>

<div class="modal fade" id="changeLocationModal" tabindex="-1" role="dialog" aria-labelledby="changeLocationModalLabel" aria-hidden="true">

</div>

<div class="modal fade offer" id="couponModal" tabindex="-1" role="dialog" aria-labelledby="couponModalLabel" aria-hidden="true" >
  <div class="modal-dialog">
    <div class="modal-content" id="printThis">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy"id="couponModalLabel">Coupon</span>
        <p class="visible-xs mobile-code"><strong>Code: <span>1211da6f</span></strong></p>
        <div id="expired-alert" class="alert alert-danger margin-top-20 hidden">
          <p><strong>Fiddle Sticks! This coupon is expired...</strong><br>Don't worry though, we have many more amazing offers.</p>
          <br>
          <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all" class="btn btn-black">See More Offers</a>
        </div>
      </div>
      <div class="modal-body">
        <div class="row visible-xs margin-bottom-20">
          <div class="col-xs-12">
              <span class="coupon-redemption-message"></span>
          </div>
          <div class="clearfix"></div>
              <div class="col-xs-4">
                <button type="button" class="btn btn-link btn-block btn-lg thumb-down btn-coupon-dislike"><img alt="Dislike" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-down.png"><br>Dislike <span class="red">(<span class="dislikes-count">4</span>)</span></button>
              </div>
              <div class="col-xs-4">
                <button type="button" class="btn btn-link btn-block btn-lg thumb-up btn-coupon-like"><img alt="Like" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-up.png"><br>Like <span class="green">(<span class="likes-count">23</span>)</span></button>
              </div>
              <div class="col-xs-4">
                <button type="button" class="btn btn-darkgrey btn-block btn-lg thumb-up btn-coupon-redeem"><img alt="Redeem" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/print-white.png"><br><span class="print-text-mobile">Redeem</span></button>
              </div>
        </div>
        <div class="printable">
            <div class="offer-info">
              <div id="printed-alert" class="alert alert-success margin-top-20 hidden">
                <p><strong>You have already printed this coupon</strong><br>You can only print a coupon once.</p>
              </div>
              <div class="member-print-alert alert alert-success margin-top-20 hidden">
                <p><strong>You must be signed in to print or redeem this coupon</strong><br>Please <a data-dismiss="modal" data-toggle="modal" data-target="#signUpModal">sign up</a> or <a data-dismiss="modal" data-toggle="modal" data-target="#signInModal">sign in</a>.</p>
              </div>
              <div class="row">
                <div class="col-xs-12">
                    <img class="img-responsive coupon-secondary-image" src="">
                </div>
              </div>
              <div class="row" id="">
              <div class="col-xs-12 col-sm-6"><img class="logo img-responsive coupon-path" src="" alt="Merchant Name" class="img-responsive" id="">
              <img class="img-responsive company-logo" src="/img/logo-small.png" alt="Save On">
              </div>
                <div class="col-xs-12 col-sm-6 border-left" id="">
                  <span class="h2 location-title hblock">Subway (Troy, MI)</span>
                  <span class="h1 coupon-title">Buy One Lunch, Get 2nd Free</span>
                  <p class="coupon-description">Max $5 discount. Must purchase 2 beverages. Dine-in only. Valid 11 am - 3 pm. Excludes holidays. Not valid with any other offers.</p>
                  <p class="expiration-date"><em><strong>Expires:</strong> <span class="coupon-expire">12-05-2013</span></em></p>
                  <p class="offer-code" style="display:none;"><strong>Offer Code: <span>1211da6f</span></strong></p>
                  <p class="coupon-code"><strong>Code: <span class="">1211da6f</span></strong></p>
                </div>
              </div>
              <div class="row default-disclaimer" id="">
                <div class="col-xs-12" id="">
                  <p class="small"><small><strong>RETAILER:</strong> Void if altered, copied, sold, purchased, transfered, exchanged or where prohibited or restricted by law. Do not accept after the expiration.<br>
                    <strong>CONSUMER:</strong> No other coupon may be used with this coupon. Consumer pays any sales tax.</small></p>
                </div>
              </div>
            </div>
        </div>
        <!--<div class="prompt visible-xs text-center" style="position: absolute; top: 350px; z-index: 1; ">
            <h1 style="color:green; display: inline; font-size:26px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Click <h1 style="color:black; display:inline; font-size:26px;">Redeem</h1></h1>
            <h1 style="color:green; font-size:26px;">To Use This Coupon</h1>
        </div>
        <div class="redemptionable visible-xs" style="-webkit-filter: blur(10px);-moz-filter: blur(10px);-o-filter: blur(10px);-ms-filter: blur(10px);filter: blur(10px);">
            <div class="offer-info">
              <div class="row">
                <div class="col-xs-12">
                    <img class="img-responsive coupon-secondary-image" src="">
                </div>
              </div>
              <div class="row" id="">
              <div class="col-xs-12 col-sm-6"><img class="logo img-responsive coupon-path" src="" alt="Merchant Name" class="img-responsive" id="">
              <img class="img-responsive company-logo" src="/img/logo-small.png" alt="Save On">
              </div>
                <div class="col-xs-12 col-sm-6 border-left" id="">
                  <span class="h2 location-title hblock">Subway (Troy, MI)</span>
                  <span class="h1 coupon-title">Buy One Lunch, Get 2nd Free</span>
                  <p class="coupon-description">Max $5 discount. Must purchase 2 beverages. Dine-in only. Valid 11 am - 3 pm. Excludes holidays. Not valid with any other offers.</p>
                  <p class="expiration-date"><em><strong>Expires:</strong> <span class="coupon-expire">12-05-2013</span></em></p>
                  <p class="offer-code" style="display:none;"><strong>Offer Code: <span>1211da6f</span></strong></p>
                  <p class="coupon-code"><strong>Code: <span class="">1211da6f</span></strong></p>
                </div>
              </div>
              <div class="row default-disclaimer" id="">
                <div class="col-xs-12" id="">
                  <p class="small"><small><strong>RETAILER:</strong> Void if altered, copied, sold, purchased, transfered, exchanged or where prohibited or restricted by law. Do not accept after the expiration.<br>
                    <strong>CONSUMER:</strong> No other coupon may be used with this coupon. Consumer pays any sales tax.</small></p>
                </div>
              </div>
            </div>
        </div>-->
        <div class="row options">
          <div class="col-xs-6">
            <button type="button" class="btn btn-block btn-default btn-coupon-clip"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/save_it_coupon.png" alt="Save It" class="img-circle"><span class="coupon-save-text">Save It</span></button>
          </div>
          <div class="col-xs-6">
            <button type="button" class="btn btn-block btn-default btn-coupon-share"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/share_it_coupon.png" alt="Share It" class="img-circle">Share It</button>
          </div>
        </div>
        <div class="row options-gray">
          <div class="hidden-xs">
            <div class="col-xs-4">
              <button type="button" class="btn btn-link btn-block btn-lg thumb-down btn-coupon-dislike"><img class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-down.png" alt="Dislike"><br>Dislike <span class="red">(<span class="dislikes-count">4</span>)</span></button>
            </div>
            <div class="col-xs-4">
              <button type="button" class="btn btn-link btn-block btn-lg thumb-up btn-coupon-like"><img class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-up.png" alt="Like"><br>Like <span class="green">(<span class="likes-count">23</span>)</span></button>
            </div>
            <div class="col-xs-4">                 
              <button type="button" class="btn btn-darkgrey btn-block btn-lg thumb-up btn-coupon-print" data-loading-text="Printing"><img class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/print-white.png" alt="Print"><br><span class="print-text">Print</span></button>
              <button type="button" class="btn btn-darkgrey btn-block btn-lg btn-mobile-only disabled" style="display:none;"><span class="glyphicon glyphicon-ban-circle"></span><br>Mobile Only<br>Offer</button>
            </div>
          </div>
            <div class="col-xs-12 footer clearfix margin-top-20 default-row">
              <div class="row">
                <div class="col-sm-6">
                  <a href="#" type="button" class="btn btn-block btn-green btn-view-locations margin-bottom-20"><span style="margin-right: 7px;" class="glyphicon glyphicon-map-marker"></span>Other Locations</a>
                </div>
                <div class="clearfix visible-xs"></div>
                <div class="col-sm-6">
                  <a href="#" type="button" class="btn btn-block btn-darkgrey btn-view-business"><span style="margin-right: 7px;" class="glyphicon glyphicon-info-sign"></span>View Business</a>
                </div>
              </div>
            </div>
            <div class="col-xs-12 footer clearfix margin-top-20 save-certified-row">
              <div class="row">
                <div class="col-sm-4">
                  <a href="#" type="button" class="btn btn-block btn-green btn-view-locations margin-bottom-20"><span style="margin-right: 7px;" class="glyphicon glyphicon-map-marker"></span>Other Locations</a>
                </div>
                <div class="clearfix visible-xs"></div>
                <div class="col-sm-4">
                  <a href="#" type="button" class="btn btn-block btn-darkgrey btn-view-business"><span style="margin-right: 7px;" class="glyphicon glyphicon-info-sign"></span>View Business</a>
                </div>

                <div class=" margin-top-20 clearfix visible-xs"></div>
                @if($quote_control)
                <div class="col-sm-4">
                  <a href="#" type="button" class="btn btn-block btn-red btn-get-quote"><span style="margin-right: 7px;" class="glyphicon glyphicon-ok-circle"></span>Get a Quote</a>
                </div>
                @endif
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade offer save-today" id="saveTodayModal" tabindex="-1" role="dialog" aria-labelledby="saveTodayModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy save-today" id="saveTodayModalLabel">Save Today</span>
        <p class="visible-xs mobile-code"><strong>Code: <span>1211da6f</span></strong></p>
        <div id="expired-alert" class="alert alert-danger margin-top-20 hidden">
          <p><strong>Fiddle Sticks! This deal is expired...</strong><br>Don't worry though, we have many more amazing offers.</p>
          <br>
          <a href="{{URL::abs('/')}}/dailydeals/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all" class="btn btn-black">See More Offers</a>
        </div>
      </div>
      <div class="modal-body save-today">
        <div class="row visible-xs margin-bottom-20">
            <div class="col-xs-12">
              <span class="coupon-redemption-message"></span>
            </div>
            <div class="col-xs-4">
              <button type="button" class="btn btn-link btn-block btn-lg thumb-down btn-coupon-dislike"><img alt="Dislike" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-down.png"><br>Dislike <span class="red">(<span class="dislikes-count">4</span>)</span></button>
            </div>
            <div class="col-xs-4">
              <button type="button" class="btn btn-link btn-block btn-lg thumb-up btn-coupon-like"><img alt="Like" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-up.png"><br>Like <span class="green">(<span class="likes-count">23</span>)</span></button>
            </div>
            <div class="col-xs-4">
              <button type="button" class="btn btn-darkgrey btn-block btn-lg thumb-up btn-coupon-redeem"><img alt="Redeem" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/print-white.png"><br>Redeem</button>
            </div>
        </div>
        <div class="printable">
            <div class="offer-info">
              <div id="printed-alert" class="alert alert-success margin-top-20 hidden">
                <p><strong>You have already printed this coupon</strong><br>You can only print a coupon once.</p>
              </div>
              <div class="member-print-alert alert alert-success margin-top-20 hidden">
                <p><strong>You must be signed in to print or redeem this coupon</strong><br>Please <a data-dismiss="modal" data-toggle="modal" data-target="#signUpModal">sign up</a> or <a data-dismiss="modal" data-toggle="modal" data-target="#signInModal">sign in</a>.</p>
              </div>
              <div class="row">
                <div class="col-xs-12">
                    <img class="img-responsive coupon-secondary-image" src="">
                </div>
              </div>
              <div class="row">
              <div class="col-xs-12"><img src="" alt="Merchant Name" class="img-responsive coupon-path"></div>
                <div class="col-xs-12 info-padding">
                  <span class="h2 location-title">Subway (Troy, MI)</span>
                  <span class="h1 coupon-title">Buy One Lunch, Get 2nd Free</span>
                  <p class="coupon-description">Max $5 discount. Must purchase 2 beverages. Dine-in only. Valid 11 am - 3 pm. Excludes holidays. Not valid with any other offers.</p>
                  <p class="expiration-date"><em><strong>Expires:</strong> <span class="coupon-expire">9h : 32m : 17s</span></em></p>
                  <p class="offer-code" style="display:none;"><strong>Offer Code: <span>1211da6f</span></strong></p>
                  <p class="coupon-code"><strong>Code: <span class="">1211da6f</span></strong></p>
                  <img class="img-responsive pull-left company-logo" src="/img/logo-small.png" alt="Save On">
                </div>
              </div>
              <div class="row default-disclaimer">
                <div class="col-xs-12">
                  <p class="small"><small><strong>RETAILER:</strong> Void if altered, copied, sold, purchased, transfered, exchanged or where prohibited or restricted by law. Do not accept after the expiration.<br>
                    <strong>CONSUMER:</strong> No other coupon may be used with this coupon. Consumer pays any sales tax.</small></p>
                </div>
              </div>
            </div>
        </div>
        <div class="row options">
          <div class="col-xs-6">
            <button type="button" class="btn btn-block btn-default btn-coupon-clip"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/save_it_save_today.png" alt="Save It" class="img-circle">Save It</button>
          </div>
          <div class="col-xs-6">
            <button type="button" class="btn btn-block btn-default btn-coupon-share"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/share_it_save_today.png" alt="Share It" class="img-circle">Share It</button>
          </div>
        </div>
        <div class="row options-gray">
          
            <div class="col-xs-4">
              <button type="button" class="btn btn-link btn-block btn-lg thumb-down btn-coupon-dislike"><img alt="Dislike" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-down.png"><br>Dislike <span class="red">(<span class="dislikes-count">4</span>)</span></button>
            </div>
            <div class="col-xs-4">
              <button type="button" class="btn btn-link btn-block btn-lg thumb-up btn-coupon-like"><img alt="Like" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-up.png"><br>Like <span class="green">(<span class="likes-count">23</span>)</span></button>
            </div>
            <div class="col-xs-4">
              <button type="button" class="btn btn-darkgrey btn-block btn-lg thumb-up btn-coupon-print" data-loading-text="Printing"><img alt="Print" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/print-white.png"><br><span class="print-text">Print</span></button>
              <button type="button" class="btn btn-darkgrey btn-block btn-lg btn-mobile-only disabled" style="display:none;"><span class="glyphicon glyphicon-ban-circle"></span><br>Mobile Only<br>Offer</button>
            </div>
            <div class="clearfix">
            </div>

            <div class="col-xs-12 footer">
              <div class="row">
                <div class="col-xs-12 truncated-about">
                  <span class="h3 spaced">About This Merchant</span>
                  <p><span class="daily-merchant-about">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam elementum diam dignissim, vestibulum neque eget, feugiat urna. </span><a class="daily-merchant-more" href="#">Read More</a></p>
                </div>
                <div class="col-xs-12 full-about">
                  <span class="h3 spaced">About This Merchant</span>
                  <p><span class="full-about-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam elementum diam dignissim, vestibulum neque eget, feugiat urna. </span></p>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-6">
                  <a href="#" type="button" class="btn btn-block btn-blue btn-view-locations"><span style="margin-right: 7px;" class="glyphicon glyphicon-map-marker"></span>View Other Locations</a>
                </div>
                <div class="col-xs-6">
                  <a href="#" type="button" class="btn btn-block btn-darkgrey btn-view-business"><span style="margin-right: 7px;" class="glyphicon glyphicon-info-sign"></span>View Business</a>
                </div>
              </div>
            </div>
            
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade offer lease" id="leaseModal" tabindex="-1" role="dialog" aria-labelledby="leaseModalLabel" aria-hidden="true" >
  <div class="modal-dialog">
    <div class="modal-content" id="printThis">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="leaseModalLabel">Lease Special</span>
        <p class="visible-xs mobile-code"><strong>Code: <span>1211da6f</span></strong></p>
        <div id="expired-alert" class="alert alert-danger margin-top-20 hidden">
          <p><strong>Fiddle Sticks! This lease special is expired...</strong><br>Don't worry though, we have many more amazing offers.</p>
          <br>
          <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all" class="btn btn-black">See More Offers</a>
        </div>
      </div>
      <div class="modal-body">
        <div class="row visible-xs margin-bottom-20">
          <div class="col-xs-12">
              <span class="coupon-redemption-message"></span>
          </div>  
          <div class="clearfix"></div>
              <div class="col-xs-4">
                <button type="button" class="btn btn-link btn-block btn-lg thumb-down btn-coupon-dislike"><img alt="Dislike" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-down.png"><br>Dislike <span class="red">(<span class="dislikes-count">4</span>)</span></button>
              </div>
              <div class="col-xs-4">
                <button type="button" class="btn btn-link btn-block btn-lg thumb-up btn-coupon-like"><img alt="Like" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-up.png"><br>Like <span class="green">(<span class="likes-count">23</span>)</span></button>
              </div>
              <div class="col-xs-4">
                <button type="button" class="btn btn-darkgrey btn-block btn-lg thumb-up btn-coupon-redeem"><img alt="Print" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/print-white.png"><br>Redeem</button>
              </div>            
        </div>
        <div class="printable">
            <div class="offer-info">
              <div id="printed-alert" class="alert alert-success margin-top-20 hidden">
                <p><strong>You have already printed this coupon</strong><br>You can only print a coupon once.</p>
              </div>
              <div class="member-print-alert alert alert-success margin-top-20 hidden">
                <p><strong>You must be signed in to print or redeem this coupon</strong><br>Please <a data-dismiss="modal" data-toggle="modal" data-target="#signUpModal">sign up</a> or <a data-dismiss="modal" data-toggle="modal" data-target="#signInModal">sign in</a>.</p>
              </div>
              <div class="row">
                <div class="col-xs-12">
                    <img class="img-responsive coupon-secondary-image" src="">
                </div>
              </div>
              <div class="row" id="">
                <div class="col-xs-12 col-sm-6"><img class="logo img-responsive coupon-path" src="" alt="Merchant Name" class="img-responsive" id="">
                    <img class="img-responsive company-logo" src="/img/logo-small.png" alt="Save On">
                    <div class="lease-contact-form">
                      <p><strong>Contact Dealer</strong></p>
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group {{$errors->has('leaseQuoteFirst') ? 'has-error' : ''}}">
                            <span class="h3">First Name</span>
                            <?php $name = Auth::check() ? explode(' ', Auth::User()->name) : array(); ?>
                            <input class="form-control leaseQuoteFirst" name="leaseQuoteFirst" placeholder="First Name *" value="{{Input::old('leaseQuoteFirst') ? Input::old('newQuoteFirst') : (isset($name[0]) ? $name[0] : '')}}">
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group {{$errors->has('leaseQuoteLast') ? 'has-error' : ''}}">
                            <span class="h3">Last Name</span>
                            <input class="form-control leaseQuoteLast" name="leaseQuoteLast" placeholder="Last Name *" value="{{Input::old('leaseQuoteLast') ? Input::old('newQuoteLast') : (isset($name[1]) ? $name[1] : '')}}">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-12">
                          <div class="form-group {{$errors->has('leaseQuoteEmail') ? 'has-error' : ''}}">
                            <span class="h3">Email</span>
                            <input class="form-control leaseQuoteEmail" name="leaseQuoteEmail" placeholder="Email *" value="{{Input::old('leaseQuoteFEmail') ? Input::old('newQuoteEmail') : (Auth::check() ? Auth::User()->email : '')}}">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group {{$errors->has('leaseQuotePhone') ? 'has-error' : ''}}">
                            <span class="h3">Phone Number</span>
                            <input class="form-control leaseQuotePhone" name="leaseQuotePhone" placeholder="Phone *" value="{{Input::old('leaseQuotePhone')}}">
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group {{$errors->has('leaseQuoteZipcode') ? 'has-error' : ''}}">
                            <span class="h3">Zipcode</span>
                            <input class="form-control leaseQuoteZipcode" name="leaseQuoteZipcode" placeholder="Zipcode *" value="{{Input::old('leaseQuoteZipcode') ? Input::old('newQuoteZipcode') : (Auth::check() ? Auth::User()->zipcode : '')}}">
                          </div>
                        </div>
                      </div>
                      <input class="leaseQuoteVehicle" name="leaseQuoteVehicle" type="hidden" value="{{Input::old('leaseQuoteVehicle')}}">
                      <button class="leaseNewQuoteSubmit btn btn-black center-block" data-modal_id="leaseModal" data-loading-text="Submitting...">Contact</button>
                    </div>
                    <div class="row text-center">
                        <div class="col-md-12">
                            <span class="leaseSubmissionMessage" style="display:none;">We are submitting your request for a quote, this may take a moment!</span>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 border-left" id="">
                  <span class="h2 location-title hblock">Subway (Troy, MI)</span>
                  <span class="h1 coupon-title">Buy One Lunch, Get 2nd Free</span>
                  <p class="coupon-description">Max $5 discount. Must purchase 2 beverages. Dine-in only. Valid 11 am - 3 pm. Excludes holidays. Not valid with any other offers.</p>
                  <p class="expiration-date"><em><strong>Expires:</strong> <span class="coupon-expire">12-05-2013</span></em></p>
                  <p class="offer-code" style="display:none;"><strong>Offer Code: <span>1211da6f</span></strong></p>
                  <p class="coupon-code"><strong>Code: <span class="">1211da6f</span></strong></p>
                </div>                
              </div>
              <div class="row default-disclaimer" id="">
                <div class="col-xs-12" id="">
                  <p class="small"><small><strong>RETAILER:</strong> Void if altered, copied, sold, purchased, transfered, exchanged or where prohibited or restricted by law. Do not accept after the expiration.<br>
                    <strong>CONSUMER:</strong> No other coupon may be used with this coupon. Consumer pays any sales tax.</small></p>
                </div>
              </div>
            </div>
        </div>
        <div class="row options">
          <div class="col-xs-6">
            <button type="button" class="btn btn-block btn-default btn-coupon-clip"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/soct/masonry-icons/save_it_new_car.png" alt="Save It" class="img-circle"><span class="coupon-save-text">Save It</span></button>
          </div>
          <div class="col-xs-6">
            <button type="button" class="btn btn-block btn-default btn-coupon-share"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/soct/masonry-icons/share_it_new_car.png" alt="Share It" class="img-circle">Share It</button>
          </div>
        </div>
        <div class="row options-gray">
          <div class="hidden-xs">
            <div class="col-xs-4">
              <button type="button" class="btn btn-link btn-block btn-lg thumb-down btn-coupon-dislike"><img class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-down.png" alt="Dislike"><br>Dislike <span class="red">(<span class="dislikes-count">4</span>)</span></button>
            </div>
            <div class="col-xs-4">
              <button type="button" class="btn btn-link btn-block btn-lg thumb-up btn-coupon-like"><img class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-up.png" alt="Like"><br>Like <span class="green">(<span class="likes-count">23</span>)</span></button>
            </div>
            <div class="col-xs-4">
              <button type="button" class="btn btn-darkgrey btn-block btn-lg thumb-up btn-coupon-print" data-loading-text="Printing"><img class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/print-white.png" alt="Print"><br><span class="print-text">Print</span></button>
              <button type="button" class="btn btn-darkgrey btn-block btn-lg btn-mobile-only disabled" style="display:none;"><span class="glyphicon glyphicon-ban-circle"></span><br>Mobile Only<br>Offer</button>
            </div>
          </div>
            <div class="col-xs-12 footer clearfix margin-top-20 default-row">
              <div class="row">
                <div class="col-sm-6">
                  <a href="#" type="button" class="btn btn-block btn-burgundy btn-view-locations margin-bottom-20"><span style="margin-right: 7px;" class="glyphicon glyphicon-map-marker"></span>Other Locations</a>
                </div>
                <div class="clearfix visible-xs"></div>
                <div class="col-sm-6">
                  <a href="#" type="button" class="btn btn-block btn-darkgrey btn-view-business"><span style="margin-right: 7px;" class="glyphicon glyphicon-info-sign"></span>View Business</a>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade offer lease" id="leaseContactModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content" id="printThis">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy">Contact Dealer</span>
      </div>
      <div class="modal-body">
        <p><img class="logo img-responsive coupon-path center-block leaseImage" src="" alt="Contact Dealer"></p>
        <div class="row text-center">
          <div class="col-xs-12">
            <span class="h3 spaced leaseQuoteTitle">Car Name</span>
          </div>
        </div>
        <div class="lease-contact-form">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group {{$errors->has('leaseQuoteFirst') ? 'has-error' : ''}}">
                <span class="h3">First Name</span>
                <?php $name = Auth::check() ? explode(' ', Auth::User()->name) : array(); ?>
                <input class="form-control leaseQuoteFirst" name="leaseQuoteFirst" placeholder="First Name *" value="{{Input::old('leaseQuoteFirst') ? Input::old('newQuoteFirst') : (isset($name[0]) ? $name[0] : '')}}">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group {{$errors->has('leaseQuoteLast') ? 'has-error' : ''}}">
                <span class="h3">Last Name</span>
                <input class="form-control leaseQuoteLast" name="leaseQuoteLast" placeholder="Last Name *" value="{{Input::old('leaseQuoteLast') ? Input::old('newQuoteLast') : (isset($name[1]) ? $name[1] : '')}}">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group {{$errors->has('leaseQuoteEmail') ? 'has-error' : ''}}">
                <span class="h3">Email</span>
                <input class="form-control leaseQuoteEmail" name="leaseQuoteEmail" placeholder="Email *" value="{{Input::old('leaseQuoteFEmail') ? Input::old('newQuoteEmail') : (Auth::check() ? Auth::User()->email : '')}}">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group {{$errors->has('leaseQuotePhone') ? 'has-error' : ''}}">
                <span class="h3">Phone Number</span>
                <input class="form-control leaseQuotePhone" name="leaseQuotePhone" placeholder="Phone *" value="{{Input::old('leaseQuotePhone')}}">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group {{$errors->has('leaseQuoteZipcode') ? 'has-error' : ''}}">
                <span class="h3">Zipcode</span>
                <input class="form-control leaseQuoteZipcode" name="leaseQuoteZipcode" placeholder="Zipcode *" value="{{Input::old('leaseQuoteZipcode') ? Input::old('newQuoteZipcode') : (Auth::check() ? Auth::User()->zipcode : '')}}">
              </div>
            </div>
          </div>
          <input class="leaseQuoteVehicle" name="leaseQuoteVehicle" type="hidden" value="{{Input::old('leaseQuoteVehicle')}}">
          <button class="leaseNewQuoteSubmit btn btn-black center-block" data-modal_id="leaseContactModal" data-loading-text="Submitting...">Contact</button>
        </div>
        <div class="row text-center">
            <div class="col-md-12">
                <span class="leaseSubmissionMessage" style="display:none;">We are submitting your request for a quote, this may take a moment!</span>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade contest" id="contestModal" tabindex="-1" role="dialog" aria-labelledby="contestModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title contest fancy" id="contestModalLabel">Contest</span>
        <div id="expired-alert" class="alert alert-danger margin-top-20 hidden">
          <p><strong>Fiddle Sticks! This contest is expired...</strong><br>Don't worry though, we have many more amazing contests.</p>
          <br>
          <a href="{{URL::abs('/')}}/contests/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all" class="btn btn-black">See More Contests</a>
        </div>
      </div>
      <div class="modal-body contest">
          <div class="top-pic margin-bottom-20">
            <img class="img-responsive contest-banner" src="" alt="Contest Name">
          </div>
          <span class="h1 contest-title"></span>
          <div class="contest-description"></div>
          <form role="form">
            <div class="row">
              <div class="col-xs-12">
                <span class="h3 spaced text-center">Confirm Your Details</span>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <span class="h3">Email</span>
                  <input type="text" class="form-control" id="contestEntryEmail" disabled="true" name="contestEntryEmail" placeholder="Email *" value="{{(Auth::check()?Auth::user()->email:'')}}">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <span class="h3">Zip Code</span>
                  <input type="text" class="form-control" id="contestEntryZip" name="contestEntryZip" placeholder="Zip Code *">
                </div>
              </div>
            </div>
            <p class="required-disclaimer">* These fields are required</p>
            <p>We hate spam as much as you do. We promise to never sell your information or use it inappropriately. For more info see our <a data-toggle="modal" data-target="#termsModal">terms</a> and <a data-toggle="modal" data-target="#privacyModal">privacy policy</a>.</p>
            <hr>
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" value="" id="contestEntryRules" name="contestEntryRules">
                  <strong class="warning">Please check the checkbox<br></strong>
                  I have read and agree to the <a data-toggle="modal" data-target="#contestRulesModal">contest rules</a>
                </label>
              </div>
            </div>
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" id="contestEmails" name="contestEmails" checked="checked">
                  I agree to receive e-mails from the sponsoring merchant
                </label>
              </div>
            </div>
            <button type="button" class="btn btn-black btn-block btn-lg center-block btn-enter-contest">ENTER CONTEST</button>
            <div class="clearfix"></div>
            <p class="text-center margin-top-20"><span class="contestMerchantLink" style="display:none;">View <a href="#">Merchant</a> | </span>Read the <a data-toggle="modal" data-target="#contestRulesModal">contest rules</a></p>
          </form>

        </div>
    </div>
  </div>
</div>

<div class="modal fade contest-thanks contest" id="contestThanksModal" tabindex="-1" role="dialog" aria-labelledby="contestThanksModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy"id="contestThanksModalLabel">Thank you!</span>
      </div>
      <div class="modal-body">
            <p>We have received your information, and we'll notify you by e-mail when we select our winner.  Good luck!</p>
      </div>
      <div class="modal-footer">
        <a type="button" class="btn btn-default pull-left" href="{{URL::abs('/')}}/contests/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all">Find More Contests</a>
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal" >Continue</button>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade contest-rules contest" id="contestRulesModal" tabindex="-1" role="dialog" aria-labelledby="contestRulesModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy"id="contestRulesModalLabel">Contest Rules</span>
      </div>
      <div class="modal-body">
        <div class="small contest-rules">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade contest" id="sweepstakesModal" tabindex="-1" role="dialog" aria-labelledby="sweepstakesModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title contest fancy" id="sweepstakesModalLabel">Win $5k</span>
      </div>
      <div class="modal-body contest external-body">
          <div class="top-pic margin-bottom-20">
            <div class="item-type contest"></div>
            <img alt="Win $5k" id="sweepstakesModalBanner" class="img-responsive sweepstakesModalBanner" src="">
          </div>
          <span class="h1">How It Works</span>
          <p id="sweepstakesModalDescExternal">If the number on the address label of your magazine matches the winning number below, that means you win $5,000! The winning number changes daily, so make sure you check back every day to see if you are a winner!</p>
          <div class="row">
            <div class="col-xs-12">
              <span class="h3 spaced text-center">The Winning Numbers</span>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-sm-12 text-center winning-numbers-div">
              <span class="label winning-numbers num_0"></span>
              <span class="label winning-numbers num_1"></span>
              <span class="label winning-numbers num_2"></span>
              <span class="label winning-numbers num_3"></span>
              <span class="label winning-numbers num_4"></span>
              <span class="label winning-numbers num_5"></span>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-sm-12 text-center">
              <p>Are you a winner? <a data-dismiss="modal" data-toggle="modal" data-target="#sweepstakesWinnerModal" href="">Click here to claim your prize</a></p>
            </div>
          </div>
        </div>
        <div class="modal-body contest internal-body">
          <div class="top-pic margin-bottom-20">
            <div class="item-type contest"></div>
            <img alt="Win $5k" class="img-responsive sweepstakesModalBanner" src="">
          </div>
          <span class="h1">How It Works</span>
          <p id="sweepstakesModalDescInternal">If your number matches the winning number, you win $5,000! The winning numbers change daily, so make sure to check back every day to see if you are a winner.</p>
          <div class="row">
            <div class="col-xs-12">
              <span class="h3 spaced text-center">The Winning Numbers</span>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-sm-12 text-center">
              <label>YOUR NUMBERS</label><br><br>
              @if(Auth::check())
              @foreach(str_split(Auth::User()->win5kid) as $num)
              <span class="label winning-numbers">{{$num}}</span>
              @endforeach
              @else
              <label><a id="win5kSignUp" href="#">Sign Up</a> to get your numbers!</label>
              @endif
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-sm-12 text-center">
              <label>WINNING NUMBERS</label><br><br>
              <div class="winning-numbers-div">
                  <span class="label winning-numbers num_0"></span>
                  <span class="label winning-numbers num_1"></span>
                  <span class="label winning-numbers num_2"></span>
                  <span class="label winning-numbers num_3"></span>
                  <span class="label winning-numbers num_4"></span>
                  <span class="label winning-numbers num_5"></span>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-sm-12 text-center">
              <p>Are you a winner? <a data-dismiss="modal" data-toggle="modal" data-target="#sweepstakesWinnerModal" href="">Click here to claim your prize</a></p>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <a href="{{URL::abs('/')}}/contests/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all" type="button" class="btn btn-black btn-block btn-lg center-block">VIEW MORE CONTESTS</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade contest" id="sweepstakesWinnerModal" tabindex="-1" role="dialog" aria-labelledby="sweepstakesWinnerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title contest fancy" id="sweepstakessweepstakesWinnerModalLabel">Win $5k</span>
      </div>
      <div class="modal-body contest">
          <span class="h1">Do you have the winning number?</span>
          <p>Fill out your information below to claim your prize! Someone will be in touch shortly to verify your information.</p>
          <div class="row">
            <div class="col-xs-12">
              <span class="h3 spaced text-center">Verify Your Info</span>
            </div>
          </div>
          <br>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <input type="firstName" class="form-control" id="firstname_sweepstakes" placeholder="First Name *">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input type="lastName" class="form-control" id="lastname_sweepstakes" placeholder="Last Name *">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <div class="form-group">
                  <input type="phone" class="form-control" id="phone_sweepstakes" placeholder="Phone Number *">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <div class="form-group">
                  <input type="address" class="form-control" id="address1_sweepstakes" placeholder="Address Line 1 *">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <div class="form-group">
                  <input type="address" class="form-control" id="address2_sweepstakes" placeholder="Address Line 2 (Optional)">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-4">
                <div class="form-group">
                  <input type="text" class="form-control" id="city_sweepstakes" placeholder="City *">
                </div>
              </div>
              <div class="col-xs-4">
                <div class="form-group">
                  <select class="form-control" name="State" id="state_sweepstakes"> 
                    <option value="" selected="selected">State *</option> 
                    <option value="AL">Alabama</option> 
                    <option value="AK">Alaska</option> 
                    <option value="AZ">Arizona</option> 
                    <option value="AR">Arkansas</option> 
                    <option value="CA">California</option> 
                    <option value="CO">Colorado</option> 
                    <option value="CT">Connecticut</option> 
                    <option value="DE">Delaware</option> 
                    <option value="DC">District Of Columbia</option> 
                    <option value="FL">Florida</option> 
                    <option value="GA">Georgia</option> 
                    <option value="HI">Hawaii</option> 
                    <option value="ID">Idaho</option> 
                    <option value="IL">Illinois</option> 
                    <option value="IN">Indiana</option> 
                    <option value="IA">Iowa</option> 
                    <option value="KS">Kansas</option> 
                    <option value="KY">Kentucky</option> 
                    <option value="LA">Louisiana</option> 
                    <option value="ME">Maine</option> 
                    <option value="MD">Maryland</option> 
                    <option value="MA">Massachusetts</option> 
                    <option value="MI">Michigan</option> 
                    <option value="MN">Minnesota</option> 
                    <option value="MS">Mississippi</option> 
                    <option value="MO">Missouri</option> 
                    <option value="MT">Montana</option> 
                    <option value="NE">Nebraska</option> 
                    <option value="NV">Nevada</option> 
                    <option value="NH">New Hampshire</option> 
                    <option value="NJ">New Jersey</option> 
                    <option value="NM">New Mexico</option> 
                    <option value="NY">New York</option> 
                    <option value="NC">North Carolina</option> 
                    <option value="ND">North Dakota</option> 
                    <option value="OH">Ohio</option> 
                    <option value="OK">Oklahoma</option> 
                    <option value="OR">Oregon</option> 
                    <option value="PA">Pennsylvania</option> 
                    <option value="RI">Rhode Island</option> 
                    <option value="SC">South Carolina</option> 
                    <option value="SD">South Dakota</option> 
                    <option value="TN">Tennessee</option> 
                    <option value="TX">Texas</option> 
                    <option value="UT">Utah</option> 
                    <option value="VT">Vermont</option> 
                    <option value="VA">Virginia</option> 
                    <option value="WA">Washington</option> 
                    <option value="WV">West Virginia</option> 
                    <option value="WI">Wisconsin</option> 
                    <option value="WY">Wyoming</option>
                  </select>

                </div>
              </div>
              <div class="col-xs-4">
                <div class="form-group">
                  <input type="text" class="form-control" id="zipcode_sweepstakes" placeholder="Zip Code *">
                </div>
              </div>
            </div>
            <div id="externalNumberInput" class="row">
              <div class="col-xs-12">
                <div class="form-group">
                    <input type="text" class="form-control" id="magazinenum_sweepstakes" placeholder="Your Winning Number *">
                  </div>
              </div>
            </div>
          <p class="required-disclaimer">* These fields are required</p>
        </div>
        <div class="modal-footer">
          <button id="submitSweepstakes" type="button" class="btn btn-black btn-block btn-lg center-block">SUBMIT</button>

          <br>
          <div class="row">
            <div class="col-sm-12 text-center">
              <p>Not a winner? <a href="{{URL::abs('/')}}/contests/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all">Go Back To Contests</a></p>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade contest" id="sweepstakesWinnerThankYouModal" tabindex="-1" role="dialog" aria-labelledby="sweepstakesWinnerThankYouModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title contest fancy" id="sweepstakesWinnerThankYouModalLabel">Win $5k</span>
      </div>
      <div class="modal-body contest">
          <span class="h1">We'll Be In Touch Soon</span>
          <p>A Save On moderator is reviewing your submission now. We'll be in touch shortly if your number matches today's winning number to confirm your winnings.</p>

        </div>
        <div class="modal-footer">
          <a href="{{URL::abs('/')}}/contests/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all" type="button" class="btn btn-black btn-block btn-lg center-block">BACK TO CONTESTS</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="firstTimeUserModal" tabindex="-1" role="dialog" aria-labelledby="firstTimeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="text-center modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <div class="h1 modal-title fancy" id="firstTimeModalLabel"><img src="{{(isset($altLogo))?$altLogo:'/img/logo.png'}}" alt="Save On" class="img-responsive center-block" width="166" height="59"></div>
      </div>
      <div class=" modal-body">
        <p>SaveOn<sup>&reg;</sup> is the best place to find <a href="{{URL::abs('/')}}/coupons/all">coupons</a><!--, <a href="{{URL::abs('/')}}/dailydeals/all">daily deals</a>,--> and <a href="{{URL::abs('/')}}/contests/all">contests</a>!</p> 
        <!--<div class="h1">Do you want to save on deals in {{$geoip->city_name}},&nbsp;{{$geoip->region_name}}? <button class="btn btn-green btn-sm search-nearby-modal" data-dismiss="modal" data-toggle="modal" data-target="#changeLocationModal"><span class="glyphicon glyphicon-map-marker"></span> Edit Location <span class="glyphicon glyphicon-chevron-right"></span></button></div>
        <p>Your location will help us customize your SaveOn experience to you! For even better, more personalized recommendations, <a class="first-time-user" data-dismiss="modal" data-toggle="modal" data-target="#signUpModal">become a member</a>.</p>-->
        <div class="row">
          <div class="col-sm-3 tour-hide">
          </div>
          <div class="col-sm-6 margin-bottom-15">
            <button class="btn btn-block btn-blue first-time-user" data-dismiss="modal" data-toggle="modal" data-target="#signUpModal">Become a Member</button>
          </div>
          <div class="col-sm-6 margin-bottom-15 tour-show hidden hidden-xs">
            <button class="btn btn-block btn-green-border" data-dismiss="modal" onclick="tour.restart()">Take the Tour</button>
          </div>
        </div>
        <p>Becoming a member is quick and easy! Not convinced?<br>
        <a class="inline" href="#" data-toggle="modal" data-target="#signUpBenefitsModal"><strong>Why become a member?</strong></a></p>
        <div class="row links-line">
            <!--<div class="col-sm-6">
                <p>Already a member? <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#signInModal">Sign In</a></p>
            </div>-->
            <div class="hidden-xs col-sm-6 tour-column hidden">
              <!--<p>New to SaveOn?<br><a href="#" data-dismiss="modal" onclick="tour.restart()">Take the tour</a></p>-->
              <p class="margin-bottom-5"><strong>Popular Offer Categories:</strong></p>
              <p><a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/food-dining" title="Food &amp; Dining Coupons in {{$geoip->city_name}}, {{$geoip->region_name}}">Food &amp; Dining Coupons</a><br>
                <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/auto-transportation" title="Automotive Coupons in {{$geoip->city_name}}, {{$geoip->region_name}}">Automotive Coupons</a><br>
                <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/health-beauty" title="Health &amp; Beauty Coupons in {{$geoip->city_name}}, {{$geoip->region_name}}">Health &amp; Beauty Coupons</a></p>
            </div>
            <div class="col-sm-12 col-xs-12">
                <p class="margin-bottom-5"><strong>Coupons in:</strong></p>
                <div class="row">
                  <div class="col-sm-6"><a href="{{URL::abs('/')}}/coupons/mi">Detroit</a></div>
                  <div class="col-sm-6"><a href="{{URL::abs('/')}}/coupons/il">Chicago</a></div>
                </div>
                <div class="row">
                  <div class="col-sm-6"><a href="{{URL::abs('/')}}/coupons/mn">Minneapolis</a></div>
                  <div class="col-sm-6"><a href="{{URL::abs('/')}}/coupons/mi/grand-rapids">Grand Rapids</a></div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal share fade share" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel" aria-hidden="true">

</div>

<div class="modal share fade share" id="shareThanksModal" tabindex="-1" role="dialog" aria-labelledby="shareThanksModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy"id="shareThanksModalLabel">Thank you!</span>
      </div>
      <div class="modal-body">
            <p>Thank you for sharing! Please continue to visit <a href="{{URL::abs('/')}}">saveon.com</a> for more great offers.</p>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal" onclick="window.location='/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all'">Find More Offers</button>
      <button type="submit" class="btn btn-default pull-right" data-dismiss="modal" onclick="window.location ='/'">Continue</button>
      <div class="clearfix"></div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="shareComingSoonModal" tabindex="-1" role="dialog" aria-labelledby="shareComingSoonModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="text-center modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="shareComingSoonModalLabel">Coming Soon</span>
      </div>
      <div class=" modal-body">
        <p>Sharing is not yet available. Check back often, because this feature is coming soon!</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade accurate-info" id="accurateInfoModal" tabindex="-1" role="dialog" aria-labelledby="accurateInfoModalLabel" aria-hidden="true">

</div>

<div class="modal fade accurate-info" id="accurateInfoThanksModal" tabindex="-1" role="dialog" aria-labelledby="accurateInfoThanksModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="accurateInfoThanksModalLabel">Thank You</span>
      </div>
      <div class="modal-body">
        <span class="h2"></span><br>
        <p>Your changes will be verified by a moderator at the Save On headquarters soon.</p>
      </div>
      <div class="modal-footer">
        <button data-dismiss="modal" class="btn btn-black">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade delete-account" id="deleteAccountModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">

</div>

<div class="modal fade delete-account" id="deleteAccountConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountConfirmationModalLabel" aria-hidden="true">
  
</div>

<div class="modal fade car-quote" id="newCarQuote" tabindex="-1" role="dialog" aria-labelledby="newCarQuoteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="newCarQuoteLabel">Get A Quote</span>
      </div>
      <div class="modal-body">
        <div id="newFormArea">
            <img alt="Car Name" class="img-responsive center-block newQuoteImg" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/soct/soct-not-found.jpg" onerror="master_control.BrokenVehicleImage(this)">
            <div class="row text-center">
              <div class="col-xs-12">
                <span class="h3 spaced newQuoteTitle">2014 Dodge Dart</span>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group {{$errors->has('newQuoteFirst') ? 'has-error' : ''}}">
                  <span class="h3">First Name</span>
                  <?php $name = Auth::check() ? explode(' ', Auth::User()->name) : array(); ?>
                  <input class="form-control" id="newQuoteFirst" name="newQuoteFirst" placeholder="First Name *" value="{{Input::old('newQuoteFirst') ? Input::old('newQuoteFirst') : (isset($name[0]) ? $name[0] : '')}}">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group {{$errors->has('newQuoteLast') ? 'has-error' : ''}}">
                  <span class="h3">Last Name</span>
                  <input class="form-control" id="newQuoteLast" name="newQuoteLast" placeholder="Last Name *" value="{{Input::old('newQuoteLast') ? Input::old('newQuoteLast') : (isset($name[1]) ? $name[1] : '')}}">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group {{$errors->has('newQuoteEmail') ? 'has-error' : ''}}">
                  <span class="h3">Email</span>
                  <input class="form-control" id="newQuoteEmail" name="newQuoteEmail" placeholder="Email *" value="{{Input::old('newQuoteFEmail') ? Input::old('newQuoteEmail') : (Auth::check() ? Auth::User()->email : '')}}">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group {{$errors->has('newQuotePhone') ? 'has-error' : ''}}">
                  <span class="h3">Phone Number</span>
                  <input class="form-control" id="newQuotePhone" name="newQuotePhone" placeholder="Phone *" value="{{Input::old('newQuotePhone')}}">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group {{$errors->has('newQuoteZipcode') ? 'has-error' : ''}}">
                  <span class="h3">Zipcode</span>
                  <input class="form-control" id="newQuoteZipcode" name="newQuoteZipcode" placeholder="Zipcode *" value="{{Input::old('newQuoteZipcode') ? Input::old('newQuoteZipcode') : (Auth::check() ? Auth::User()->zipcode : '')}}">
                </div>
              </div>
            </div>
            <input id="newQuoteVehicle" name="newQuoteVehicle" type="hidden" value="{{Input::old('newQuoteVehicle')}}">
        </div>

        <div id="newSelectionArea" style="display:none;">
            <script type="text/ejs" id="template_new_dealer">
            <% list(new_dealers, function(new_dealer){ %>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="newDealerCheck" data-new-dealer-id="<%= new_dealer.id %>"> <strong><%= new_dealer.name %></strong>
                        </label>
                        <address>
                            <p>
                                <%= new_dealer.address %><br/>
                                <%= new_dealer.city %>, <%= new_dealer.state %> <%= new_dealer.zipcode %>
                            </p>
                        </address>
                    </div>
                  </div>
                </div>
            <% }); %>
            </script>
            <div class="row text-center">
              <div class="col-xs-12">
                <span class="h3 spaced">Dealer Selection</span>
                <span>Please select which dealer(s) you would like to receive a quote from.</span>
              </div>
            </div>

            <div id="newDealerResultsArea">

            </div>
            
        </div>
      </div>
      <div class="modal-footer">
        <div>
            <button id="btnNewQuoteSubmit" class="btn btn-black btn-block btn-lg center-block" data-loading-text="Submitting...">Submit</button>
        </div>
        <div>
            <button id="btnNewDealerSubmit" class="btn btn-black btn-block btn-lg center-block" style="display:none;" data-loading-text="Submitting...">Submit</button>
        </div>
        <div class="row text-center">
            <div class="col-md-12">
                <span id="newSubmissionMessage" style="display:none;">We are submitting your request for a quote, this may take a moment!</span>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade offer used-car-quote" id="usedCarQuote" tabindex="-1" role="dialog" aria-labelledby="usedCarQuoteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <form action="/cars/used-auto-quote" method="POST" role="form"> 
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="usedCarQuoteLabel">Contact the Dealer</span>
      </div>
      <div class=" modal-body">
        <div class="used-car-image">
          <div id="sync1UsedQuote" class="owl-carousel margin-bottom-20">

          </div>

        </div>
        <div class="sync2UsedQuote-container">
          <div id="sync2UsedQuote" class="owl-carousel">
          </div>
        </div>
        <div class="clearfix margin-bottom-15"></div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group {{$errors->has('first_name') ? 'has-error' : ''}}">
              <span class="h3">First Name</span>
              <?php $name = Auth::check() ? explode(' ', Auth::User()->name) : array(); ?>
              <input id="usedQuoteFirst" name="usedQuoteFirst" class="form-control" placeholder="First Name *" value="{{Input::old('usedQuoteFirst') ? Input::old('usedQuoteFirst') : (isset($name[0]) ? $name[0] : '')}}">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group {{$errors->has('last_name') ? 'has-error' : ''}}">
              <span class="h3">Last Name</span>
              <input id="usedQuoteLast" name="usedQuoteLast" class="form-control" placeholder="Last Name *" value="{{Input::old('usedQuoteLast') ? Input::old('usedQuoteLast') : (isset($name[1]) ? $name[1] : '')}}">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group {{$errors->has('email') ? 'has-error' : ''}}">
              <span class="h3">Email</span>
              <input id="usedQuoteEmail" name="usedQuoteEmail" class="form-control" placeholder="Email *" value="{{Input::old('usedQuoteEmail') ? Input::old('usedQuoteEmail') : (Auth::check() ? Auth::User()->email : '')}}">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group {{$errors->has('phone') ? 'has-error' : ''}}">
              <span class="h3">Phone Number</span>
              <input id="usedQuotePhone" name="usedQuotePhone" class="form-control" placeholder="Phone *" value="{{Input::old('usedQuotePhone')}}">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group {{$errors->has('zip') ? 'has-error' : ''}}">
              <span class="h3">Zipcode</span>
              <input id="usedQuoteZipcode" name="usedQuoteZipcode" class="form-control" placeholder="Zipcode *" value="{{Input::old('usedQuoteZipcode') ? Input::old('usedQuoteZipcode') : (Auth::check() ? Auth::User()->zipcode : '')}}">
            </div>
          </div>
        </div>
        <input id="usedQuoteVehicle" name="usedQuoteVehicle" type="hidden" value="{{Input::old('usedQuoteVehicle')}}">
        <div>
            <button id="btnUsedQuoteSubmit" type="submit" class="btn btn-black btn-block btn-lg center-block" data-loading-text="Submitting...">Submit</button>
        </div>
        <div class="row text-center">
            <div class="col-md-12">
                <span id="usedSubmissionMessage" style="display:none;">We are submitting your request for a quote, this may take a moment!</span>
            </div>
        </div>
        <div class="row text-center">
          <div class="col-xs-12">
            <span class="h3 spaced usedQuoteTitle">2000 Ford Focus LT</span>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <p><strong>Make: </strong><span class="usedMake">Ford</span></p>
            <p><strong>Model: </strong><span class="usedModel">Focus<span></p>
          </div>
          <div class="col-sm-6">
            <p><strong>Vin Number: </strong><span class="usedVin">12345678910</span></p>
            <p><strong>Price: </strong><span class="usedPrice">$17,000</span></p>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <p><strong>Exterior Color: </strong><span class="usedExterior">Black</span></p>
            <p><strong>Interior Color: </strong><span class="usedInterior">Black</span></p>
          </div>
          <div class="col-sm-6">
            <p><strong>Mileage: </strong><span class="usedMileage">54,000</span></p>
            <p><strong>Trim: </strong><span class="usedTrim">LT</span></p>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <p><strong>Engine: </strong><span class="usedEngine">3.2L 6 Cyl. SOHC</span></p>
          </div>
          <div class="col-sm-6">
            <p><strong>Transmission: </strong><span class="usedTransmission">Automatic</span></p>
          </div>
        </div>
        
        <div class="clearfix"></div>
        <div class="row options">
          <div class="col-xs-6">
            <button type="button" class="btn btn-default btn-block btn-used-save"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/get_it_save_today.png" alt="Save It" class="img-circle">Favorite It</button>
            <button type="button" class="btn btn-default btn-block btn-used-unsave hidden"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/get_it_save_today.png" alt="Saved!" class="img-circle">Favorited!</button>
          </div>
          <div class="col-xs-6">
            <button type="button" class="btn btn-block btn-default btn-coupon-share"><img alt="Share It" src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/share_it_save_today.png" class="img-circle">Share It</button>
          </div>
        </div>
        <div class="row options-gray">
          
            <div class="col-xs-12 footer">
              <span class="h3 spaced">About This Dealer</span>
              <p><span class="usedMerchant">Crestview Cadillac</span><br>
                  <span class="usedAddress"></span><br>
                  <span class="usedCity">Rochester</span>, <span class="usedState">MI</span> <span class="usedZip">483007</span><br>
              </p>
              <span class="h3 spaced">About This vehicle</span>
              <p><span class="used-car-about usedAbout">Front Wheel Drive, Power Steering, ABS, 4-Wheel Disc Brakes, Steel Wheels, Tires - Front Performance, Tires - Rear Performance, Wheel Covers, Temporary Spare Tire, Automatic Headlights, Power Mirror(s), Intermittent Wipers, Variable Speed Intermittent Wipers, AM/FM Stereo, CD Player, MP3 Player, Auxiliary Audio Input, Cloth Seats, Bucket Seats, Driver Adjustable Lumbar, Pass-Through Rear Seat, Rear Bench Seat, Power Outlet, Floor Mats, Adjustable Steering Wheel, Steering Wheel Audio Controls, Power Windows, Power Door Locks, Keyless Entry, Remote Trunk Release, Cruise Control, Engine Immobilizer, Security System, A/C, Rear Defrost, Driver Vanity Mirror, Passenger Vanity Mirror, Front Reading Lamps, Traction Control, Stability Control, Passenger Air Bag Sensor, Child Safety Locks, Tire Pressure Monitor, Emergency Trunk Release</span></p>
            </div>
            
        </div>
      </div>
      <!--<div class="modal-footer">
        
      </div>-->
      
    </form>
    </div>
  </div>
</div>

<div class="modal fade quote-thanks contest" id="quoteThanksModal" tabindex="-1" role="dialog" aria-labelledby="quoteThanksModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy"id="quoteThanksModalLabel">Quote Request Submitted!</span>
      </div>
      <div class="modal-body">
            <p id="quoteThanksContent">We have received your information, and you should be receiving a quote soon!</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal" >Continue</button>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade offer used-car-modal" id="usedCarModal" tabindex="-1" role="dialog" aria-labelledby="usedCarModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="usedCarModalLabel">Used Car</span>
      </div>
      <div class=" modal-body">
        <div class="used-car-image">
          <div id="sync1UsedModal" class="sync1UsedModal owl-carousel margin-bottom-20">

          </div>
          
          <button type="button" class="btn btn-blue btn-block center-block btn-used-car-modal-quote" data-dismiss="modal"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/quote_it_save_today.png" alt="Quote It" class="img-circle">Quote It</button>
        </div>
        <div class="sync2UsedModal-container">
          <div id="sync2UsedModal" class="sync2UsedModal owl-carousel">
          </div>
        </div>
        <div class="row text-center">
          <div class="col-xs-12">
            <span class="h3 spaced usedTitle">2000 Ford Focus LT</span>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <p><strong>Make: </strong><span class="usedMake">Ford</span></p>
            <p><strong>Model: </strong><span class="usedModel">Focus<span></p>
          </div>
          <div class="col-sm-6">
            <p><strong>Vin Number: </strong><span class="usedVin">12345678910</span></p>
            <p><strong>Price: </strong><span class="usedPrice">$17,000</span></p>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <p><strong>Exterior Color: </strong><span class="usedExterior">Black</span></p>
            <p><strong>Interior Color: </strong><span class="usedInterior">Black</span></p>
          </div>
          <div class="col-sm-6">
            <p><strong>Mileage: </strong><span class="usedMileage">54,000</span></p>
            <p><strong>Trim: </strong><span class="usedTrim">LT</span></p>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <p><strong>Engine: </strong><span class="usedEngine">3.2L 6 Cyl. SOHC</span></p>
          </div>
          <div class="col-sm-6">
            <p><strong>Transmission: </strong><span class="usedTransmission">Automatic</span></p>
          </div>
        </div>  
        <div class="row options">
          <div class="col-xs-6">
            <button type="button" class="btn btn-default btn-block btn-used-save"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/get_it_save_today.png" alt="Save It" class="img-circle">Save It</button>
            <button type="button" class="btn btn-default btn-block btn-used-unsave hidden"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/get_it_save_today.png" alt="Saved!" class="img-circle">Saved!</button>
          </div>
          <div class="col-xs-6">
            <button type="button" class="btn btn-block btn-default btn-coupon-share"><img alt="Share It" src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/share_it_save_today.png" class="img-circle">Share It</button>
          </div>
        </div>
        <div class="row options-gray">
          
            <div class="col-xs-12 footer">
              <span class="h3 spaced">About This Dealer</span>
              <p><span class="usedMerchant">Crestview Cadillac</span><br>
                  <span class="usedAddress"></span><br>
                  <span class="usedCity">Rochester</span>, <span class="usedState">MI</span> <span class="usedZip">483007</span><br>
              </p>
              <span class="h3 spaced">About This vehicle</span>
              <p><span class="used-car-about usedAbout">Front Wheel Drive, Power Steering, ABS, 4-Wheel Disc Brakes, Steel Wheels, Tires - Front Performance, Tires - Rear Performance, Wheel Covers, Temporary Spare Tire, Automatic Headlights, Power Mirror(s), Intermittent Wipers, Variable Speed Intermittent Wipers, AM/FM Stereo, CD Player, MP3 Player, Auxiliary Audio Input, Cloth Seats, Bucket Seats, Driver Adjustable Lumbar, Pass-Through Rear Seat, Rear Bench Seat, Power Outlet, Floor Mats, Adjustable Steering Wheel, Steering Wheel Audio Controls, Power Windows, Power Door Locks, Keyless Entry, Remote Trunk Release, Cruise Control, Engine Immobilizer, Security System, A/C, Rear Defrost, Driver Vanity Mirror, Passenger Vanity Mirror, Front Reading Lamps, Traction Control, Stability Control, Passenger Air Bag Sensor, Child Safety Locks, Tire Pressure Monitor, Emergency Trunk Release</span></p>
            </div>
            
        </div>
      </div>
      
    </div>
  </div>
</div>

<div id="printSection"></div>

<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
  
</div>

<div class="modal fade" id="privacyModal" tabindex="-1" role="dialog" aria-labelledby="privacyModalLabel" aria-hidden="true">
  
</div>

<script>
user_id = '{{Auth::check() ? Auth::User()->id : Auth::nonmember()->id}}';
user_type = '{{Auth::check() ? "User" : "Nonmember"}}';
trackable = '{{SoeHelper::isTrackable() ? 1 : 0}}';
<?php
    if (!stristr(Request::url(), 'printable'))
    {
        if(!Session::has('newUser'))
        {
            Session::put('newUser', 1);
?>
            newUser = {{Session::get('newUser')}};
<?php   
        } else { 
?>
            newUser = 0;
<?php   
        } 
        if(Auth::check() && !SoeHelper::hasLocations(Auth::User()) && !Cookie::get('hasLocation'))
        {
            Cookie::queue('hasLocation', -1, 60*24*3);
?>
            hasLocation = 0;
<?php
        } else {
?>
            hasLocation = 1;
<?php
        }
        if(!Session::has('setPreferences') && Auth::check())
        {
            Session::put('setPreferences', 1);
?>
            setPreferences = 0;
<?php
        } else {
?>
            setPreferences = 1;
<?php
        }
    }
?>
</script>

<script type="text/ejs" id="template_new_car">
<% list(vehicles, function(vehicle){ %>
<div class="item new-car invisible">
    <div class="item-type new-car"></div>
    <div class="top-pic ">
        <a href="{{URL::abs('/')}}/cars/research/<%= vehicle.year %>/<%= (vehicle.make_slug && vehicle.model_slug) ? vehicle.make_slug+'/'+vehicle.model_slug+'/' : '' %><%= vehicle.id %>">
            <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
            <img alt="<%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>" class="img-responsive" src="<%= vehicle.display_image ? vehicle.display_image.path : 'http://s3.amazonaws.com/saveoneverything_assets/assets/images/soct/soct-not-found.jpg' %>" onerror="master_control.BrokenVehicleImage(this)">
        </a>
    </div>
    <a class="item-info" href="{{URL::abs('/')}}/cars/research/<%= vehicle.year %>/<%= (vehicle.make_slug && vehicle.model_slug) ? vehicle.make_slug+'/'+vehicle.model_slug+'/' : '' %><%= vehicle.id %>">
      <div class="h3"><%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %></div>
      <div class="more-info">
            <p><strong>MPG</strong> <%= (vehicle.city_epa != 0) ? vehicle.city_epa+'/'+vehicle.highway_epa+' mpg':'N/A' %></p>
            <p><strong>Body Type</strong> <%= vehicle.primary_body_type %></p>
            <p><strong>MSRP</strong> <%= (vehicle.price != 0)?'$'+vehicle.price:'N/A' %></p>
        </div>
      <div class="incentives special-info" style="<%= vehicle.incentives.length == 0 ? 'display:none;' : '' %>">
        <p><span class="glyphicon glyphicon-ok"></span> <%= vehicle.incentives.length ? vehicle.incentives[0].name : '' %></p>
      </div>
    </a>
    <div class="btn-group">
        <button type="button" class="btn btn-default btn-view-new-car" data-url="/cars/research/<%= vehicle.year %>/<%= (vehicle.make_slug && vehicle.model_slug) ? vehicle.make_slug+'/'+vehicle.model_slug+'/' : '' %><%= vehicle.id %>" data-toggle="tooltip" data-placement="top" title="Get More Info on the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-view-it.png" alt="Get More Info on the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"></button>
        <button type="button" class="btn btn-default btn-new-car-quote" data-vehicle_id="<%= vehicle.id %>" data-toggle="tooltip" data-placement="top" title="Get a Quote on the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-quote-it.png" alt="Get a Quote on the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"></button>
        <button type="button" class="btn btn-default btn-new-share" data-vehicle_id="<%= vehicle.id %>" data-toggle="tooltip" data-placement="top" title="Share the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-share-it.png" alt="Share the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"></button>
    </div>
</div>
<% }); %>
</script>

<script type="text/ejs" id="template_grid_vehicle">
<% list(vehicles, function(vehicle){ %>
<div class="item used-car invisible">
  <div class="item-type used-car"></div>
    <div class="top-pic btn-used-car-quote pointer" data-id="<%= vehicle.id %>"  data-vehicle_id="<%= vehicle.id %>">
            <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
            <img alt="<%= vehicle.year %> <%= vehicle.make %> <%= vehicle.model %>" class="img-responsive" src="<%= vehicle.display_image == '' ? 'http://s3.amazonaws.com/saveoneverything_assets/assets/images/soct/soct-not-found.jpg' : vehicle.display_image %>" onerror="master_control.BrokenVehicleImage(this,<%= vehicle.id %>,'used')">
            
    </div>
    <div class="item-info">
      <p class="merchant-name">
        <% if(vehicle.vendor == 'soct'){ %>
          <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/auto-transportation/auto-dealers/<%= vehicle.dealer_slug %>">
        <% } %>
          <%= vehicle.dealer_name == 'WHOLESALE PARTNER' ? '' : vehicle.dealer_name.toLowerCase().replace(/\b./g, function(name){ return name.toUpperCase(); }) %>&nbsp;
        <% if(vehicle.vendor == 'soct'){ %>
          </a>
        <% } %>
      </p>
      <div class="btn-used-car-quote" data-vehicle_id="<%= vehicle.id %>">
        <span class="h3"><%= vehicle.year %> <%= vehicle.make %> <%= vehicle.model %></span>
        <div class="special-info">
          <p class="expires_at"><strong>Price:</strong> <%= vehicle.internet_price != 0 ? '$'+vehicle.internet_price : 'Call' %></p>
          <p class="expires_at"><strong>Mileage:</strong> <%= vehicle.mileage != 0 ? vehicle.mileage : 'Call' %></p>
        </div>
      </div>
    </div>
    <div class="btn-group">
        <button class="btn btn-default btn-used-car-quote" data-vehicle_id="<%= vehicle.id %>" data-toggle="tooltip" data-placement="top" title="Get More Info on This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-more-info.png" alt="Get More Info on This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"></button>
        <button class="btn btn-default btn-used-share" data-vehicle_id="<%= vehicle.id %>" data-toggle="tooltip" data-placement="top" title="Share This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-share-it.png" alt="Share This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"></button>
        <button type="button" class="btn btn-default btn-used-save <%== vehicle.is_saved == true ? 'disabled' : ''%>" data-vehicle_id="<%= vehicle.id %>" data-toggle="tooltip" data-placement="top" title="Add This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %> to Your Favorites"><img src="<%== vehicle.is_saved == true ? 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-favorited.png' : 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-favorite-it.png'%>" alt="Add This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %> to Your Favorites"></button>
    </div>
</div>
<% }); %>
</script>

<script type="text/ejs" id="template_entity">
<% list(entities, function(entities)
{ %>
    <%
      var c = entities.expires_at.split(/[- :]/);
      var expires = new Date(c[0], c[1]-1, c[2], c[3], c[4], c[5]);
      var company_slug = '';
      if(entities.company_id > 1)
      {
        company_slug = entities.company_name.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
      }
    %>
    <% if (entities.entitiable_type == 'Offer') { %>
        <% if ((entities.secondary_type == 'lease') || (entities.secondary_type == 'purchase')) { %>
            <div class="item <%= entities.secondary_type %> invisible" itemscope itemtype="http://schema.org/Organization">
                <div class="item-type <%= entities.secondary_type %>"></div>
                <div class="top-pic">
                    <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
                        <img alt="<%= entities.merchant_name %> Coupons" class="img-responsive" src="<%= entities.path != entities.logo ? entities.path : (entities.about_img ? entities.about_img : entities.path) %>">
                    </a>
                </div>
                <a class="merchant-link" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%= entities.subcategory_slug %>/<%= entities.merchant_slug+'/'+entities.location_id %>">
                  <div class="item-info">
                    <p class="merchant-name" itemprop="name"><%= entities.merchant_name %></p>
                  </div>
                </a>
                <div itemscope itemtype="http://schema.org/Product">
                    <span class="hidden" itemprop="name"><%= entities.merchant_name %> - <%= entities.name %></span>
                    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                        <a class="item-info" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>?showeid=<%= entities.id %>"itemscope itemtype="http://schema.org/Product">
                          <span class="h3" itemprop="name"><%= entities.name %></span>
                        </a>
                        <p class="category-links"><a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>" title="<%= unSlugCategory(entities.category_slug) %> Coupons"><%= unSlugCategory(entities.category_slug) %></a> > <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug%>" title="<%= unSlugCategory(entities.subcategory_slug) %> Coupons"><%= unSlugCategory(entities.subcategory_slug) %></a></p>
                        <div class="margin-10 expires_at">
                        <% if(entities.hide_expiration == '0'){ %>
                            <span itemprop="availabilityEnds"><strong>Expires</strong> <%== entities.is_reoccurring == "1" ? "{{date('m/t/Y')}}" : expires.getMonth() + 1 +"/"+ expires.getDate() +"/"+ expires.getFullYear() %></span>
                        <% } %>
                            &nbsp;
                        </div>
                    </div>
                </div>
                <% if(entities.company_id > 1) { %>
                <div class="white-label">
                  <p>Provided by <% if (company_slug != 'yipit'){ %><a href="{{URL::abs('/')}}/<%= company_slug %>"><%= entities.company_name %></a><% }else{ %><%= entities.company_name %><% } %></p>
                </div>
                <% } else { %>
                  <div class="see-more">
                      <a class="btn btn-block btn-burgundy-border btn-lg" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
                      <% if(entities.total_entities > 1) { %>
                          View <strong><%== entities.total_entities-1 %></strong> more offer<%== (entities.total_entities > 2)?"s":""%>
                      <% } else { %>
                          View all offers
                      <% } %>
                      </a>
                  </div>
                <% } %>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-more-info.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    <button type="button" class="btn btn-default btn-lease-contact" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Contact <%= entities.merchant_name %> about <%= entities.name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-contact.png" alt="Contact <%= entities.merchant_name %> about <%= entities.name %>"></button>
                    <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Share This Lease Special for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-share-it.png" alt="Share This Lease Special for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                </div>
            </div>
        <% } else if (entities.is_dailydeal == '1') { %>
            <div class="item save-today invisible <%= entities.is_certified == '1' ? 'save_certified_offer' : '' %>" itemscope itemtype="http://schema.org/Organization">
                <div class="item-type save-today"></div>
                <div class="top-pic">
                    <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%= entities.subcategory_slug %>/<%= entities.merchant_slug+'/'+entities.location_id %>">
                        <img alt="<%= entities.merchant_name %> Coupons" class="img-responsive" src="<%= entities.path != entities.logo ? entities.path : (entities.about_img ? entities.about_img : entities.path) %>">
                    </a>
                </div>
                <div class="row">
                  <div class="merchant-offer-count col-xs-12">
                    <a class="item-info" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
                        <p class="merchant-name" itemprop="name"><%= entities.merchant_name %></p>
                    </a>
                  </div>
                </div>
                <div itemscope itemtype="http://schema.org/Product">
                  <span class="hidden" itemprop="name"><%= entities.merchant_name %> - <%= entities.name %></span>
                  <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <a class="item-info" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>?showeid=<%= entities.id %>"itemscope itemtype="http://schema.org/Product">
                      <span class="h3" itemprop="name"><%= entities.name %></span>
                    </a>
                    <p class="category-links"><a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>" title="<%= unSlugCategory(entities.category_slug) %> Coupons"><%= unSlugCategory(entities.category_slug) %></a> > <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug%>" title="<%= unSlugCategory(entities.subcategory_slug) %> Coupons"><%= unSlugCategory(entities.subcategory_slug) %></a></p>
                    <div class="margin-10 expires_at">
                    <% if(entities.hide_expiration == '0'){ %>
                        <span itemprop="availabilityEnds"><strong>Expires</strong> <%== entities.is_reoccurring == "1" ? "{{date('m/t/Y')}}" : expires.getMonth() + 1 +"/"+ expires.getDate() +"/"+ expires.getFullYear() %></span>
                    <% } %>
                        &nbsp;
                    </div>
                  </div>
                </div>
                <% if(entities.is_certified == '1') { %>
                <div class="certified_section">
                  <div class="row">
                    <div class="col-xs-4">
                      <img alt="Save Certified" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/save_certified.png">
                    </div>
                    <div class="col-xs-6">
                      <span class="h2 spaced">Save Certified</span>
                    </div>
                  </div>
                </div>
                <% } else if(entities.company_id > 1) { %>
                <div class="white-label">
                  <p>Provided by <% if (company_slug != 'yipit'){ %><a href="{{URL::abs('/')}}/<%= company_slug %>"><%= entities.company_name %></a><% }else{ %><%= entities.company_name %><% } %></p>
                </div>
                <% } else { %>
                  <div class="see-more">
                      <a class="btn btn-block btn-blue-border btn-lg" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
                      <% if(entities.total_entities > 1) { %>
                          View <strong><%== entities.total_entities-1 %></strong> more offer<%== (entities.total_entities > 2)?"s":""%>
                      <% } else { %>
                          View all offers
                      <% } %>
                      </a>
                  </div>
                <% } %>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-get-it.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    <button type="button" class="btn btn-default btn-save-coupon <%= entities.is_clipped == true ? 'disabled' : '' %>" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Save This Save Today for <%= entities.name %> from <%= entities.merchant_name %>"><img src="<%= entities.is_clipped == true ? 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-saved.png' : 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-save-it.png' %>" alt="Save This Save Today for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    <% if (entities.is_certified == '0' && entities.is_sohi_trial == '0') { %>
                    <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Share This Save Today for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-share-it.png" alt="Share This Save Today for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    <% } %>
                    @if($quote_control)
                    <% if (entities.is_certified == '1' || entities.is_sohi_trial == '1') { %>
                    <a href="{{URL::abs('/')}}<%= '/homeimprovement/quote?offer_id='+entities.entitiable_id %>" type="button" class="btn btn-default btn-coupon-quote" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Get a Quote for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-quote-it.png" alt="Get a Quote for <%= entities.name %> from <%= entities.merchant_name %>"></a>
                    <% } %>
                    @endif
                </div>
            </div>
        <% } else { %>
            <div class="item coupon invisible <%= entities.is_certified == 1 ? 'save_certified_offer' : '' %>" itemscope itemtype="http://schema.org/Organization">
                <div class="item-type coupon"></div>
                <div class="top-pic <%== (entities.company_id == 2)?'yipit':''%>">
                    <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
                        <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
                        <img alt="<%= entities.merchant_name %> Coupons" class="img-responsive" src="<%= entities.merchant_logo ? entities.merchant_logo : (entities.path != entities.logo ? entities.path : (entities.about_img ? entities.about_img : entities.path)) %>" itemprop="image">
                    </a>
                </div>
                <div class="row">
                  <div class="merchant-offer-count col-xs-12">
                    <a class="item-info" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
                        <p class="merchant-name" itemprop="name"><%= entities.merchant_name %></p>
                    </a>
                  </div>
                </div>
                <div itemscope itemtype="http://schema.org/Product">
                  <span class="hidden" itemprop="name"><%= entities.merchant_name %> - <%= entities.name %></span>
                  <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <a class="item-info <%= (entities.company_id > 1)?'third-party':'' %>" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>?showeid=<%= entities.id %>" itemscope itemtype="http://schema.org/Product">
                      <span class="h3" itemprop="name"><%= entities.name %></span>
                    </a>
                    <p class="category-links"><a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>" title="<%= unSlugCategory(entities.category_slug) %> Coupons"><%= unSlugCategory(entities.category_slug) %></a> > <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug%>" title="<%= unSlugCategory(entities.subcategory_slug) %> Coupons"><%= unSlugCategory(entities.subcategory_slug) %></a></p>
                    <div class="margin-10 expires_at">
                    <% if(entities.hide_expiration == '0'){ %>
                        <span itemprop="availabilityEnds"><strong>Expires</strong> <%== entities.is_reoccurring == "1" ? "{{date('m/t/Y')}}" : expires.getMonth() + 1 +"/"+ expires.getDate() +"/"+ expires.getFullYear() %></span>
                    <% } %>
                        &nbsp;
                    </div>
                  </div>
                </div>
                <% if(entities.is_certified == '1') { %>
                <div class="certified_section">
                  <div class="row">
                    <div class="col-xs-4">
                      <img alt="Save Certified" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/save_certified.png">
                    </div>
                    <div class="col-xs-6">
                      <span class="h2 spaced">Save Certified</span>
                    </div>
                  </div>
                </div>
                <% } else if(entities.company_id > 1) { %>
                <div class="white-label">
                  <p>Provided by <% if (company_slug != 'yipit'){ %><a href="{{URL::abs('/')}}/<%= company_slug %>"><%= entities.company_name %></a><% }else{ %><%= entities.company_name %><% } %></p>
                </div>
                <% } else { %>
                  <div class="see-more">
                      <a class="btn btn-block btn-green-border btn-lg" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
                      <% if(entities.total_entities > 1) { %>
                          View <strong><%== entities.total_entities-1 %></strong> more offer<%== (entities.total_entities > 2)?"s":""%>
                      <% } else { %>
                          View all offers
                      <% } %>
                      </a>
                  </div>
                <% } %>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"><span class="visible-xs-inline"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-get-it.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></span><span class="hidden-xs"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-print-it.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></span></button>
                    <button type="button" class="btn btn-default btn-save-coupon <%= entities.is_clipped == true ? 'disabled' : '' %>" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Save This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"><img src="<%= entities.is_clipped == true ? 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-saved.png' : 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-save-it.png' %>" alt="Save This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    <% if (entities.is_certified == '0' && entities.is_sohi_trial == '0') { %>
                    <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Share This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-share-it.png" alt="Share This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    <% } %>
                    @if($quote_control)
                    <% if (entities.is_certified == '1' || entities.is_sohi_trial == '1') { %>
                    <a href="{{URL::abs('/')}}<%= '/homeimprovement/quote?offer_id='+entities.entitiable_id %>" type="button" class="btn btn-default btn-coupon-quote" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Get a Quote for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-quote-it.png" alt="Get a Quote for <%= entities.name %> from <%= entities.merchant_name %>"></a>
                    <% } %>
                    @endif
                </div>
            </div>
        <% } %>
    <% } else if (entities.entitiable_type == 'Contest') { %>

        <div class="item contest btn-get-contest" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" itemscope itemtype="http://schema.org/Organization">
          <div itemscope itemtype="http://schema.org/Product">
            <span class="hidden" itemprop="name"><%= entities.merchant_name %> - <%= entities.display_name %></span>
            <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
              <div class="item-type contest"></div>
              <div class="top-pic">
                  <img alt="<%= entities.name %>" class="img-responsive" src="<%= entities.path %>">
              </div>
              
              <div class="item-info">
                  <div class="item-name">
                      <span itemprop="name"><%= entities.display_name %></span>
                  </div>
                  <button class="btn btn-red btn-lg btn-block btn-get-contest">CLICK HERE TO ENTER</button>
              </div>
              <% if (typeof(entities.winner_first_name) !== "undefined") { %>
              <div class="contest-winner">
                <img alt="Winner: <%= entities.winner_first_name %> <%= entities.winner_last_name.charAt(0) %>.<% if (entities.winner_city != '') { %> from <%= entities.winner_city %>, <%= entities.winner_state %><% } %>" class="pull-left img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/trophy.jpg">
                <p class="spaced">Winner<% if(entities.winner_count > 1){ %> - <a class="show-all-winners" data-contest_id="<%= entities.entitiable_id %>">Show All</a><% } %></p>
                <p><%= entities.winner_first_name %> <%= entities.winner_last_name.charAt(0) %>.<% if (entities.winner_city != '') { %> from <%= entities.winner_city %>, <%= entities.winner_state %><% } %></p>
              </div>
              <% } %>
            </div>
          </div>
        </div>

    <% } else { %>
        <a class="item invisible" href="<%= entities.url %>" target="_blank">
            <img alt="Banner" class="img-responsive" src="<%= entities.path %>">
        </a>
    <% } %>
<% }); %>
</script>

<script type="text/ejs" id="template_single_entity">
<%
      if (typeof(entities.expires_at) != "undefined")
      {
        var c = entities.expires_at.split(/[- :]/);
        var expires = new Date(c[0], c[1]-1, c[2], c[3], c[4], c[5]);
      }
      var company_slug = '';
      if(entities.company_id > 1)
      {
        company_slug = entities.company_name.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
      }
    %>
    <% if (entities.entitiable_type == 'Offer') { %>
        <% if ((entities.secondary_type == 'lease') || (entities.secondary_type == 'purchase')) { %>
            <div class="item <%= entities.secondary_type %>" itemscope itemtype="http://schema.org/Organization">
                <div class="item-type <%= entities.secondary_type %>"></div>
                <div class="top-pic">
                    <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
                        <img alt="<%= entities.merchant_name %> Coupons" class="img-responsive" src="<%= entities.path != entities.logo ? entities.path : (entities.about_img ? entities.about_img : entities.path) %>">
                    </a>
                </div>
                <a class="merchant-link" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%= entities.subcategory_slug %>/<%= entities.merchant_slug+'/'+entities.location_id %>">
                  <div class="item-info">
                    <p class="merchant-name" itemprop="name"><%= entities.merchant_name %></p>
                  </div>
                </a>
                <div itemscope itemtype="http://schema.org/Product">
                    <span class="hidden" itemprop="name"><%= entities.merchant_name %> - <%= entities.name %></span>
                    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <a class="item-info" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>?showeid=<%= entities.id %>"itemscope itemtype="http://schema.org/Product">
                      <span class="h3" itemprop="name"><%= entities.name %></span>
                    </a>
                      <p class="category-links"><a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>" title="<%= unSlugCategory(entities.category_slug) %> Coupons"><%= unSlugCategory(entities.category_slug) %></a> > <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug%>" title="<%= unSlugCategory(entities.subcategory_slug) %> Coupons"><%= unSlugCategory(entities.subcategory_slug) %></a></p>
                      <% if(entities.hide_expiration == '0'){ %><p class="margin-10 expires_at" itemprop="availabilityEnds"><strong>Expires</strong> <%== entities.is_reoccurring == "1" ? "{{date('m/t/Y')}}" : expires.getMonth() + 1 +"/"+ expires.getDate() +"/"+ expires.getFullYear() %></p><% } %>
                    </div>
                </div>
                <% if(entities.company_id > 1) { %>
                <div class="white-label">
                  <p>Provided by <% if (company_slug != 'yipit'){ %><a href="{{URL::abs('/')}}/<%= company_slug %>"><%= entities.company_name %></a><% }else{ %><%= entities.company_name %><% } %></p>
                </div>
                <% } else { %>
                  <div class="see-more">
                      <a class="btn btn-block btn-burgundy-border btn-lg" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
                      <% if(entities.total_entities > 1) { %>
                          View <strong><%== entities.total_entities-1 %></strong> more offer<%== (entities.total_entities > 2)?"s":""%>
                      <% } else { %>
                          View all offers
                      <% } %>
                      </a>
                  </div>
                <% } %>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-more-info.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    <button type="button" class="btn btn-default btn-lease-contact" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Contact <%= entities.merchant_name %> about <%= entities.name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-contact.png" alt="Contact <%= entities.merchant_name %> about <%= entities.name %>"></button>
                    <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Share This Lease Special for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-share-it.png" alt="Share This Lease Special for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                </div>
            </div>
        <% } else if (entities.is_dailydeal == '1') { %>
            <div class="item save-today <%= entities.is_certified == '1' ? 'save_certified_offer' : '' %>" itemscope itemtype="http://schema.org/Organization">
                <div class="item-type save-today"></div>
                <div class="top-pic">
                    <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%= entities.subcategory_slug %>/<%= entities.merchant_slug+'/'+entities.location_id %>">
                        <img alt="<%= entities.merchant_name %> Coupons" class="img-responsive" src="<%= entities.path != entities.logo ? entities.path : (entities.about_img ? entities.about_img : entities.path) %>">
                    </a>
                </div>
                <div class="row">
                  <div class="merchant-offer-count col-xs-12">
                    <a class="item-info" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
                        <p class="merchant-name" itemprop="name"><%= entities.merchant_name %></p>
                    </a>
                  </div>
                </div>
                <div itemscope itemtype="http://schema.org/Product">
                  <span class="hidden" itemprop="name"><%= entities.merchant_name %> - <%= entities.name %></span>
                  <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <a class="item-info" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>?showeid=<%= entities.id %>"itemscope itemtype="http://schema.org/Product">
                        <span class="h3" itemprop="name"><%= entities.name %></span>
                    </a>
                    <p class="category-links"><a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>" title="<%= unSlugCategory(entities.category_slug) %> Coupons"><%= unSlugCategory(entities.category_slug) %></a> > <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug%>" title="<%= unSlugCategory(entities.subcategory_slug) %> Coupons"><%= unSlugCategory(entities.subcategory_slug) %></a></p>
                    <% if(entities.hide_expiration == '0'){ %><p class="margin-10 expires_at" itemprop="availabilityEnds"><strong>Expires</strong> <%== entities.is_reoccurring == "1" ? "{{date('m/t/Y')}}" : expires.getMonth() + 1 +"/"+ expires.getDate() +"/"+ expires.getFullYear() %></p><% } %>
                  </div>
                </div>
                <% if(entities.is_certified == '1') { %>
                <div class="certified_section">
                  <div class="row">
                    <div class="col-xs-4">
                      <img alt="Save Certified" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/save_certified.png">
                    </div>
                    <div class="col-xs-6">
                      <span class="h2 spaced">Save Certified</span>
                    </div>
                  </div>
                </div>
                <% } else if(entities.company_id > 1) { %>
                <div class="white-label">
                  <p>Provided by <% if (company_slug != 'yipit'){ %><a href="{{URL::abs('/')}}/<%= company_slug %>"><%= entities.company_name %></a><% }else{ %><%= entities.company_name %><% } %></p>
                </div>
                <% } else { %>
                  <div class="see-more">
                      <a class="btn btn-block btn-blue-border btn-lg" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
                      <% if(entities.total_entities > 1) { %>
                          View <strong><%== entities.total_entities-1 %></strong> more offer<%== (entities.total_entities > 2)?"s":""%>
                      <% } else { %>
                          View all offers
                      <% } %>
                      </a>
                  </div>
                <% } %>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-get-it.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    <button type="button" class="btn btn-default btn-save-coupon <%= entities.is_clipped == true ? 'disabled' : '' %>" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Save This Save Today for <%= entities.name %> from <%= entities.merchant_name %>"><img src="<%= entities.is_clipped == true ? 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-saved.png' : 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-save-it.png' %>" alt="Save This Save Today for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" @if($quote_control)style="<%= (entities.is_certified == '1' || entities.is_sohi_trial == '1') ? 'display:none;' : '' %>"@endif data-toggle="tooltip" data-placement="top" title="Share This Save Today for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-share-it.png" alt="Share This Save Today for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    @if($quote_control)<a href="{{URL::abs('/')}}<%= '/homeimprovement/quote?offer_id='+entities.entitiable_id %>" type="button" class="btn btn-default btn-coupon-quote" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" style="<%= (entities.is_certified == '1' || entities.is_sohi_trial == '1') ? '' : 'display:none;' %>" data-toggle="tooltip" data-placement="top" title="Get a Quote for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-quote-it.png" alt="Get a Quote for <%= entities.name %> from <%= entities.merchant_name %>"></a>@endif
                </div>
            </div>
        <% } else { %>
            <div class="item coupon <%= entities.is_certified == 1 ? 'save_certified_offer' : '' %>" itemscope itemtype="http://schema.org/Organization">
                <div class="item-type coupon"></div>
                <div class="top-pic <%== (entities.company_id == 2)?'yipit':''%>">
                    <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
                        <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
                        <img alt="<%= entities.merchant_name %> Coupons" class="img-responsive" src="<%= entities.merchant_logo ? entities.merchant_logo : (entities.path != entities.logo ? entities.path : (entities.about_img ? entities.about_img : entities.path)) %>" itemprop="image">
                    </a>
                </div>
                <div class="row">
                  <div class="merchant-offer-count col-xs-12">
                    <a class="item-info" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
                        <p class="merchant-name" itemprop="name"><%= entities.merchant_name %></p>
                    </a>
                  </div>
                </div>
                <div itemscope itemtype="http://schema.org/Product">
                  <span class="hidden" itemprop="name"><%= entities.merchant_name %> - <%= entities.name %></span>
                  <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <a class="item-info  <%= (entities.company_id > 1)?'third-party':'' %>" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>?showeid=<%= entities.id %>" itemscope itemtype="http://schema.org/Product">
                      <span class="h3" itemprop="name"><%= entities.name %></span>
                    </a>
                    <p class="category-links"><a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>" title="<%= unSlugCategory(entities.category_slug) %> Coupons"><%= unSlugCategory(entities.category_slug) %></a> > <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug%>" title="<%= unSlugCategory(entities.subcategory_slug) %> Coupons"><%= unSlugCategory(entities.subcategory_slug) %></a></p>
                    <% if(entities.hide_expiration == '0'){ %><p class="margin-10 expires_at" itemprop="availabilityEnds"><strong>Expires</strong> <%== entities.is_reoccurring == "1" ? "{{date('m/t/Y')}}" : expires.getMonth() + 1 +"/"+ expires.getDate() +"/"+ expires.getFullYear() %></p><% } %>
                  </div>
                </div>
                <% if(entities.is_certified == '1') { %>
                <div class="certified_section">
                  <div class="row">
                    <div class="col-xs-4">
                      <img alt="Save Certified" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/save_certified.png">
                    </div>
                    <div class="col-xs-6">
                      <span class="h2 spaced">Save Certified</span>
                    </div>
                  </div>
                </div>
                <% } else if(entities.company_id > 1) { %>
                <div class="white-label">
                  <p>Provided by <% if (company_slug != 'yipit'){ %><a href="{{URL::abs('/')}}/<%= company_slug %>"><%= entities.company_name %></a><% }else{ %><%= entities.company_name %><% } %></p>
                </div>
                <% } else { %>
                  <div class="see-more">
                      <a class="btn btn-block btn-green-border btn-lg" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
                      <% if(entities.total_entities > 1) { %>
                          View <strong><%== entities.total_entities-1 %></strong> more offer<%== (entities.total_entities > 2)?"s":""%>
                      <% } else { %>
                          View all offers
                      <% } %>
                      </a>
                  </div>
                <% } %>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"><span class="visible-xs-inline"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-get-it.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></span><span class="hidden-xs"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-print-it.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></span></button>
                    <button type="button" class="btn btn-default btn-save-coupon <%= entities.is_clipped == true ? 'disabled' : '' %>" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Save This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"><img src="<%= entities.is_clipped == true ? 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-saved.png' : 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-save-it.png' %>" alt="Save This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" @if($quote_control)style="<%= (entities.is_certified == '1' || entities.is_sohi_trial == '1') ? 'display:none;' : '' %>"@endif data-toggle="tooltip" data-placement="top" title="Share This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-share-it.png" alt="Share This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    @if($quote_control)<a href="{{URL::abs('/')}}<%= '/homeimprovement/quote?offer_id='+entities.entitiable_id %>" type="button" class="btn btn-default btn-coupon-quote" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" style="<%= (entities.is_certified == '1' || entities.is_sohi_trial == '1') ? '' : 'display:none;' %>" data-toggle="tooltip" data-placement="top" title="Get a Quote for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-quote-it.png" alt="Get a Quote for <%= entities.name %> from <%= entities.merchant_name %>"></a>@endif
                </div>
            </div>
        <% } %>
    <% } else if (entities.entitiable_type == 'Contest') { %>

        <div class="item contest btn-get-contest" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" itemscope itemtype="http://schema.org/Organization">
          <div itemscope itemtype="http://schema.org/Product">
            <span class="hidden" itemprop="name"><%= entities.merchant_name %> - <%= entities.display_name %></span>
            <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
              <div class="item-type contest"></div>
              <div class="top-pic">
                  <img alt="<%= entities.name %>" class="img-responsive" src="<%= entities.path %>">
              </div>
              
              <div class="item-info">
                  <div class="item-name">
                      <span itemprop="name"><%= entities.display_name %></span>
                  </div>
                  <button class="btn btn-red btn-lg btn-block btn-get-contest">CLICK HERE TO ENTER</button>
              </div>
              <% if (typeof(entities.winner_first_name) !== "undefined") { %>
              <div class="contest-winner">
                <img alt="Winner: <%= entities.winner_first_name %> <%= entities.winner_last_name.charAt(0) %>.<% if (entities.winner_city != '') { %> from <%= entities.winner_city %>, <%= entities.winner_state %><% } %>" class="pull-left img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/trophy.jpg">
                <p class="spaced">Winner<% if(entities.winner_count > 1){ %> - <a class="show-all-winners" data-contest_id="<%= entities.entitiable_id %>">Show All</a><% } %></p>
                <p><%= entities.winner_first_name %> <%= entities.winner_last_name.charAt(0) %>.<% if (entities.winner_city != '') { %> from <%= entities.winner_city %>, <%= entities.winner_state %><% } %></p>
              </div>
              <% } %>
            </div>
          </div>
        </div>

    <% } else { %>
        <a class="item" href="<%= entities.url %>" target="_blank">
            <img alt="Banner" class="img-responsive" src="<%= entities.path %>">
        </a>
    <% } %>
</script>

<script type="text/ejs" id="template_recommendation_list">
<% list(entities, function(entity){
    if(entity.object_type == 'VehicleStyle')
    { %>
        <%== can.view.render('template_list_new_car', {vehicles: [entity]}); %>
    <% }
    else if(entity.object_type == 'UsedVehicle')
    { %>
        <%== can.view.render('template_list_vehicle', {vehicles: [entity]}); %>
    <% }
    else
    { %>
        <%== can.view.render('template_entity_list', {entities: [entity]}); %>
    <% }
 }); %>
</script>

<script type="text/ejs" id="template_list_new_car">
<% list(vehicles, function(vehicle){ %>
<div class="item list new-car">
    <div class="item-type new-car"></div>
    <div class="row margin-bottom-10 margin-top-10">
      <a class="col-xs-5 col-sm-3" href="{{URL::abs('/')}}/cars/research/<%= vehicle.year %>/<%= (vehicle.make_slug && vehicle.model_slug) ? vehicle.make_slug+'/'+vehicle.model_slug+'/' : '' %><%= vehicle.id %>">
          <div class="list-img">
              <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
              <img alt="<%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>" class="img-responsive" src="<%= vehicle.display_image ? vehicle.display_image.path : 'http://s3.amazonaws.com/saveoneverything_assets/assets/images/soct/soct-not-found.jpg' %>" onerror="master_control.BrokenVehicleImage(this)">
          </div>
      </a>
      <a class="col-xs-7 col-sm-5 col-lg-4 item-info" href="{{URL::abs('/')}}/cars/research/<%= vehicle.year %>/<%= (vehicle.make_slug && vehicle.model_slug) ? vehicle.make_slug+'/'+vehicle.model_slug+'/' : '' %><%= vehicle.id %>">
        <div class="h3"><%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %></div>
        <div class="more-info">
              <p><strong>MPG</strong> <%= (vehicle.city_epa != 0) ? vehicle.city_epa+'/'+vehicle.highway_epa+' mpg':'N/A' %></p>
              <p><strong>Body Type</strong> <%= vehicle.primary_body_type %></p>
              <p><strong>MSRP</strong> <%= (vehicle.price != 0)?'$'+vehicle.price:'N/A' %></p>
          </div>
        <div class="incentives special-info" style="<%= vehicle.incentives.length == 0 ? 'display:none;' : '' %>">
          <p><span class="glyphicon glyphicon-ok"></span> <%= vehicle.incentives.length ? vehicle.incentives[0].name : '' %></p>
        </div>
      </a>
      <div class="col-md-4 visible-lg btn-col">
        <div class="margin-top-10"></div>
        <button type="button" class="btn btn-default btn-view-new-car" data-url="/cars/research/<%= vehicle.year %>/<%= (vehicle.make_slug && vehicle.model_slug) ? vehicle.make_slug+'/'+vehicle.model_slug+'/' : '' %><%= vehicle.id %>" data-toggle="tooltip" data-placement="top" title="Get More Info on the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-view-it.png" alt="Get More Info on the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"></button><!--
        --><button type="button" class="btn btn-default btn-new-car-quote" data-vehicle_id="<%= vehicle.id %>" data-toggle="tooltip" data-placement="top" title="Get a Quote on the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-quote-it.png" alt="Get a Quote on the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"></button><!--
        --><button type="button" class="btn btn-default btn-new-share" data-vehicle_id="<%= vehicle.id %>" data-toggle="tooltip" data-placement="top" title="Share the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-share-it.png" alt="Share the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"></button>
      </div>
    </div>
    <div class="btn-group hidden-lg">
        <button type="button" class="btn btn-default btn-view-new-car" data-url="/cars/research/<%= vehicle.year %>/<%= (vehicle.make_slug && vehicle.model_slug) ? vehicle.make_slug+'/'+vehicle.model_slug+'/' : '' %><%= vehicle.id %>" data-toggle="tooltip" data-placement="top" title="Get More Info on the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-view-it.png" alt="Get More Info on the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"></button>
        <button type="button" class="btn btn-default btn-new-car-quote" data-vehicle_id="<%= vehicle.id %>" data-toggle="tooltip" data-placement="top" title="Get a Quote on the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-quote-it.png" alt="Get a Quote on the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"></button>
        <button type="button" class="btn btn-default btn-new-share" data-vehicle_id="<%= vehicle.id %>" data-toggle="tooltip" data-placement="top" title="Share the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-share-it.png" alt="Share the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"></button>
    </div>
</div>
<% }); %>
</script>

<script type="text/ejs" id="template_list_vehicle">
<% list(vehicles, function(vehicle){ %>
<div class="item list used-car">
    <div class="item-type used-car"></div>
    <div class="row margin-bottom-10 margin-top-10">
        <div class="col-xs-5 col-sm-3 btn-used-car-quote pointer" data-vehicle_id="<%= vehicle.id %>">
            <div class="list-img">
                <img alt="<%= vehicle.year %> <%= vehicle.make %> <%= vehicle.model %>" class="img-responsive" src="<%= vehicle.display_image == '' ? 'http://s3.amazonaws.com/saveoneverything_assets/assets/images/soct/soct-not-found.jpg' : vehicle.display_image %>" onerror="master_control.BrokenVehicleImage(this,<%= vehicle.id %>,'used')">
            </div>
        </div>
        <% if(vehicle.vendor == 'soct'){ %>
        <a class="item-info col-xs-7 col-sm-5 col-lg-4" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/auto-transportation/auto-dealers/<%= vehicle.dealer_slug %>">
        <% }else{ %>
        <div class="item-info col-xs-7 col-sm-5 col-lg-4">
        <% } %>
            <!--<div class="margin-top-20 visible-xs"></div>
            <div class="margin-top-10 hidden-xs"></div>-->
            <div class="h3"><%= vehicle.year %> <%= vehicle.make %> <%= vehicle.model %></div>
            <div class="more-info">
                <p>Price: <strong><%= vehicle.internet_price != 0 ? '$'+vehicle.internet_price : 'Call' %></strong></p>
                <p>Mileage: <strong><%= vehicle.mileage != 0 ? vehicle.mileage : 'Call' %></strong></p>
                <% if(vehicle.address != '') { %>
                <p><%= vehicle.dealer_name == 'WHOLESALE PARTNER' ? '' : vehicle.dealer_name.toLowerCase().replace(/\b./g, function(name){ return name.toUpperCase(); }) %><br>
                    <%= vehicle.address %><br>
                    <%= vehicle.city.toLowerCase().replace(/\b./g, function(name){ return name.toUpperCase(); }) %>, <%= vehicle.state %> <%= vehicle.zipcode %><br>
                    <%= Math.ceil(vehicle.distance / 1609) %> Miles Away</p>
                <% } %>
            </div>
        <% if(vehicle.vendor == 'soct'){ %>
        </a>
        <% }else{ %>
        </div>
        <% } %>
        <div class="col-md-4 visible-lg btn-col">
            <div class="margin-top-10"></div>
            <button class="btn btn-default btn-used-car-quote" data-vehicle_id="<%= vehicle.id %>" data-toggle="tooltip" data-placement="top" title="Get More Info on This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-more-info.png" alt="Get More Info on This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"></button><!--
            --><button class="btn btn-default btn-used-share" data-vehicle_id="<%= vehicle.id %>" data-toggle="tooltip" data-placement="top" title="Share This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-share-it.png" alt="Share This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"></button><!--
            --><button type="button" class="btn btn-default btn-used-save <%== vehicle.is_saved == true ? 'disabled' : ''%>" data-vehicle_id="<%= vehicle.id %>" data-toggle="tooltip" data-placement="top" title="Add This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %> to Your Favorites"><img src="<%== vehicle.is_saved == true ? 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-favorited.png' : 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-favorite-it.png'%>" alt="Add This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %> to Your Favorites"></button>
        </div>
        <div class="btn-group hidden-lg">
            <button class="btn btn-default btn-used-car-quote" data-vehicle_id="<%= vehicle.id %>" data-toggle="tooltip" data-placement="top" title="Get More Info on This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-more-info.png" alt="Get More Info on This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"></button>
            <button class="btn btn-default btn-used-share" data-vehicle_id="<%= vehicle.id %>" data-toggle="tooltip" data-placement="top" title="Share This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-share-it.png" alt="Share This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"></button>
            <button type="button" class="btn btn-default btn-used-save <%== vehicle.is_saved == true ? 'disabled' : ''%>" data-vehicle_id="<%= vehicle.id %>" data-toggle="tooltip" data-placement="top" title="Add This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %> to Your Favorites"><img src="<%== vehicle.is_saved == true ? 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-favorited.png' : 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-favorite-it.png'%>" alt="Add This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %> to Your Favorites"></button>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<% }); %>
</script>

<script type="text/ejs" id="template_entity_list">
<% list(entities, function(entities)
{ %>
    <%
        var c = entities.expires_at.split(/[- :]/);
        var expires = new Date(c[0], c[1]-1, c[2], c[3], c[4], c[5]);
        var company_slug = '';
        if(entities.company_id > 1)
        {
            company_slug = entities.company_name.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
        }
    %>
    <% if (entities.entitiable_type == 'Offer') { %>
        <% if ((entities.secondary_type == 'lease') || (entities.secondary_type == 'purchase')) { %>
            <div class="item list <%= entities.secondary_type %>" itemscope itemtype="http://schema.org/Organization">
                <div class="item-type <%= entities.secondary_type %>"></div>
                <div class="row margin-bottom-10 margin-top-10">
                    <div class="col-lg-8">
                        <div class="row">
                            <a class="col-xs-5 col-sm-3 col-lg-4" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
                                    <div class="list-img">
                                        <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
                                        <img alt="<%= entities.merchant_name %> Coupons" class="img-responsive" src="<%= entities.path != entities.logo ? entities.path : (entities.about_img ? entities.about_img : entities.path) %>" itemprop="image">
                                    </div>
                            </a>
                            <div class="col-xs-7 col-sm-5 col-lg-4 item-info">
                                <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
                                    <p class="merchant-name" itemprop="name">
                                        <%= entities.merchant_name %>
                                    </p>
                                </a>
                                <% if (entities.total_entities > 1) { %>
                                    <p class="offer-count"><strong><%= entities.total_entities-1 %></strong> more offer<%== (entities.total_entities > 2)?"s":""%></p>
                                <% } %>
                            </div>
                            <div class="col-xs-12 col-sm-5 col-lg-8 item-info">
                                <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>?showeid=<%= entities.id %>">
                                    <div class="h3" itemprop="name">
                                        <%= entities.name %>
                                    </div>
                                </a>
                                <p><a class="category" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>"><%= unSlugCategory(entities.category_slug) %></a> > <a class="category" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug%>"><%= unSlugCategory(entities.subcategory_slug) %></a></p>
                                <% if(entities.hide_expiration == '0'){ %><p class="expires_at" itemprop="availabilityEnds"><strong>Expires</strong> <%== entities.is_reoccurring == "1" ? "{{date('m/t/Y')}}" : expires.getMonth() + 1 +"/"+ expires.getDate() +"/"+ expires.getFullYear() %></p><% } %>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 visible-lg btn-col">
                        <div class="margin-top-10"></div>
                        <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-more-info.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></button><!--
                            --><button type="button" class="btn btn-default btn-lease-contact" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Contact <%= entities.merchant_name %> about <%= entities.name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-contact.png" alt="Contact <%= entities.merchant_name %> about <%= entities.name %>"></button><!--
                            --><button type="button" class="btn btn-default btn-coupon-share" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Share This Lease Special for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-share-it.png" alt="Share This Lease Special for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    </div>
                </div>
                <% if(entities.company_id > 1) { %>
                    <div class="white-label">
                        <p>Provided by <% if (company_slug != 'yipit'){ %><a href="{{URL::abs('/')}}/<%= company_slug %>"><%= entities.company_name %></a><% }else{ %><%= entities.company_name %><% } %></p>
                    </div>
                <% } %>
                <div class="btn-group hidden-lg">
                    <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-more-info.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    <button type="button" class="btn btn-default btn-lease-contact" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Contact <%= entities.merchant_name %> about <%= entities.name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-contact.png" alt="Contact <%= entities.merchant_name %> about <%= entities.name %>"></button>
                    <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Share This Lease Special for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-share-it.png" alt="Share This Lease Special for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                </div>
            </div>
        <% } else if (entities.is_dailydeal == '1') { %>
            <div class="item list save-today <%= entities.is_certified == '1' ? 'save_certified_offer' : '' %>" itemscope itemtype="http://schema.org/Organization">
                <div class="item-type save-today"></div>
                <div class="row margin-bottom-10 margin-top-10">
                    <div class="col-lg-8">
                        <div class="row">
                            <a class="col-xs-5 col-sm-3 col-lg-4" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
                                <div class="list-img">
                                    <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
                                    <img alt="<%= entities.merchant_name %> Coupons" class="img-responsive" src="<%= entities.path != entities.logo ? entities.path : (entities.about_img ? entities.about_img : entities.path) %>" itemprop="image">
                                </div>
                            </a>
                            <div class="col-xs-7 col-sm-5 col-lg-8 item-info">
                                <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
                                    <p class="merchant-name" itemprop="name">
                                        <%= entities.merchant_name %>
                                    </p>
                                </a>
                                <% if (entities.total_entities > 1) { %>
                                    <p class="offer-count"><strong><%= entities.total_entities-1 %></strong> more offer<%== (entities.total_entities > 2)?"s":""%></p>
                                <% } %>
                            </div>
                            <div class="col-xs-12 col-sm-5 col-lg-8 item-info">
                                <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>?showeid=<%= entities.id %>">
                                    <div class="h3" itemprop="name">
                                        <%= entities.name %>
                                    </div>
                                </a>
                                <p><a class="category" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>"><%= unSlugCategory(entities.category_slug) %></a> > <a class="category" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug%>"><%= unSlugCategory(entities.subcategory_slug) %></a></p>
                                <% if(entities.hide_expiration == '0'){ %><p class="expires_at" itemprop="availabilityEnds"><strong>Expires</strong> <%== entities.is_reoccurring == "1" ? "{{date('m/t/Y')}}" : expires.getMonth() + 1 +"/"+ expires.getDate() +"/"+ expires.getFullYear() %></p><% } %>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 visible-lg btn-col">
                        <div class="margin-top-10"></div>
                        <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-get-it.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></button><!--
                        --><button type="button" class="btn btn-default btn-save-coupon <%= entities.is_clipped == true ? 'disabled' : '' %>" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Save This Save Today for <%= entities.name %> from <%= entities.merchant_name %>"><img src="<%= entities.is_clipped == true ? 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-saved.png' : 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-save-it.png' %>" alt="Save This Save Today for <%= entities.name %> from <%= entities.merchant_name %>"></button><!--
                        --><button type="button" class="btn btn-default btn-coupon-share" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" @if($quote_control)style="<%= (entities.is_certified == '1' || entities.is_sohi_trial == '1') ? 'display:none;' : '' %>"@endif data-toggle="tooltip" data-placement="top" title="Share This Save Today for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-share-it.png" alt="Share This Save Today for <%= entities.name %> from <%= entities.merchant_name %>"></button><!--
                        -->@if($quote_control)<a href="{{URL::abs('/')}}<%= '/homeimprovement/quote?offer_id='+entities.entitiable_id %>" type="button" class="btn btn-default btn-coupon-quote" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" style="<%= (entities.is_certified == '1' || entities.is_sohi_trial == '1') ? '' : 'display:none;' %>" data-toggle="tooltip" data-placement="top" title="Get a Quote for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/quote_it_save_today.png" alt="Get a Quote for <%= entities.name %> from <%= entities.merchant_name %>" class="img-circle"><br>Quote It</a>@endif
                    </div>
                </div>
                <% if(entities.company_id > 1) { %>
                    <div class="white-label">
                        <p>Provided by <% if (company_slug != 'yipit'){ %><a href="{{URL::abs('/')}}/<%= company_slug %>"><%= entities.company_name %></a><% }else{ %><%= entities.company_name %><% } %></p>
                    </div>
                <% } %>
                <div class="btn-group hidden-lg">
                    <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-get-it.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    <button type="button" class="btn btn-default btn-save-coupon <%= entities.is_clipped == true ? 'disabled' : '' %>" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Save This Save Today for <%= entities.name %> from <%= entities.merchant_name %>"><img src="<%= entities.is_clipped == true ? 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-saved.png' : 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-save-it.png' %>" alt="Save This Save Today for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" @if($quote_control)style="<%= (entities.is_certified == '1' || entities.is_sohi_trial == '1') ? 'display:none;' : '' %>"@endif data-toggle="tooltip" data-placement="top" title="Share This Save Today for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-share-it.png" alt="Share This Save Today for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    @if($quote_control)<a href="{{URL::abs('/')}}<%= '/homeimprovement/quote?offer_id='+entities.entitiable_id %>" type="button" class="btn btn-default btn-coupon-quote" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" style="<%= (entities.is_certified == '1' || entities.is_sohi_trial == '1') ? '' : 'display:none;' %>" data-toggle="tooltip" data-placement="top" title="Get a Quote for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/quote_it_save_today.png" alt="Get a Quote for <%= entities.name %> from <%= entities.merchant_name %>" class="img-circle"><br>Quote It</a>@endif
                </div>
            </div>
        <% } else { %>
            <div class="item list coupon <%= entities.is_certified == 1 ? 'save_certified_offer' : '' %>" itemscope itemtype="http://schema.org/Organization">
                <div class="item-type coupon"></div>
                <div class="row margin-bottom-10 margin-top-10">
                    <div class="col-lg-8">
                        <div class="row">
                            <a class="col-xs-5 col-sm-3 col-lg-4" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
                                <div class="list-img">
                                    <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
                                    <img alt="<%= entities.merchant_name %> Coupons" class="img-responsive" src="<%= entities.merchant_logo ? entities.merchant_logo : (entities.path != entities.logo ? entities.path : (entities.about_img ? entities.about_img : entities.path)) %>" itemprop="image">
                                </div>
                            </a>
                            <div class="col-xs-7 col-sm-5 col-lg-8 item-info">
                                <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
                                    <p class="merchant-name" itemprop="name">
                                        <%= entities.merchant_name %>
                                    </p>
                                </a>
                                <% if (entities.total_entities > 1) { %>
                                    <p class="offer-count"><strong><%= entities.total_entities-1 %></strong> more offer<%== (entities.total_entities > 2)?"s":""%></p>
                                <% } %>
                            </div>
                            <div class="col-xs-12 col-sm-5 col-lg-8 item-info">
                                <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>?showeid=<%= entities.id %>">
                                    <div class="h3" itemprop="name">
                                        <%= entities.name %>
                                    </div>
                                </a>
                                <p><a class="category" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>"><%= unSlugCategory(entities.category_slug) %></a> > <a class="category" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug%>"><%= unSlugCategory(entities.subcategory_slug) %></a></p>
                                <% if(entities.hide_expiration == '0'){ %><p class="expires_at" itemprop="availabilityEnds"><strong>Expires</strong> <%== entities.is_reoccurring == "1" ? "{{date('m/t/Y')}}" : expires.getMonth() + 1 +"/"+ expires.getDate() +"/"+ expires.getFullYear() %></p><% } %>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 visible-lg btn-col">
                        <div class="margin-top-10"></div>
                        <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"><span class="visible-xs-inline"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-get-it.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></span><span class="hidden-xs"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-print-it.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></span></button><!--
                        --><button type="button" class="btn btn-default btn-save-coupon <%= entities.is_clipped == true ? 'disabled' : '' %>" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Save This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"><img src="<%= entities.is_clipped == true ? 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-saved.png' : 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-save-it.png' %>" alt="Save This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"></button><!--
                        --><button type="button" class="btn btn-default btn-coupon-share" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" @if($quote_control)style="<%= (entities.is_certified == '1' || entities.is_sohi_trial == '1') ? 'display:none;' : '' %>"@endif data-toggle="tooltip" data-placement="top" title="Share This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-share-it.png" alt="Share This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"></button><!--
                        -->@if($quote_control)<a href="{{URL::abs('/')}}<%= '/homeimprovement/quote?offer_id='+entities.entitiable_id %>" type="button" class="btn btn-default btn-coupon-quote" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" style="<%= (entities.is_certified == '1' || entities.is_sohi_trial == '1') ? '' : 'display:none;' %>" data-toggle="tooltip" data-placement="top" title="Get a Quote for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-quote-it.png" alt="Get a Quote for <%= entities.name %> from <%= entities.merchant_name %>"></a>@endif
                    </div>
                </div>
                <% if(entities.company_id > 1) { %>
                    <div class="white-label">
                      <p>Provided by <% if (company_slug != 'yipit'){ %><a href="{{URL::abs('/')}}/<%= company_slug %>"><%= entities.company_name %></a><% }else{ %><%= entities.company_name %><% } %></p>
                    </div>
                <% } %>
                <div class="btn-group hidden-lg">
                    <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"><span class="visible-xs-inline"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-get-it.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></span><span class="hidden-xs"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-print-it.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></span></button>
                    <button type="button" class="btn btn-default btn-save-coupon <%= entities.is_clipped == true ? 'disabled' : '' %>" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Save This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"><img src="<%= entities.is_clipped == true ? 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-saved.png' : 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-save-it.png' %>" alt="Save This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" @if($quote_control)style="<%= (entities.is_certified == '1' || entities.is_sohi_trial == '1') ? 'display:none;' : '' %>"@endif data-toggle="tooltip" data-placement="top" title="Share This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-share-it.png" alt="Share This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    @if($quote_control)<a href="{{URL::abs('/')}}<%= '/homeimprovement/quote?offer_id='+entities.entitiable_id %>" type="button" class="btn btn-default btn-coupon-quote" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" style="<%= (entities.is_certified == '1' || entities.is_sohi_trial == '1') ? '' : 'display:none;' %>" data-toggle="tooltip" data-placement="top" title="Get a Quote for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-quote-it.png" alt="Get a Quote for <%= entities.name %> from <%= entities.merchant_name %>"></a>@endif
                </div>
            </div>
        <% } %>
    <% } else if (entities.entitiable_type == 'Contest') { %>
    <% } else { %>
    <% } %>
<% }); %>
</script>

<script type="text/ejs" id="template_recommendation_map">
<% list(entities, function(entity){
    if(entity.object_type == 'VehicleStyle')
    { %>
        <%== can.view.render('template_map_new_car', {vehicles: [entity]}); %>
    <% }
    else if(entity.object_type == 'UsedVehicle')
    { %>
        <%== can.view.render('template_map_vehicle', {vehicles: [entity]}); %>
    <% }
    else
    { %>
        <%== can.view.render('template_entity_map', {entities: [entity]}); %>
    <% }
 }); %>
</script>

<script type="text/ejs" id="template_map_new_car">
<% list(vehicles, function(vehicle){ %>
<div class="item map new-car">
      <a class="item-info" href="{{URL::abs('/')}}/cars/research/<%= vehicle.year %>/<%= (vehicle.make_slug && vehicle.model_slug) ? vehicle.make_slug+'/'+vehicle.model_slug+'/' : '' %><%= vehicle.id %>">
        <div class="h3"><%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %></div>
        <div class="more-info">
              <p><strong>MPG</strong> <%= (vehicle.city_epa != 0) ? vehicle.city_epa+'/'+vehicle.highway_epa+' mpg':'N/A' %></p>
              <p><strong>Body Type</strong> <%= vehicle.primary_body_type %></p>
              <p><strong>MSRP</strong> <%= (vehicle.price != 0)?'$'+vehicle.price:'N/A' %></p>
          </div>
        <div class="incentives special-info" style="<%= vehicle.incentives.length == 0 ? 'display:none;' : '' %>">
          <p><span class="glyphicon glyphicon-ok"></span> <%= vehicle.incentives.length ? vehicle.incentives[0].name : '' %></p>
        </div>
      </a>
    <div class="btn-group">
        <button type="button" class="btn btn-default btn-view-new-car" data-url="/cars/research/<%= vehicle.year %>/<%= (vehicle.make_slug && vehicle.model_slug) ? vehicle.make_slug+'/'+vehicle.model_slug+'/' : '' %><%= vehicle.id %>" data-toggle="tooltip" data-placement="top" data-container="body" title="Get More Info on the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-view-it.png" alt="Get More Info on the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"></button>
        <button type="button" class="btn btn-default btn-new-car-quote" data-vehicle_id="<%= vehicle.id %>" data-toggle="tooltip" data-placement="top" title="Get a Quote on the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-quote-it.png" alt="Get a Quote on the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"></button>
        <button type="button" class="btn btn-default btn-new-share" data-vehicle_id="<%= vehicle.id %>" data-toggle="tooltip" data-placement="top" title="Share the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"> <img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-share-it.png" alt="Share the <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"></button>
    </div>
</div>
<% }); %>
</script>

<script type="text/ejs" id="template_map_vehicle">
<% list(vehicles, function(vehicle){ %>
<div class="item map used-car">
    <% if(vehicle.vendor == 'soct'){ %>
    <a class="item-info" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/auto-transportation/auto-dealers/<%= vehicle.dealer_slug %>">
    <% }else{ %>
    <div class="item-info">
    <% } %>
        <div class="h3"><%= vehicle.year %> <%= vehicle.make %> <%= vehicle.model %></div>
        <div class="more-info">
            <p>Price: <strong><%= vehicle.internet_price != 0 ? '$'+vehicle.internet_price : 'Call' %></strong></p>
            <p>Mileage: <strong><%= vehicle.mileage != 0 ? vehicle.mileage : 'Call' %></strong></p>
            <% if(vehicle.address != '') { %>
            <p><%= vehicle.dealer_name == 'WHOLESALE PARTNER' ? '' : vehicle.dealer_name.toLowerCase().replace(/\b./g, function(name){ return name.toUpperCase(); }) %><br>
                <%= vehicle.address %><br>
                <%= vehicle.city.toLowerCase().replace(/\b./g, function(name){ return name.toUpperCase(); }) %>, <%= vehicle.state %> <%= vehicle.zipcode %><br>
                <%= Math.ceil(vehicle.distance / 1609) %> Miles Away</p>
            <% } %>
        </div>
    <% if(vehicle.vendor == 'soct'){ %>
    </a>
    <% }else{ %>
    </div>
    <% } %>
    <div class="btn-group">
        <button class="btn btn-default btn-used-car-quote" data-vehicle_id="<%= vehicle.id %>" data-toggle="tooltip" data-placement="top" data-container="body" title="Get More Info on This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-more-info.png" alt="Get More Info on This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"></button>
        <button class="btn btn-default btn-used-share" data-vehicle_id="<%= vehicle.id %>" data-toggle="tooltip" data-placement="top" title="Share This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-share-it.png" alt="Share This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %>"></button>
        <button type="button" class="btn btn-default btn-used-save <%== vehicle.is_saved == true ? 'disabled' : ''%>" data-vehicle_id="<%= vehicle.id %>" data-toggle="tooltip" data-placement="top" title="Add This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %> to Your Favorites"><img src="<%== vehicle.is_saved == true ? 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-favorited.png' : 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-favorite-it.png'%>" alt="Add This Used <%= vehicle.year %> <%= vehicle.make_name %> <%= vehicle.model_name %> to Your Favorites"></button>
    </div>
    <div class="clearfix"></div>
</div>
<% }); %>
</script>

<script type="text/ejs" id="template_entity_map">
<% list(entities, function(entities)
{ %>
    <%
      var c = entities.expires_at.split(/[- :]/);
      var expires = new Date(c[0], c[1]-1, c[2], c[3], c[4], c[5]);
      var company_slug = '';
      if(entities.company_id > 1)
      {
        company_slug = entities.company_name.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
      }
    %>
    <% if (entities.entitiable_type == 'Offer') { %>
        <% if ((entities.secondary_type == 'lease') || (entities.secondary_type == 'purchase')) { %>
            <div class="item map <%= entities.secondary_type %>" itemscope itemtype="http://schema.org/Organization">
        <div class="item-info">
                <a class="" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
            <p class="merchant-name" itemprop="name">
              <%= entities.merchant_name %>
            </p>
          </a>
          <% if (entities.total_entities > 1) { %>
                    <p class="offer-count"><strong><%= entities.total_entities-1 %></strong> more offer<%== (entities.total_entities > 2)?"s":""%></p>
                    <% } %>
                    <a class="" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>?showeid=<%= entities.id %>">
            <div class="h3" itemprop="name">
            <%= entities.name %>
            </div>
          </a>
          <p><a class="category" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>"><%= unSlugCategory(entities.category_slug) %></a> > <a class="category" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug%>"><%= unSlugCategory(entities.subcategory_slug) %></a></p>
          <% if(entities.hide_expiration == '0'){ %><p class="expires_at" itemprop="availabilityEnds"><strong>Expires</strong> <%== entities.is_reoccurring == "1" ? "{{date('m/t/Y')}}" : expires.getMonth() + 1 +"/"+ expires.getDate() +"/"+ expires.getFullYear() %></p><% } %>
        </div>
        <% if(entities.company_id > 1) { %>
                <div class="white-label">
                  <p>Provided by <% if (company_slug != 'yipit'){ %><a href="{{URL::abs('/')}}/<%= company_slug %>"><%= entities.company_name %></a><% }else{ %><%= entities.company_name %><% } %></p>
                </div>
                <% } %>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" data-container="body" title="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-more-info.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    <button type="button" class="btn btn-default btn-lease-contact" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Contact <%= entities.merchant_name %> about <%= entities.name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-contact.png" alt="Contact <%= entities.merchant_name %> about <%= entities.name %>"></button>
                    <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Share This Lease Special for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-share-it.png" alt="Share This Lease Special for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                </div>
            </div>
        <% } else if (entities.is_dailydeal == '1') { %>
            <div class="item map save-today <%= entities.is_certified == '1' ? 'save_certified_offer' : '' %>" itemscope itemtype="http://schema.org/Organization">

        <div class="item-info">
                <a class="" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
            <p class="merchant-name" itemprop="name">
              <%= entities.merchant_name %>
            </p>
          </a>
          <% if (entities.total_entities > 1) { %>
                    <p class="offer-count"><strong><%= entities.total_entities-1 %></strong> more offer<%== (entities.total_entities > 2)?"s":""%></p>
                    <% } %>
                    <a class="" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>?showeid=<%= entities.id %>">
            <div class="h3" itemprop="name">
            <%= entities.name %>
            </div>
          </a>
          <p><a class="category" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>"><%= unSlugCategory(entities.category_slug) %></a> > <a class="category" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug%>"><%= unSlugCategory(entities.subcategory_slug) %></a></p>
          <% if(entities.hide_expiration == '0'){ %><p class="expires_at" itemprop="availabilityEnds"><strong>Expires</strong> <%== entities.is_reoccurring == "1" ? "{{date('m/t/Y')}}" : expires.getMonth() + 1 +"/"+ expires.getDate() +"/"+ expires.getFullYear() %></p><% } %>
        </div>
        <% if(entities.company_id > 1) { %>
                <div class="white-label">
                  <p>Provided by <% if (company_slug != 'yipit'){ %><a href="{{URL::abs('/')}}/<%= company_slug %>"><%= entities.company_name %></a><% }else{ %><%= entities.company_name %><% } %></p>
                </div>
                <% } %>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" data-container="body" title="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-get-it.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    <button type="button" class="btn btn-default btn-save-coupon <%= entities.is_clipped == true ? 'disabled' : '' %>" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Save This Save Today for <%= entities.name %> from <%= entities.merchant_name %>"><img src="<%= entities.is_clipped == true ? 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-saved.png' : 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-save-it.png' %>" alt="Save This Save Today for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" @if($quote_control)style="<%= (entities.is_certified == '1' || entities.is_sohi_trial == '1') ? 'display:none;' : '' %>"@endif data-toggle="tooltip" data-placement="top" title="Share This Save Today for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-share-it.png" alt="Share This Save Today for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    @if($quote_control)<a href="{{URL::abs('/')}}<%= '/homeimprovement/quote?offer_id='+entities.entitiable_id %>" type="button" class="btn btn-default btn-coupon-quote" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" style="<%= (entities.is_certified == '1' || entities.is_sohi_trial == '1') ? '' : 'display:none;' %>" data-toggle="tooltip" data-placement="top" title="Get a Quote for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/quote_it_save_today.png" alt="Get a Quote for <%= entities.name %> from <%= entities.merchant_name %>" class="img-circle"><br>Quote It</a>@endif
                </div>
            </div>
        <% } else { %>
            <div class="item map coupon <%= entities.is_certified == 1 ? 'save_certified_offer' : '' %>" itemscope itemtype="http://schema.org/Organization">
              <div class="item-info">
                <a class="" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
            <p class="merchant-name" itemprop="name">
              <%= entities.merchant_name %>
            </p>
          </a>
          <% if (entities.total_entities > 1) { %>
                    <p class="offer-count"><strong><%= entities.total_entities-1 %></strong> more offer<%== (entities.total_entities > 2)?"s":""%></p>
                    <% } %>
                    <a class="" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>?showeid=<%= entities.id %>">
            <div class="h3" itemprop="name">
            <%= entities.name %>
            </div>
          </a>
          <p><a class="category" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>"><%= unSlugCategory(entities.category_slug) %></a> > <a class="category" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug%>"><%= unSlugCategory(entities.subcategory_slug) %></a></p>
          <% if(entities.hide_expiration == '0'){ %><p class="expires_at" itemprop="availabilityEnds"><strong>Expires</strong> <%== entities.is_reoccurring == "1" ? "{{date('m/t/Y')}}" : expires.getMonth() + 1 +"/"+ expires.getDate() +"/"+ expires.getFullYear() %></p><% } %>
        </div>
        <% if(entities.company_id > 1) { %>
                <div class="white-label">
                  <p>Provided by <% if (company_slug != 'yipit'){ %><a href="{{URL::abs('/')}}/<%= company_slug %>"><%= entities.company_name %></a><% }else{ %><%= entities.company_name %><% } %></p>
                </div>
                <% } %>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" data-container="body" title="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"><span class="visible-xs-inline"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-get-it.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></span><span class="hidden-xs"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-print-it.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></span></button>
                    <button type="button" class="btn btn-default btn-save-coupon <%= entities.is_clipped == true ? 'disabled' : '' %>" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Save This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"><img src="<%= entities.is_clipped == true ? 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-saved.png' : 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-save-it.png' %>" alt="Save This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" @if($quote_control)style="<%= (entities.is_certified == '1' || entities.is_sohi_trial == '1') ? 'display:none;' : '' %>"@endif data-toggle="tooltip" data-placement="top" title="Share This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-share-it.png" alt="Share This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    @if($quote_control)<a href="{{URL::abs('/')}}<%= '/homeimprovement/quote?offer_id='+entities.entitiable_id %>" type="button" class="btn btn-default btn-coupon-quote" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" style="<%= (entities.is_certified == '1' || entities.is_sohi_trial == '1') ? '' : 'display:none;' %>" data-toggle="tooltip" data-placement="top" title="Get a Quote for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-quote-it.png" alt="Get a Quote for <%= entities.name %> from <%= entities.merchant_name %>"></a>@endif
                </div>
            </div>
        <% } %>
    <% } else if (entities.entitiable_type == 'Contest') { %>
    <% } else { %>
    <% } %>
    <div class="clearfix"></div>
<% }); %>
</script>

<script type="text/ejs" id="template_sidebar_offer">
<% list(entities, function(entity)
{ %>
    <li>
      <a href="{{URL::abs('/')}}<%= entity.entitiable_type == 'Contest' ? '/contests/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all?showeid='+entity.id : '/'+'coupons'+'/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/'+entity.category_slug+'/'+entity.subcategory_slug+'/'+entity.merchant_slug+'/'+entity.location_id %>">
        <div class="pull-right">
          <img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-view-it.png" alt="View This Coupon for <%= entity.name %> from <%= entity.merchant_name %>">
        </div>
        <div>
          <div class="merchant-name"><%= entity.merchant_name %></div>
          <div class="offer-name"><%= entity.name %></div>
        </div>
      </a>
      <hr class="clearfix">
    </li>
<% }); %>
</script>
<!-- UserVoice JavaScript SDK (only needed once on a page) -->
<script>(function(){var uv=document.createElement('script');uv.type='text/javascript';uv.async=true;uv.src='//widget.uservoice.com/HAH8F2Z9BZLITZLdlQJg.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv,s)})()</script>

<script type="text/javascript">
  var uvOptions = {};
  (function() {
    var uv = document.createElement('script'); 
    uv.type = 'text/javascript'; 
    uv.async = true;
    uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/SPVBLeZBQxhfj9ChtztBGg.js';
    var s = document.getElementsByTagName('script')[0]; 
    s.parentNode.insertBefore(uv, s);
  })();
</script> 
<!-- A function to launch the Classic Widget -->
<script>
UserVoice = window.UserVoice || [];
function showClassicWidget() {
 UserVoice.push(['showLightbox', 'classic_widget', {
   mode: 'support',
   primary_color: '#1F9F5F',
   link_color: '#1F9F5F'
 }]);
}
</script>
<?php
  if(App::environment() == 'prod' && SoeHelper::isTrackable())
  {
  ?>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', "{{Config::get('integrations.google_analytics.siteid')}}", 'saveon.com');
      ga('send', 'pageview');

    </script>

    <!-- Segment Pixel - MNG 59214 - http://www.saveon.com - 033114 - DO NOT MODIFY -->
    <script src="http://ib.adnxs.com/seg?add=1579534&t=1" type="text/javascript"></script>
    <!-- End of Segment Pixel -->

    <!-- Google Code for Remarketing Tag -->
    <script type="text/javascript">
        /* <![CDATA[ */
        var google_conversion_id = 985706296;
        var google_custom_params = window.google_tag_params;
        var google_remarketing_only = true;
        /* ]]> */
    </script>
    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
    <noscript>
        <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/985706296/?value=0&amp;guid=ON&amp;script=0"/>
        </div>
    </noscript> 
  <?php
  }

?>

