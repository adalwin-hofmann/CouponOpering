@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.sales.master.templates.sidebar', array())
@stop
@section('body')
<!--BEGIN MAIN CONTENT-->
<script>
frameHeights = new Object();
</script>
<div id="main" role="main">
    <div class="block">
        <div class="clearfix"></div>
        
         <!--page title-->
        <div class="pagetitle">
            <h1>Training</h1> 
            <div class="clearfix"></div>
        </div>
         <!--page title end-->
        <div class="clearfix"></div>
         
         <!-- info-box -->
        <div class="grid">
            <div class="grid-title">
                <div class="pull-left">
                    <span>Welcome</span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="grid-content overflow">
                <div class="box">
                    <div class="padded">
                        <div class="row"><!--Start my code-->
                            <!--<div class="col-md-3">
                                <a class="btn btn-info" style="width: 100%; padding: 30px 0px;" href="/backoffice/sales/salesvideos">
                                    <img class="backoffice-icons" src="/bundles/backoffice/img/video-icon.png"><br/>
                                    <span style="font-size: 1.5em">Sales Videos</span>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a class="btn btn-info" style="width: 100%; padding: 30px 0px;" href="/backoffice/sales/dalimvideos">
                                    <img class="backoffice-icons" src="/bundles/backoffice/img/dalim-icon.png"><br/>
                                    <span style="font-size: 1.5em">Dalim Videos</span>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a class="btn btn-info" style="width: 100%; padding: 30px 0px;" href="/backoffice/sales/maghubvideos">
                                    <img class="backoffice-icons" src="/bundles/backoffice/img/maghub-icon.png"><br/>
                                    <span style="font-size: 1.5em">MagHub Videos</span>
                                </a>
                            </div>-->
                            <div class="col-sm-4 margin-bottom-10">
                                <a class="btn btn-info" style="width: 100%; padding: 30px 0px;" href="/trainingholder">
                                    <img class="backoffice-icons" src="/img/training-icon.png"><br/>
                                    <span style="font-size: 1.5em;">Training</span>
                                </a>
                            </div>
                            <div class="col-sm-4 margin-bottom-10">
                                <a class="btn btn-info" style="width: 100%; padding: 30px 0px;" href="/training-videos">
                                    <img class="backoffice-icons" src="img/video-icon.png"><br/>
                                    <span style="font-size: 1.5em">Videos</span>
                                </a>
                            </div>
                            <div class="col-sm-4 margin-bottom-10">
                                <a class="btn btn-info" style="width: 100%; padding: 30px 0px;" href="/training-guides">
                                    <img class="backoffice-icons" src="img/clipboard-icon.png"><br/>
                                    <span style="font-size: 1.5em">Guides</span>
                                </a>
                            </div>
                            
                        </div>
                        <div class="row">
                            <!--<div class="col-md-3" style="margin-top:10px;">
                                <a class="btn btn-info" style="width: 100%; padding: 30px 0px;" href="/bundles/backoffice/img/ebrochure-cheat-sheet.pdf">
                                    <img class="backoffice-icons" src="/bundles/backoffice/img/training-icon.png"><br/>
                                    <span style="font-size: 1.5em;">eBrochure "Cheat Sheet"</span>
                                </a>
                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop