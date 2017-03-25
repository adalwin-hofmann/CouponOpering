<!DOCTYPE html>
<html lang="en"  class="body-error">
<head>
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
<body id="main" class="signup-page">
    <div class="container">

        <h1>Merchant Portal</h1>

        <p>Sign up to get up to date stats on how your business is doing.</p>

        <form method="POST" action="signup">
            @if(!$user)
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control" name="firstName" placeholder="Enter First Name" value="{{Input::old('firstName')}}">
                        <span style="color:red;">{{$errors->first('firstName')}}</span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" class="form-control" name="lastName" placeholder="Enter Last Name" value="{{Input::old('lastName')}}">
                        <span style="color:red;">{{$errors->first('lastName')}}</span>
                    </div>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter Email Address" value="{{$email}}" readonly="readonly">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Company Name</label>
                        <input type="text" class="form-control " name="company" placeholder="Enter Company Name" value="{{$franchise ? $franchise->name : ''}}" readonly="readonly">
                    </div>
                </div>
            </div>
            @if(!$user)
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Enter a password">
                        <span style="color:red;">{{$errors->first('password')}}</span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm the password">
                    </div>
                </div>
            </div>
            @else
            <h3>This email address matches an existing user, please confirm your password to sign up.</h3>
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label>Confirm Password</label>
                    <input type="password" Class="form-control" name="existing_password">
                    <span style="color:red;">{{$errors->first('password_match') ? 'This password does not match the existing user.' : ''}}</span>
                </div>
            </div>
            @endif
            <input type="hidden" name="uniq" value="{{$uniq}}"/>
            <div class="text-center">
                <button type="submit" class="btn btn-primary" {{$invalid ? 'disabled="disabled"' : ''}}>Sign Up</button>
            </div>
            <div class="text-center">
                @if($invalid || Session::has('invalid'))<span style="color:red;">This signup link is invalid, please request a new one.</span>@endif
            </div>
        </form>

    </div>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/js/jquery-1.11.0.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/can.custom.js"></script>
    @section('code')
        {{isset($code) ? $code : ''}}
    @show

</body>
</html>