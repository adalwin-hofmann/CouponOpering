@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.sales.master.templates.sidebar', array())
@stop
@section('body')

<!--BEGIN MAIN CONTENT-->
<div id="main" role="main">
  <div class="block">
    <div class="clearfix"></div>

    <!--page title-->
    <div class="pagetitle">
      <h1>Intake Form</h1> 
      <div class="clearfix"></div>
    </div>
    <!--page title end-->

    <hr>

    <ul class="inline center-block">
      <li><span class="badge">1</span></li>
      <li><span class="badge">2</span></li>
      <li><span class="badge badge-inverse">3</span></li>
      <li><span class="badge">4</span></li>
      <li><span class="badge">5</span></li>
    </ul>

    <div class="center-square">

    <div class="main-content">
      <h2>Pictures <a href="#myModal" data-toggle="modal"><span class="badge badge-inverse">?</span></a></h2>
      <form method="post" action="/intake4" class="form-horizontal">
      <p>Upload custom photos:</p>
      <div class="row center-block">
        <div class="col-md-1">
          <a href="#"><img id="" class="" src="http://placehold.it/320X300"></a>
        </div>
        <div class="col-md-1">
          <a href="#"><img id="" class="" src="http://placehold.it/320X300"></a>
        </div>
        <div class="col-md-1">
          <a href="#"><img id="" class="" src="http://placehold.it/320X300"></a>
        </div>
        <div class="col-md-1">
          <a href="#"><img id="" class="" src="http://placehold.it/320X300"></a>
        </div>
      </div>
      <div class="clearfix"></div>
      <p><a href="#">+ Add Additional Photos</a></p>
      <p>&quot;Or&quot;</p>
      <p><label class="radio">
          <input type="radio" name="optionsRadios" id="optionsRadios1" value="useWebsite">
          Use Website for Photos
        </label></p>
      <p>&quot;Or&quot;</p>
      <p><label class="radio">
          <input type="radio" name="optionsRadios" id="optionsRadios2" value="usePrintAd">
          Use Print Ad for Photos
        </label></p>
      <p class="text-right">
        <button type="button" class="btn btn-prev">Prev</button>
        <button type="submit" class="btn btn-next">Next</button>
      </p>
      </form>

    </div>

    </div>

    <!--BEGIN FOOTER-->
    <div class="footer">
      <div class="left">Copyright &copy; 2013</div>
      <div class="right"><!--<a href="#">Buy Template</a>--></div>
      <div class="clearfix"></div>
    </div>
    <!--BEGIN FOOTER END-->
          
    <div class="clearfix"></div> 
  </div><!--end .block-->
</div>
<!--MAIN CONTENT END-->

<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">The Picture Section</h3>
  </div>
  <div class="modal-body">
    <p>They say a picture is worth 1000 words, by having multiple photos we are able to show off the various features and attributes of our merchant's product/services. In this section we allow you to upload photos via our gallery, your personal electronic device, the merchant's website or print ad.</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>

@stop