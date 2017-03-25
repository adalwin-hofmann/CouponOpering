<!DOCTYPE html>
<html lang="en"  class="body-error"><head>
    <meta charset="utf-8">
    <title>{{$title}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Bootstrap -->
    <link href="/nightsky/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="/nightsky/css/bootstrap-glyphicons.css" rel="stylesheet" media="screen">
    
    <!-- Custom styles for this template -->
    <link href="/nightsky/css/login.css" rel="stylesheet">

    <link rel="stylesheet" href="/css/backoffice.css">
    
  <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <script src="/nightsky/js/respond.min.js"></script>
  <![endif]-->
    
  </head>
  <body id="main">
    <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
        <div id="wrapper">
            <div id="login" class="animate form position">
                <form action="/login" class="form-login" method="POST"> 
                    <div class="content-login">
                      <div class="header">Account Login</div>
                      
                      <div class="form-group inputs">
                          <input class="form-control" name="signInEmail" type="text" placeholder="Email" />
                      </div>
                      <div class="form-group inputs">
                          <input class="form-control" name="signInPassword" type="password"  placeholder="Password" />
                      </div>
                      
                      <!--<div class="link-1"><a href="#">Create New Account</a></div>
                      <div class="link-2"><a href="#">Forgot Password?</a></div>-->
                      <div class="clear"></div>
                      <div class="button-login"><input type="submit" class="btn btn-primary btn-lg btn-block" value="Sign In"></div>

                      <hr>

                      <div class="text-center">
                            <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#forgotPasswordModal">Forgot Password?</a>
                      </div>
                    </div>
                    
                    <div class="footer-login">
                        <div class="alert alert-danger alert-dismissable login-message" style="{{Session::has('signInError') ? '' : 'display:none;'}} margin-left: -19px;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <span>{{Session::has('signInError') ? 'Your login credentials are incorrect.' : ''}}</span>
                        </div>
                     <!-- <div class="pull-left">Sing In With</div>
                     <div class="pull-right">
                       <ul class="social-links">
                          <li class="facebook"><a href="#"><span>facebook</span></a></li>
                          <li class="twitter"><a href="#"><span>twitter</span></a></li>
                          <li class="google-plus"><a href="#"><span>google</span></a></li>
                       </ul>
                     </div> 
                     <div class="clear"></div> -->
                    </div>
                   
                </form>

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
          <form>
            <div class="modal-body">
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
    
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/js/jquery-1.11.2.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/can.custom.js"></script>
    @section('code')
        {{isset($code) ? $code : ''}}
    @show

  </body>


</html>