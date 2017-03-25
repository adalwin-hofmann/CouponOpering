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
                <form action="/reset-password" class="form-login" method="POST"> 
                    <div class="content-login">
                        <div class="header">Reset Password</div>
                        <div class="form-group inputs">
                            <label for="password">Email</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email Address"/>
                            <span style="color:red;">{{$errors->first('email')}}</span>
                        </div>
                        <div class="form-group inputs">
                            <label for="password">New Password</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Change Password"/>
                            <span style="color:red;">{{$errors->first('password')}}</span>
                        </div>
                        <div class="form-group inputs">
                            <label for="password">Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password"/>
                            <span style="color:green;">{{Session::has('password_changed') ? 'Password changed successfully.' : ''}}</span>
                        </div>
                        <input type="hidden" name="uniq" value="{{$uniq}}"/>
                        @if(Session::has('invalid'))<span class="pull-left" style="color:red;">This password reset is invalid, <br/>please request a new one <a href="#" data-toggle="modal" data-target="#forgotPasswordModal">here.</a></span>@endif
                        <div class="clear"></div>
                        <div class="button-login"><button type="submit" class="btn btn-primary btn-lg btn-block">Reset Password</button></div>
                    </div>
                    <div class="footer-login">
                    </div>
                </form>
            </div>
        </div>
    </div>
  </body>
</html>