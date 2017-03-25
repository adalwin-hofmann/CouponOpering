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
      <li><span class="badge badge-inverse">2</span></li>
      <li><span class="badge">3</span></li>
      <li><span class="badge">4</span></li>
      <li><span class="badge">5</span></li>
    </ul>

    <div class="center-square">

    <div class="main-content">
      <h2>About <a href="#myModal" data-toggle="modal"><span class="badge badge-inverse">?</span></a></h2>
      <form method="post" action="/intake3" class="form-horizontal">
        <table class="form-table">
          <tr>
            <td>Website Address:</td>
            <td><input type="text" placeholder="">
          </tr><tr>
            <td colspan="2">
              <textarea id="about" name="about" class="fill-up" rows="10">Enter About Us text here...</textarea>
            </td>
          </tr><tr>
            <td colspan="2">
              <p>&quot;Or&quot;</p>
              <p><label class="radio">
                  <input type="radio" name="optionsRadios" id="optionsRadios1" value="useWebsite">
                  Use Website for About Us
                </label></p>
            </td>
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
    <h3 id="myModalLabel">The About Us Section</h3>
  </div>
  <div class="modal-body">
    <p>The About Us section for a merchant's page is an electronic brochure for their microsite. It may be tempting to just copy and paste text from a merchant's website, but just know that unique content drives traffic and prints. By starting off your merchant page with unique content, you are giving said merchant a leg up on their competition.</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>

@stop