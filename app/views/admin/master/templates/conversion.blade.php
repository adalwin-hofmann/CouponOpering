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
    <link href="/nightsky/css/custom-style.css" rel="stylesheet">
    
  <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <script src="/nightsky/js/respond.min.js"></script>
  <![endif]-->
    <script>
        allowDrag = true;
    </script>
  </head>
  <body>
    <div class="col-xs-12">
        <div id="wrapper" style="top: 60px;">
            <div class="animate form position">
                <div class="form-login">
                    <div class="content-login">
                      <div class="header">Drag and Drop File to Convert</div>
                    </div>
                    <div id="container" style="height: 500px;">

                    </div>
                </div>

            </div>   
        </div> 
    </div>
   
    
    

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/nightsky/js/jquery.min.js"></script>
    <script src="/js/can.custom.js"></script>
    <div class="upload-window-fade" style="display:none;"></div>
  </body>
    @section('code')
        {{isset($code) ? $code : ''}}
    @show
</html>