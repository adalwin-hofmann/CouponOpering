@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.content.master.templates.sidebar', array())
@stop
@section('body')
<script>
    selectedFranchise = "{{ $franchise_id }}";
    selectedMerchant = "{{ $merchant_id }}";
</script>
    <!--BEGIN MAIN CONTENT-->
    <div id="main" role="main">
      <div class="block">
      <div class="clearfix"></div>
        
         <!--page title-->
         <div class="pagetitle">
            <h1>Dashboard</h1> 
            <div class="clearfix"></div>
         </div>
         <div class="clearfix"></div>
         <!--page title end-->
         
         <!-- info-box -->
         <div class="grid">
            <div class="grid-title">
                <div class="pull-left">
                    <span>Syndication</span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="grid-content overflow">
                <div class="row">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="can_syndicate" style="display:inline;" {{ $franchise->can_syndicate ? 'checked="checked"' : '' }}> Syndicate This Franchise?
                            </label>
                        </div>
                    </div>
                </div>
                <div id="syndDiv" style="{{ $franchise->can_syndicate ? '' : 'display:none;' }}">
                    <div class="divider"><hr></div>
                    <legend>Syndication Setup</legend>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Syndication Radius (Miles)</label>
                            <input type="text" class="form-control" id="syndication_radius" value="{{ $franchise->syndication_radius }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Syndication Rating</label>
                            <select class="form-control" id="syndication_rating">
                                <option value="">Choose</option>
                                <option value="family-friendly" {{$franchise->syndication_rating == 'family-friendly' ? 'selected="selected"' : ''}}>Family Friendly</option>
                                <option value="adult" {{$franchise->syndication_rating == 'adult' ? 'selected="selected"' : ''}}>Adult</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Click Pay Rate</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                                    <input class="form-control" id="click_pay_rate" type="text" value="{{number_format(($franchise->click_pay_rate / 100), 2, '.', '')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Impression Pay Rate</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                                    <input class="form-control" id="impression_pay_rate" type="text" value="{{number_format(($franchise->impression_pay_rate / 100), 2, '.', '')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <form method="post" enctype="multipart/form-data" action="/wizard-imgupload" target="728x90Frame">
                            <div class="col-md-6 form-group">
                                <label>Syndication Banner (728x90)</label>
                                <input type="file" name="upload_img"><button type="submit" class="btn btn-primary btn-banner" data-loading-text="Loading...">Upload</button>
                                <input name="type" type="hidden" value="syndication_banner">
                                <input name="banner_type" type="hidden" value="banner_728x90">
                                <div style="{{ $franchise->banner_728x90 == '' ? 'display:none;': '' }}">
                                    <a id="banner_728x90" href="{{ $franchise->banner_728x90 }}">View Banner</a> | <a class="btn-link btn-clear" data-banner="banner_728x90">Remove</a>
                                </div>
                            </div>
                            <input name="franchise_id" value="{{ $franchise->id }}" type="hidden">
                        </form>
                        <iframe name="728x90Frame" id="728x90Frame" height="0" border="0" scrolling="auto" style="display: none;" src=""></iframe>
                    </div>
                    <div class="row">
                        <form method="post" enctype="multipart/form-data" action="/wizard-imgupload" target="300x600Frame">
                            <div class="col-md-6 form-group">
                                <label>Syndication Banner (300x600)</label>
                                <input type="file" name="upload_img"><button type="submit" class="btn btn-primary btn-banner" data-loading-text="Loading...">Upload</button>
                                <input name="type" type="hidden" value="syndication_banner">
                                <input name="banner_type" type="hidden" value="banner_300x600">
                                <div style="{{ $franchise->banner_300x600 == '' ? 'display:none;': '' }}">
                                    <a id="banner_300x600" href="{{ $franchise->banner_300x600 }}">View Banner</a> | <a class="btn-link btn-clear" data-banner="banner_300x600">Remove</a>
                                </div>
                            </div>
                            <input name="franchise_id" value="{{ $franchise->id }}" type="hidden">      
                        </form>
                        <iframe name="300x600Frame" id="300x600Frame" height="0" border="0" scrolling="auto" style="display: none;" src=""></iframe>
                    </div>
                    <div class="row">
                        <form method="post" enctype="multipart/form-data" action="/wizard-imgupload" target="300x250Frame">
                            <div class="col-md-6 form-group">
                                <label>Syndication Banner (300x250)</label>
                                <input type="file" name="upload_img"><button type="submit" class="btn btn-primary btn-banner" data-loading-text="Loading...">Upload</button>
                                <input name="type" type="hidden" value="syndication_banner">
                                <input name="banner_type" type="hidden" value="banner_300x250">
                                <div  style="{{ $franchise->banner_300x250 == '' ? 'display:none;': '' }}">
                                    <a id="banner_300x250" href="{{ $franchise->banner_300x250 }}">View Banner</a> | <a class="btn-link btn-clear" data-banner="banner_300x250">Remove</a>
                                </div>
                            </div>
                            <input name="franchise_id" value="{{ $franchise->id }}" type="hidden">
                        </form>
                        <input type="hidden" id="form_banner_728x90" value="{{ $franchise->banner_728x90 }}">
                        <input type="hidden" id="form_banner_300x600" value="{{ $franchise->banner_300x600 }}">
                        <input type="hidden" id="form_banner_300x250" value="{{ $franchise->banner_300x250 }}">
                        <iframe name="300x250Frame" id="300x250Frame" height="0" border="0" scrolling="auto" style="display: none;" src=""></iframe>
                        <button id="ieDoneButton" type="button" style="display: none;"></button>
                    </div>
                </div>
                

                <div class="divider"><hr></div>
                <div class="" style="padding-bottom: 0px;">
                    <div class="pull-left">
                        <button type="button" class="btn btn-primary btn-prev"><i class="icon-hand-left"></i>  Back</button>
                    </div>
                    <div class="pull-right">
                        <button type="button" class="btn btn-success btn-next">Next  <i class="icon-hand-right"></i></button>
                    </div>
                </div>
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