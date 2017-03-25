@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.content.master.templates.sidebar', array())
@stop
@section('body')
<script type="text/ejs" id="template_coupon">
<% list(results, function(result){ %>
  <tr class="row-selectable <%= result.is_followup_for == 0 ? '' : 'is-follow-up' %>" data-offer_id="<%= result.id %>" data-toggle="tooltip" data-placement="right" title="Edit This Coupon">
    <td colspan="3" style="padding:0px;">
        <table>
            <tr class="row-select" data-offer_id="<%= result.id %>">
                <td style="border:none;"><%= result.name %></td>
                <td style="border:none;"><%= result.starts_at %></td>
                <td style="border:none;"><%= result.expires_at %></td>
            </tr>
        </table>
    </td>
    <td><a class="btn btn-link btn-status" data-offer_id="<%= result.id %>" data-status="<%= result.is_active %>"><%= result.is_active == 1 ? 'Active' : 'Inactive' %></a></td>
  </tr>
<% }) %>
</script>

<script type="text/ejs" id="template_location">
<% list(locations, function(loc){ %>
  <option value="<%= loc.id %>"><%= loc.name %></option>
<% }) %>
</script>

<script type="text/ejs" id="template_subcategory">
    <% list(subcategories, function(subcat){ %>
      <option value="<%= subcat.id %>"><%= subcat.name %></option>
    <% }) %>
</script>

<script type="text/ejs" id="template_model">
<% list(models, function(model){ %>
    <option value="<%= model.id %>"><%= model.name %></option>
<% }) %>
</script>

<script>
    currentPage = 0;
    selectedOffer = 0;
    selectedFranchise = "{{ $franchise_id }}";
    selectedMerchant = "{{ $merchant_id }}";
    img_path = "";
    selectedCategory = 0;
    selectedSubcategory = 0;
    selectedPage = 0;
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
                    <span>Coupons</span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="grid-content overflow">
                <div class="row">
                    <div class="col-md-5">
                        <div class="">
                            <input id="coupSelection" class="form-control search-query fill-up" style="border-radius: 15px;" type="text" placeholder="Search Coupons">
                            <input id="showInactive" type="checkbox"> 
                            <label class="checkbox" for="showInactive">
                                <span></span>Show Inactive
                            </label>
                        </div>
                        <div class="">
                            <table class="table box" style="margin-top: 10px;">
                                <thead>
                                    <tr>
                                        <th>Coupon</th>
                                        <th>Start</th>
                                        <th>Expire</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="coupResultsArea">

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4">
                                            <button class="btn btn-xs is-follow-up">&nbsp</button> - <small>Denotes Follow Up Offer</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <div class="pagination" style="margin: 0px;">
                                              <ul class="pagination" id="coupPaginationBottom">
                                                <li><a id="coupPrev" data-type="coup">&lsaquo; Prev</a></li>
                                                <li><span id="coupLblCurrentPage" style="color: #0088CC;"></span></li>
                                                <li><a id="coupNext" data-type="coup">Next &rsaquo;</a></li>
                                              </ul>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div id="titleArea" class="col-md-7">
                        <div class="lead">
                            Coupon Details
                        </div>
                        <hr>
                        <div class="">
                            <div class="pull-right">
                                <span class="coupon-messages" style="display:none;"></span>
                                <button type="button" class="btn btn-info btn-copy" style="display:none; margin-right: 10px;">Copy</button>
                                <button type="button" class="btn btn-primary btn-save">Save New</button>
                                <button type="button" class="btn btn-success btn-add-coupon" style="display:none; margin-right: 10px;">Add New</button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Coupon Title*</label>
                                <div class="controls">
                                    <input id="name" name="name" class="form-control fill-up" type="text" placeholder="Coupon Title*">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Coupon Short Title</label>
                                <div class="controls row">
                                    <div class="col-xs-6">
                                        <input id="short_name_line1" name="short_name_line1" class="form-control" type="text" placeholder="Coupon Short Title Line 1">
                                    </div>
                                    <div class="col-xs-6">
                                        <input id="short_name_line2" name="short_name_line2" class="form-control" type="text" placeholder="Coupon Short Title Line 2">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Coupon Image</label>
                                <div class="controls input-group">
                                    <input id="image" name="image" class="form-control" type="text">
                                    <span class="input-group-btn">
                                        <button id="btnGallery" class="btn btn-primary" style="border-radius:0" data-toggle="modal" data-target="#myModal">Choose</button>
                                        <button id="btnGalleryUpload" class="btn btn-success" style="" data-toggle="modal" data-target="#uploadModal">Upload New</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <form enctype="multipart/form-data" action="/wizard-imgupload" method="POST" target="ieSubmitFrame">
                                <div class="col-md-12 form-group">
                                    <label>Coupon Secondary Image</label>
                                    <div class="controls input-group">
                                        <span class="input-group-btn">
                                            <span class="btn btn-primary btn-file">
                                                Browse <input id="coupon" name="upload_img" type="file">
                                            </span>
                                        </span>
                                        <input id="secondary_image" class="form-control" type="text">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-success">Upload</button>
                                        </span>
                                        <input id="secondaryTypeInput" name="type" type="hidden" value="secondary_image">
                                    </div>
                                </div>
                            </form>
                            <button id="ieSecondaryDoneButton" type="button" style="display: none;"></button>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label class="control-label" for="description">Disclaimer*</label>
                                <div class="controls">
                                    <textarea id="description" name="description" class="form-control fill-up" rows="6"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="clearfix"></div>
                    <div class="row">
                        <div id="detailsArea" class="col-md-12">
                            <div class="row">

                                <div class="col-md-3 form-group">
                                    <label>Max Prints</label>
                                    <div class="controls">
                                        <select name="max_prints" id="max_prints" class="form-control fill-up">
                                            @for($i = 1; $i <= 10; $i++)
                                            <option value="{{$i}}" {{$i==1 ? 'selected="selected"' : ''}}>{{$i}}</option>
                                            @endfor
                                            <option value="-1">No Limit</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 col-md-offset-1 form-group">
                                    <label>Demo Status</label>
                                    <div class="controls">
                                        <select name="is_demo" id="is_demo" class="form-control fill-up">
                                            <option value="1">Demo</option>
                                            <option value="0" selected="selected">Live</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 col-md-offset-1">
                                    <label>Featured Offer</label>
                                    <select id="is_featured_offer" class="form-control fill-up">
                                        <option value="0" selected="selected">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <label>Savings</label>
                                    <div class="controls input-group">
                                        <span class="input-group-btn">
                                            <button class="btn" style="cursor:default;">$</button>
                                        </span>
                                        <input id="savings" name="savings" class="form-control fill-up" type="text" placeholder="Savings">
                                    </div>
                                </div>
                                <div class="col-md-3 col-md-offset-1 form-group">
                                    <label>Coupon Code</label>
                                    <div class="controls">
                                        <input id="code" class="form-control fill-up" type="text" placeholder="Code">
                                    </div>
                                </div>
                                <div class="col-md-3 col-md-offset-1 form-group">
                                    <label>Coupon Type</label>
                                    <div class="controls">
                                        <select id="coupon_type" class="form-control fill-up" >
                                            <option value='simple' selected="selected">Simple</option>
                                            <option value='savetoday'>Save Today</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <div>
                                        <label>Start Date*</label>
                                        <label>
                                            <input id="is_reoccurring" type="checkbox" style="display:inline;">
                                            Reoccurring?
                                        </label>
                                    </div>
                                    <div class="controls input-group">
                                        <input id="starts_at" name="starts_at" class="form-control fill-up" type="text" placeholder="Start Date">
                                        <span class="input-group-btn">
                                            <button id="startNow" class="btn"><i class="icon-time"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-md-offset-1 form-group">
                                    <div>
                                        <label>Expiration Date*</label>
                                        <label>
                                            <input id="hide_expiration" type="checkbox" style="display:inline;">
                                            Hide?
                                        </label>
                                    </div>
                                    <div class="controls">
                                        <input id="expires_at" name="expires_at" class="form-control fill-up" type="text" placeholder="Expiration Date">
                                    </div>
                                </div>
                                <div class="col-md-3 col-md-offset-1 form-group">
                                    <label>Status</label>
                                    <div class="controls">
                                        <select id="status" class="form-control fill-up">
                                            <option value='1' selected="selected">Active</option>
                                            <option value='0'>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Black Friday Only -->
                            <div class="row" style="display:none" id="blkfriday">
                                <div class="col-md-3  form-group" id="savetdy" >
                                    <label>Quantity</label>
                                    <div class="controls">
                                        <input id="quantity" class="form-control fill-up" type="number" placeholder="Quantity">
                                    </div>
                                </div>
                                <div class="col-md-3 form-group" id="regularPrice">
                                    <label>Regular Price</label>
                                    <div class="controls">
                                        <input id="regularprice" class="form-control fill-up" type="number" placeholder="Regular Price">
                                    </div>
                                </div>
                                <div class="col-md-3  col-md-offset-1 form-group" >
                                    <label>Special Price</label>
                                    <div class="controls">
                                        <input id="specialprice" class="form-control fill-up" type="number" placeholder="Special Price">
                                    </div>
                                </div>
                            </div>
                            <!-- save today Only -->
                            <!-- <div class="row" style="display:none" id="savetdy">
                                <div class="col-md-3  form-group" >
                                    <label>Regular Price</label>
                                    <div class="controls">
                                        <input id="regularprice" class="form-control fill-up" type="number" placeholder="Regular Price">
                                    </div>
                                </div>
                                <div class="col-md-3  col-md-offset-1 form-group" >
                                    <label>Special Price</label>
                                    <div class="controls">
                                        <input id="specialprice" class="form-control fill-up" type="number" placeholder="Special Price">
                                    </div>
                                </div>
                            </div> -->

                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label>Participating Locations</label>
                                    <div class="input-group">
                                        <select id="locations" class="form-control">
                                            <option value="">All</option>
                                            @foreach($locations as $location)
                                                <option value="{{$location->id}}">{{$location->name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-btn">
                                            <button id="moreLocations" type="button" class="btn btn-sm" style="height: 28px;"><i class="icon-plus"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-8 form-group">
                                    <label>Print Button Link</label>
                                    <input id="print_override" class="form-control fill-up" type="text">
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Requires Member To Print</label>
                                    <select class="form-control" id="member_print">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Mobile Only</label>
                                    <select class="form-control" id="is_mobile_only">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Secondary Type</label>
                                    <select class="form-control" id="secondary_type">
                                        <option value="">None</option>
                                        <option value="lease">Lease</option>
                                        <option value="purchase">Purchase</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Custom Category</label>
                                    <select class="form-control" id="custom_category_id">
                                        <option value="0">Same As Merchant</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Custom Subcategory</label>
                                    <select class="form-control" id="custom_subcategory_id" disabled="disabled">
                                        <option value="0">Same As Merchant</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Category Visible</label>
                                    <select class="form-control" id="category_visible">
                                        <option value="1">Visible</option>
                                        <option value="0">Hidden</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row lease-info" style="display:none">
                                <div class="col-md-3">
                                    <label>Year</label>
                                    <input id="lease_year" class="form-control fill-up" type="text">
                                </div>
                                <div class="col-md-3">
                                    <label>Make</label>
                                    <select id="lease_make" class="form-control fill-up">
                                        <option value="0">Select</option>
                                        @foreach($makes as $make)
                                        <option value="{{$make->id}}">{{$make->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Model</label>
                                    <select id="lease_model" class="form-control fill-up">
                                        <option value="0">Select</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="">
                    <div class="pull-right">
                        <span class="coupon-messages" style="display:none;"></span>
                        <button type="button" class="btn btn-info btn-copy" style="display:none; margin-right: 10px;" data-loading-text="Loading...">Copy</button>
                        <button type="button" class="btn btn-primary btn-save" data-loading-text="Loading...">Save New</button>
                        <button type="button" class="btn btn-success btn-add-coupon" style="display:none; margin-right: 10px;">Add New</button>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="divider"><hr></div>

                <div class="" style="padding-bottom: 0px;">
                    <div class="pull-left">
                        <a id="btnPrevStep" href="/location?viewing={{$franchise_id}}" class="btn btn-primary"><i class="icon-hand-left"></i>  Back</a>
                    </div>
                    <div class="pull-right">
                        <a id="btnNextStep" href="/event?viewing={{$franchise_id}}" class="btn btn-success">Next  <i class="icon-hand-right"></i></a>
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

    <!-- Modal -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Coupon Image Gallery</h3>
                <div class="clear-fix"></div>
                <div class="row">
                    <div class="col-md-6">
                        <select id="imgCategory" class="form-control">
                            <option value="0">Category</option>
                            @foreach($categories as $cat)
                            <option value="{{$cat->id}}">{{$cat->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select id="imgSubCategory" class="form-control" disabled="disabled">
                            <option value="0">Subategory</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div id="gallerySelected">
                </div>
                <div id="gallery">
                <script type='text/ejs' id='template_image'>
                    <% var i=1; list(images, function(image){ if(image.id){%>
                        <img class="img-polaroid col-md-3" style="cursor:pointer;" src="<%= image.path %>"  data-img_id="<%= image.id %>" data-path="<%= image.path %>" data-toggle="tooltip" title="Use This Image"/>
                        <% if(i++ % 4 == 0){ %>
                            </div><div>
                        <% } %>
                    <% }}) %>
                    <div class="clearfix"></div>
                </script>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <div class="pull-left">
                  <ul class="pagination" id="imgPaginationBottom" style="margin: 0px;">
                    <li><a class="pointer" id="imgFirst">&lsaquo;&lsaquo; First</a></li>
                    <li><a class="pointer" id="imgPrev">&lsaquo; Prev</a></li>
                    <li><span id="imgLblCurrentPage" style="color: #0088CC;"></span></li>
                    <li><a class="pointer" id="imgNext">Next &rsaquo;</a></li>
                    <li><a class="pointer" id="imgLast">Last &rsaquo;&rsaquo;</a></li>
                  </ul>
                </div>
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="uploadModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Upload Image
                    <div class="pull-right" style="padding-right: 10px;">
                        
                    </div>
                </h3>
            </div>
            <div class="modal-body">
                <div>
                    <form enctype="multipart/form-data" action="/wizard-imgupload" method="POST" target="ieSubmitFrame" onsubmit="return upload_control.Validate();">
                        <div class="row">
                            <div class="col-md-6">
                                <select id="uploadCategory" name="category_id" class="form-control">
                                    <option value="0">Category</option>
                                    @foreach($categories as $cat)
                                    <option value="{{$cat->id}}">{{$cat->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select id="uploadSubCategory" name="subcategory_id" disabled="disabled" class="form-control">
                                    <option value="0">Subategory</option>
                                </select>
                            </div>
                        </div>
                        <div id="logoUpload" class="form-group" style="margin-top: 10px;">
                            <label class="control-label" for="coupon">Upload a Gallery Image</label>
                            <div class="controls">
                                <input id="coupon" name="upload_img" type="file" class="form-control fill-up margin-bottom-10">
                                <button id="btnUploadGallery" type="submit" class="btn">Upload</button>
                                <input name="type" type="hidden" value="gallery">
                            </div>
                        </div>
                    </form>
                    <iframe name="ieSubmitFrame" id="ieSubmitFrame" height="0" border="0" scrolling="auto" style="display: none;" src=""></iframe>
                    <button id="ieDoneButton" type="button" style="display: none;"></button>
                </div>
            </div>
            <div class="modal-footer">
                <span id="uploadMessages" style="display:none; margin-right:5px;"></span> 
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
            </div>
        </div>
    </div>
</div>

@stop