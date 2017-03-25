@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.content.master.templates.sidebar', array())
@stop
@section('body')

<script type="text/ejs" id="template_banner">
    <div id="banner-<%= banner.id %>" class="row <%= banner.type+'-banner-row' %>">
        <div class="col-md-12">
            <div>
                <label>
                    <input class="banner-demo" type="checkbox" style="display:inline" <%= banner.is_demo == '1' ? 'checked="checked"' : '' %>> Is Demo?
                </label>
            </div>
            <img class="img-responsive" src="<%= banner.path %>">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Radius (Miles)</label>
                    <input type="text" class="banner-radius form-control" value="<%= banner.service_radius / 1607 %>">
                </div>
                <% if(banner.type == 'keyword'){ %>
                <div class="col-md-8 form-group">
                    <label>Keywords</label>
                    <input class="banner-keywords form-control" type="text" value="<%= banner.keywords %>">
                </div>
                <% } %>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Locations</label>
                    <div class="input-group">
                        <select class="banner-locations form-control" <%= banner.is_location_specific == '1' ? 'multiple="true"' : '' %>>
                            <option value="0">All Locations</option>
                            @foreach($locations as $location)
                            <option value="{{$location->id}}">{{$location->name}} <%= (banner.is_location_specific == '1' && locations.indexOf({{$location->id}}) > -1) ? 'selected="selected"' : '' %></option>
                            @endforeach
                        </select>
                        <span class="input-group-btn">
                            <button type="button" class="btn-more-locations btn btn-sm" data-banner_id="<%= banner.id %>"><i class="<%= banner.is_location_specific ? 'icon-minus' : 'icon-plus' %>"></i></button>
                        </span>
                    </div>
                </div>
                <div class="col-md-6 form-group">
                    <label>Custom Url</label>
                    <input type="text" class="form-control banner-url" value="<%= banner.custom_url %>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn-delete btn btn-danger" data-banner_type="<%= banner.type %>" data-banner_id="<%= banner.id %>">Delete</button>
                    <button class="btn-save btn btn-primary" data-banner_type="<%= banner.type %>" data-banner_id="<%= banner.id %>">Save</button>
                </div>
            </div>
        </div>
    </div>
</script>

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
                <span>Banners</span>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="grid-content overflow">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label>Banner Package</label>
                    <select id="banner_package">
                        <option value="" {{$franchise->banner_package == '' ? 'selected="selected"' : ''}}>None</option>
                        <option value="basic" {{$franchise->banner_package == 'basic' ? 'selected="selected"' : ''}}>Basic</option>
                        <option value="premium" {{$franchise->banner_package == 'premium' ? 'selected="selected"' : ''}}>Premium</option>
                    </select>
                </div>
            </div>
            <div id="homepageRow" class="row" {{$franchise->banner_package == 'premium' ? '' : 'style="display: none;"'}}>
                <div class="col-md-12">
                    <legend>Homepage Banner (980X250)</legend>
                    <div id="homepageArea"></div>
                    <div id="homepage-creation">
                        <div id="homepage-form" class="row">
                            <form enctype="multipart/form-data" action="/wizard-banner-upload" method="POST" target="ieSubmitFrame">
                                <div class="col-md-12 form-group">
                                    <label>Create Banner</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <span class="btn btn-primary btn-file">
                                                Browse <input name="upload_img" type="file" data-type="homepage">
                                            </span>
                                        </span>
                                        <input id="homepage-image" class="form-control" type="text">
                                        <input name="type" type="hidden" value="homepage">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-success">Upload</button>
                                        </span>
                                    </div>
                                </div>
                            </form>
                            <button id="homepageDoneButton" type="button" style="display: none;"></button>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>
                                    <input id="homepage-demo" type="checkbox" style="display:inline"> Is Demo?
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Radius (Miles)</label>
                                <input type="text" class="form-control" id="homepage-radius">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Locations</label>
                                <div class="input-group">
                                    <select id="homepage-locations" class="form-control">
                                        <option value="0">All Locations</option>
                                        @foreach($locations as $location)
                                        <option value="{{$location->id}}">{{$location->name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn-more-locations btn btn-sm"><i class="icon-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Custom Url</label>
                                <div class="input-group">
                                    <input id="homepage-url" type="text" class="form-control">
                                    <span class="input-group-btn">
                                        <button class="btn-create-new btn btn-success" data-type="homepage">Create</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="allCouponsRow" class="row" {{$franchise->banner_package == 'premium' ? '' : 'style="display: none;"'}}>
                <div class="col-md-12">
                    <legend>All Coupons Banner (300X250)</legend>
                    <div id="allCouponsArea"></div>
                    <div id="all-coupons-creation">
                        <div id="all-coupons-form" class="row">
                            <form enctype="multipart/form-data" action="/wizard-banner-upload" method="POST" target="ieSubmitFrame">
                                <div class="col-md-12 form-group">
                                    <label>Create Banner</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <span class="btn btn-primary btn-file">
                                                Browse <input name="upload_img" type="file" data-type="all-coupons">
                                            </span>
                                        </span>
                                        <input id="all-coupons-image" class="form-control" type="text">
                                        <input name="type" type="hidden" value="all-coupons">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-success">Upload</button>
                                        </span>
                                    </div>
                                </div>
                            </form>
                            <button id="all-couponsDoneButton" type="button" style="display: none;"></button>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>
                                    <input id="all-coupons-demo" type="checkbox" style="display:inline"> Is Demo?
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Radius (Miles)</label>
                                <input type="text" class="form-control" id="all-coupons-radius">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Locations</label>
                                <div class="input-group">
                                    <select id="all-coupons-locations" class="form-control">
                                        <option value="0">All Locations</option>
                                        @foreach($locations as $location)
                                        <option value="{{$location->id}}">{{$location->name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn-more-locations btn btn-sm"><i class="icon-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Custom Url</label>
                                <div class="input-group">
                                    <input id="all-coupons-url" type="text" class="form-control">
                                    <span class="input-group-btn">
                                        <button class="btn-create-new btn btn-success" data-type="all-coupons">Create</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="subcategoryRow" class="row" {{$franchise->banner_package == 'premium' ? '' : ($franchise->banner_package == 'basic' ? '' : 'style="display: none;"')}}>
                <div class="col-md-12">
                    <legend>Subcategory Banner (300X250)</legend>
                    <div id="subcategoryArea"></div>
                    <div id="subcategory-creation">
                        <div id="subcategory-form" class="row">
                            <form enctype="multipart/form-data" action="/wizard-banner-upload" method="POST" target="ieSubmitFrame">
                                <div class="col-md-12 form-group">
                                    <label>Create Banner</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <span class="btn btn-primary btn-file">
                                                Browse <input name="upload_img" type="file" data-type="subcategory">
                                            </span>
                                        </span>
                                        <input id="subcategory-image" class="form-control" type="text">
                                        <input name="type" type="hidden" value="subcategory">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-success">Upload</button>
                                        </span>
                                    </div>
                                </div>
                            </form>
                            <button id="subcategoryDoneButton" type="button" style="display: none;"></button>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>
                                    <input id="subcategory-demo" type="checkbox" style="display:inline"> Is Demo?
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Radius (Miles)</label>
                                <input type="text" class="form-control" id="subcategory-radius">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Locations</label>
                                <div class="input-group">
                                    <select id="subcategory-locations" class="form-control">
                                        <option value="0">All Locations</option>
                                        @foreach($locations as $location)
                                        <option value="{{$location->id}}">{{$location->name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn-more-locations btn btn-sm"><i class="icon-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Custom Url</label>
                                <div class="input-group">
                                    <input id="subcategory-url" type="text" class="form-control">
                                    <span class="input-group-btn">
                                        <button class="btn-create-new btn btn-success" data-type="subcategory">Create</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="keywordRow" class="row" {{$franchise->banner_package == 'premium' ? '' : 'style="display: none;"'}}>
                <div class="col-md-12">
                    <legend>Keyword Search Banners (980x250)</legend>
                    <div id="keywordSearchArea"></div>
                    <div id="keyword-creation">
                        <div id="keyword-form" class="row">
                            <form enctype="multipart/form-data" action="/wizard-banner-upload" method="POST" target="ieSubmitFrame">
                                <div class="col-md-12 form-group">
                                    <label>Create Banner</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <span class="btn btn-primary btn-file">
                                                Browse <input name="upload_img" type="file" data-type="keyword">
                                            </span>
                                        </span>
                                        <input id="keyword-image" class="form-control" type="text">
                                        <input name="type" type="hidden" value="keyword">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-success">Upload</button>
                                        </span>
                                    </div>
                                </div>
                            </form>
                            <button id="keywordDoneButton" type="button" style="display: none;"></button>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>
                                    <input id="keyword-demo" type="checkbox" style="display:inline"> Is Demo?
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Radius (Miles)</label>
                                <input type="text" class="form-control" id="keyword-radius">
                            </div>
                            <div class="col-md-8 form-group">
                                <label>Keywords</label>
                                <input type="text" class="form-control" id="keywords">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Locations</label>
                                <div class="input-group">
                                    <select id="keyword-locations" class="form-control">
                                        <option value="0">All Locations</option>
                                        @foreach($locations as $location)
                                        <option value="{{$location->id}}">{{$location->name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn-more-locations btn btn-sm"><i class="icon-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Custom Url</label>
                                <div class="input-group">
                                    <input id="keyword-url" type="text" class="form-control">
                                    <span class="input-group-btn">
                                        <button class="btn-create-new btn btn-success" data-type="keyword">Create</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="divider"><hr></div>

            <div class="" style="padding-bottom: 0px;">
                <div class="pull-left">
                    <a id="btnPrevStep" href="/event?viewing={{$franchise_id}}" class="btn btn-primary"><i class="icon-hand-left"></i>  Back</a>
                </div>
                <div class="pull-right">
                    <a id="btnNextStep" href="/about?viewing={{$franchise_id}}" class="btn btn-success">Next  <i class="icon-hand-right"></i></a>
                </div>
            </div>
            <iframe name="ieSubmitFrame" id="ieSubmitFrame" height="0" border="0" scrolling="auto" style="display: none;" src=""></iframe>
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