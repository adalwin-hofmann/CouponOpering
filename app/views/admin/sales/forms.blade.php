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
            <h1>Forms</h1> 
            <div class="clearfix"></div>
        </div>
         <!--page title end-->
         <div class="clearfix"></div>
         
         <!-- info-box -->
        <div class="grid">
            <div class="grid-title">
                <div class="pull-left">
                    <span>Sales Forms</span>
                </div>
            </div>
            <div class="grid-content overflow">
                        <div class="box">
                            <div class="padded">
                                <div id="formSelection" class="">
                                    <div class="box">
                                        <div class="tab-header" style="display:none;">Digital Merchant Layout <span class="cancel" style="float: right; margin-right: 10px;"><a href="/forms">Done</a></span></div>
                                        <div class="padded body-div">
                                            <button class="btn btn-primary" style="width: 200px; margin: 5px;">Digital Merchant Layout</button>
                                            <div class="row form-div" style="display:none;">
                                                <div class="col-md-12">
                                                    <div id="wufoo-m7w9w9">
                                                        Fill out my <a href="http://saveoneverything.wufoo.com/forms/m7w9w9">online form</a>.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box">
                                        <div class="tab-header" style="display:none;">Digital Change Request <span class="cancel" style="float: right; margin-right: 10px;"><a href="/forms">Done</a></span></div>
                                        <div class="padded body-div">
                                            <button class="btn btn-info" style="width: 200px; margin: 5px;">Digital Change Request</button>
                                            <div class="row form-div" style="display:none;">
                                                <div class="col-md-12">
                                                    <div id="wufoo-z7p7m5">
                                                        Fill out my <a href="http://saveoneverything.wufoo.com/forms/z7p7m5">online form</a>.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box">
                                        <div class="tab-header" style="display:none;">WIN & SAVE <span class="cancel" style="float: right; margin-right: 10px;"><a href="/forms">Done</a></span></div>
                                        <div class="padded body-div">
                                            <button class="btn btn-success" style="width: 200px; margin: 5px;">WIN & SAVE</button>
                                            <div class="row form-div" style="display:none;">
                                                <div class="col-md-12">
                                                    <div id="wufoo-k7p9r1">
                                                        Fill out my <a href="http://saveoneverything.wufoo.com/forms/k7p9r1">online form</a>.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box">
                                        <div class="tab-header" style="display:none;">SAVE Today <span class="cancel" style="float: right; margin-right: 10px;"><a href="/forms">Done</a></span></div>
                                        <div class="padded body-div">
                                            <button class="btn btn-warning" style="width: 200px; margin: 5px;">SAVE Today</button>
                                            <div class="row form-div" style="display:none;">
                                                <div class="col-md-12">
                                                    <div id="wufoo-xxae44b1gyj3j4">
                                                        Fill out my <a href="http://saveoneverything.wufoo.com/forms/xxae44b1gyj3j4">online form</a>.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box">
                                        <div class="tab-header" style="display:none;"><span class="cancel" style="float: right; margin-right: 10px;"><a href="/forms">Done</a></span></div>
                                        <div class="padded body-div">
                                            <button class="btn btn-danger" style="width: 200px; margin: 5px;">SOCT Dealer Signup </button>
                                            <div class="row form-div" style="display:none;">
                                                <div class="col-md-12">
                                                    <div id="wufoo-revwika1ui8uuz">
                                                        Fill out my <a href="https://saveoneverything.wufoo.com/forms/revwika1ui8uuz">online form</a>.
                                                    </div>
                                                </div>
                                            </div>
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