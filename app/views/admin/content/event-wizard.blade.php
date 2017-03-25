@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.content.master.templates.sidebar', array())
@stop
@section('body')
<script type="text/ejs" id="template_event">
<% list(results, function(result){ %>
  <tr class="row-selectable" data-event_id="<%= result.id %>" data-toggle="tooltip" data-placement="right" title="Edit This Event">
    <td class="row-select" data-event_id="<%= result.id %>" colspan="3" style="padding:0px;">
        <table>
            <tr>
                <td style="border:none;"><%= result.name %></td>
                <td style="border:none;"><%= result.starts_at %></td>
                <td style="border:none;"><%= result.expires_at %></td>
            </tr>
        </table>
    </td>
    <td><a class="btn btn-link btn-status" data-event_id="<%= result.id %>" data-status="<%= result.is_active %>"><%= result.is_active == 1 ? 'Active' : 'Inactive' %></a></td>
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

<script>
    currentPage = 0;
    selectedEvent = 0;
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
                    <span>Events</span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="grid-content overflow">
                <div class="row">
                    <div class="col-md-5">
                        <div class="">
                            <input id="eventSelection" class="form-control search-query fill-up" style="border-radius: 15px;" type="text" placeholder="Search Events">
                            <input id="showInactive" type="checkbox"> 
                            <label class="checkbox" for="showInactive">
                                <span></span>Show Inactive
                            </label>
                        </div>
                        <div class="">
                            <table class="table box" style="margin-top: 10px;">
                                <thead>
                                    <tr>
                                        <th>Event</th>
                                        <th>Start</th>
                                        <th>Expire</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="eventResultsArea">

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4">
                                            <div class="pagination" style="margin: 0px;">
                                              <ul class="pagination" id="eventPaginationBottom">
                                                <li><a id="eventPrev" data-type="event">&lsaquo; Prev</a></li>
                                                <li><span id="eventLblCurrentPage" style="color: #0088CC;"></span></li>
                                                <li><a id="eventNext" data-type="event">Next &rsaquo;</a></li>
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
                            Event Details
                        </div>
                        <hr>
                        <div class="">
                            <div class="pull-right">
                                <span class="event-messages" style="display:none;"></span>
                                <button type="button" class="btn btn-info btn-copy" style="display:none; margin-right: 10px;">Copy</button>
                                <button type="button" class="btn btn-primary btn-save">Save New</button>
                                <button type="button" class="btn btn-success btn-add-event" style="display:none; margin-right: 10px;">Add New</button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Event Title*</label>
                                <div class="controls">
                                    <input id="name" name="name" class="form-control fill-up" type="text" placeholder="Event Title*">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Event Short Title</label>
                                <div class="controls row">
                                    <div class="col-xs-6">
                                        <input id="short_name_line1" name="short_name_line1" class="form-control" type="text" placeholder="Event Short Title Line 1">
                                    </div>
                                    <div class="col-xs-6">
                                        <input id="short_name_line2" name="short_name_line2" class="form-control" type="text" placeholder="Event Short Title Line 2">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Event Image</label>
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
                                    <label>Demo Status</label>
                                    <div class="controls">
                                        <select name="is_demo" id="is_demo" class="form-control fill-up">
                                            <option value="1">Demo</option>
                                            <option value="0" selected="selected">Live</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 col-md-offset-1">
                                    <label>Featured Event</label>
                                    <select id="is_featured" class="form-control fill-up">
                                        <option value="0" selected="selected">No</option>
                                        <option value="1">Yes</option>
                                    </select>
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
                            <div class="row">

                                <div class="col-md-3 form-group">
                                    <label>Post Date*</label>
                                    <div class="controls input-group">
                                        <input id="starts_at" name="starts_at" class="form-control fill-up" type="text" placeholder="Start Date">
                                        <span class="input-group-btn">
                                            <button id="startNow" class="btn"><i class="icon-time"></i></button>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-3 col-md-offset-1 form-group">
                                    <label>Expiration Date*</label>
                                    <div class="controls">
                                        <input id="expires_at" name="expires_at" class="form-control fill-up" type="text" placeholder="Expiration Date">
                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-md-3 form-group">
                                    <label>Event Start Date*</label>
                                    <div class="controls">
                                        <input id="event_start" name="event_start" class="form-control fill-up" type="text" placeholder="Start Date">
                                    </div>
                                </div>

                                <div class="col-md-3 col-md-offset-1 form-group">
                                    <label>Event End Date*</label>
                                    <div class="controls">
                                        <input id="event_end" name="event_end" class="form-control fill-up" type="text" placeholder="End Date">
                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-md-4 form-group">
                                    <label>Participating Locations</label>
                                    <div class="input-group controls">
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

                                <div class="col-md-7 form-group col-md-offest-1">
                                    <label>Website</label>
                                    <div class="controls">
                                        <input id="website" name="website" class="form-control" type="text" placeholder="Website">
                                    </div>
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
                        </div>
                    </div>

                <div class="">
                    <div class="pull-right">
                        <span class="event-messages" style="display:none;"></span>
                        <button type="button" class="btn btn-info btn-copy" style="display:none; margin-right: 10px;" data-loading-text="Loading...">Copy</button>
                        <button type="button" class="btn btn-primary btn-save" data-loading-text="Loading...">Save New</button>
                        <button type="button" class="btn btn-success btn-add-event" style="display:none; margin-right: 10px;">Add New</button>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="divider"><hr></div>

                <div class="" style="padding-bottom: 0px;">
                    <div class="pull-left">
                        <a id="btnPrevStep" href="/coupon?viewing={{$franchise_id}}" class="btn btn-primary"><i class="icon-hand-left"></i>  Back</a>
                    </div>
                    <div class="pull-right">
                        <a id="btnNextStep" href="/banner?viewing={{$franchise_id}}" class="btn btn-success">Next  <i class="icon-hand-right"></i></a>
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
                <h3 id="myModalLabel">Event Image Gallery</h3>
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
                <div id="gallery">
                <script type='text/ejs' id='template_image'>
                    <% var i=1; list(images, function(image){ if(image.id){%>
                        <img class="img-polaroid col-md-3" style="cursor:pointer;" src="<%= image.path %>"  data-img_id="<%= image.id %>" data-path="<%= image.path %>" data-toggle="tooltip" title="Use This Image"/>
                        <% if(i++ % 4 == 0){ %>
                            </div><div>
                        <% } %>
                    <% }}) %>
                </script>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <div class="pull-left">
                  <ul class="pagination" id="imgPaginationBottom" style="margin: 0px;">
                    <li><a id="imgPrev">&lsaquo; Prev</a></li>
                    <li><span id="imgLblCurrentPage" style="color: #0088CC;"></span></li>
                    <li><a id="imgNext">Next &rsaquo;</a></li>
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