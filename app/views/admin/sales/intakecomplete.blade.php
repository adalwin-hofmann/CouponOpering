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

    <div class="center-square">

    <div class="main-content">
      <h2>Your Form Has Been Submitted</h2>
      <h3>What can you expect next?</h3>
      <p>Within 24 hours you will receive an email letting you know your form has been recieved by the content team.</p>
      <p>Upon content conpletion, an email will be sent requesting your content approval or request for changes.</p>
      <h3>Thank You!</h3>
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

@stop