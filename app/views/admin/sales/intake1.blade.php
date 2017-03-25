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
      <li><span class="badge badge-inverse">1</span></li>
      <li><span class="badge">2</span></li>
      <li><span class="badge">3</span></li>
      <li><span class="badge">4</span></li>
      <li><span class="badge">5</span></li>
    </ul>

    <div class="center-square">

    <div class="main-content">
      <h2>Customer ID <a href="#myModal" data-toggle="modal"><span class="badge badge-inverse">?</span></a></h2>
      <form method="post" action="/intake2" class="form-horizontal">
        <table class="form-table">
          <tr>
            <td><input type="text" placeholder="" value=""></td>
          </tr>
        </table>
        <p class="text-right">
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
    <h3 id="myModalLabel">The Customer ID Section</h3>
  </div>
  <div class="modal-body">
    <p>The Customer ID is a unique code used in Maghub to identify a customer. By providing the customer ID, we can go into Maghub to gather all the necessary information for the merchant and their merchant page.</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>

@stop