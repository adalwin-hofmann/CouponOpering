
<div class="modal fade" id="signUpModal" tabindex="-1" role="dialog" aria-labelledby="signUpModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <h1 class="modal-title fancy"id="signUpModalLabel">Sign Up</h1>
        <p class="margin-top-20">Becoming a member is quick and easy! Please complete the form below:</p>
      </div>
      <div class="modal-body">
        <form id="signUpForm" role="form" method="post" action="/signup" onsubmit="return master_control.ValidateSignup();">
          <div class="row">
            <div class="col-sm-6">
            <div style="position:relative;" class="form-group">
              <input type="text" class="form-control" id="signUpFirstName" name="signUpFirstName" placeholder="* First Name">
              <div style="position:absolute; top:6px; right:10px;">
                <button type="button" data-trigger="focus" data-content="Providing your gender helps us find coupons, deals, and contests that are customized and catered to your interests." data-placement="bottom" class="popover-info btn-link"><span class="glyphicon glyphicon-question-sign"></span></button>
              </div>
              <span id="first_name_message" class="signup-message" style="color: red;"></span>
            </div>

            <div class="form-group">
              <input type="text" class="form-control" id="signUpLastName" name="signUpLastName" placeholder="Last Name">
            </div>
            <div style="position:relative;" class="form-group">
              <input type="email" class="form-control" id="signUpEmail" name="signUpEmail" placeholder="* Email">
              <div style="position:absolute; top:6px; right:10px;">
                <button type="button" data-trigger="focus" data-content="Providing your gender helps us find coupons, deals, and contests that are customized and catered to your interests." data-placement="bottom" class="popover-info btn-link"><span class="glyphicon glyphicon-question-sign"></span></button>
              </div>
              <span id="email_message" class="signup-message" style="color: red;"></span>
            </div>
            <div class="form-group">
              <input type="text" maxlength="5" class="form-control" id="signUpLastZip" name="signUpLastZip" placeholder="* Zip Code">
              <span id="zipcode_message" class="signup-message" style="color: red;"></span>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <input type="password" class="form-control" id="signUpPassword" name="signUpPassword" placeholder="* Password">
              <span id="password_message" class="signup-message" style="color: red;"></span>
            </div>
            <div class="form-group">
              <input type="password" class="form-control" id="signUpPasswordConfirm" name="signUpPasswordConfirm" placeholder="* Confirm Password">
            </div>
            <div class="form-group">
                <select name="signUpGender" class="form-control">
                    <option value=''>Choose Gender</option>
                    <option <?php echo $gender=='M'?'selected="selected"': '';?> value='M'>Male</option>
                    <option <?php echo $gender=='F'?'selected="selected"': '';?> value='F'>Female</option>
                </select>
            </div>
            <div style="position:relative;" class="row form-group">
              <div class="col-xs-3"><label for="">Birthday</label></div>
              <div class="col-xs-8">
                <select name="SignUpDateOfBirthMonth" class="form-control inline">
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
                <select name="SignUpDateOfBirthDay" class="form-control inline">
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
                <select name="SignUpDateOfBirthYear" class="form-control inline">
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
                <button type="button" data-trigger="focus" data-content="Providing your gender helps us find coupons, deals, and contests that are customized and catered to your interests." data-placement="bottom" class="popover-info btn-link"><span class="glyphicon glyphicon-question-sign"></span></button>
              </div>
            </div>
          </div>
        </div>
          <div class="clearfix"></div>
          <p class="required-disclaimer">* These fields are required</p>
        
        <div class="form-group">
            <div class="checkbox">
                <label>
                  <input id="signUpTerms" type="checkbox">* I have read and agree to the <a href="{{URL::abs('/')}}/terms">terms of use</a>
                </label>
            </div>
            <span id="terms_message" class="signup-message" style="color: red;"></span>
        </div>
        <div class="form-group">
            <div class="checkbox">
                <label>
                  <input type="checkbox"> I would like to receive newsletters and promotions through email.
                </label>
            </div>
        </div>
        <input type="hidden" id="redirectUrl" name="currentUrl" value="{{Request::url()}}"/>
        <input type="hidden" class="show-eid" name="signUpEid" value="0"/>
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
