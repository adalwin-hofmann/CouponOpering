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
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="padded">
                                <div class="padded iframe-holder" style="max-height: 800px; overflow:scroll; -webkit-overflow-scrolling:touch">
                                    <iframe src="/training" width="99%" height="800px"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop