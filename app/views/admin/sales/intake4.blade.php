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
      <li><span class="badge">3</span></li>
      <li><span class="badge badge-inverse">4</span></li>
      <li><span class="badge">5</span></li>
    </ul>

    <div class="center-square">

    <div class="main-content">
      <h2>Location <a href="#myModal" data-toggle="modal"><span class="badge badge-inverse">?</span></a></h2>
      <form method="post" action="/intake5" class="form-horizontal">

        <p><label class="radio">
          <input type="radio" name="optionsRadios" id="optionsRadios1" value="usePrintAd">
          See Print Ad
          </label></p>
        <p>&quot;Or&quot;</p>
        <p><label class="radio">
          <input type="radio" name="optionsRadios" id="optionsRadios2" value="useWebsite">
          See Website
          </label></p>
        <p>&quot;Or&quot;</p>
        <p><label class="radio">
          <input type="radio" name="optionsRadios" id="optionsRadios3" value="useCustom">
          Use Custom
          </label></p>
        
        <table class="form-table">
          <tr>
            <td style="width:50%"><input type="text" placeholder="" name="address1"></td>
            <td colspan="2"><input type="text" placeholder="" name="address2"></td>
          </tr><tr>
            <td>Address Line 1</td>
            <td colspan="2">Address Line 2</td>
          </tr><tr>
            <td><input type="text" placeholder="" name="city"></td>
            <td><input type="text" placeholder="" name="state"></td>
            <td><input type="text" placeholder="" name="zipcode"></td>
          </tr><tr>
            <td>City</td>
            <td>State</td>
            <td>Zip Code</td>
          </tr><tr>
            <td><input type="text" placeholder="" name="country"></td>
            <td colspan="2"><input type="text" placeholder="" name="phone"></td>
          </tr><tr>
            <td>Country</td>
            <td colspan="2">Phone Number</td>
          </tr>
        </table>
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
    <h3 id="myModalLabel">The Location Section</h3>
  </div>
  <div class="modal-body">
    <p>A merchant's location can always just be taken from a website or print ad, however, in situations where a merchant has multiple locations, we require that they are manually entered or that a site is referenced listing all available locations.</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>

@stop