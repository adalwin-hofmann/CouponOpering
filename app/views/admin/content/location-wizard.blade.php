@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.content.master.templates.sidebar', array())
@stop
@section('body')
<script type="text/ejs" id="template_location">
<% list(results, function(result){ %>
  <tr class="row-selectable" data-location_id="<%= result.id %>" data-toggle="tooltip" title="Edit This Location" data-placement="right">
    <td><%= result.name %></td>
    <td><%= result.city %></td>
    <td><%= result.zip %></td>
  </tr>
<% }) %>
</script>

<script>
    currentPage = 0;
    selectedLocation = 0;
    selectedMerchant = "{{ $merchant_id }}";
    selectedFranchise = "{{ $franchise_id }}";
    img_path = "";
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
                    <span>Locations</span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="grid-content overflow">
                <div class="row">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <input id="locSelection" class="form-control search-query input-block-level" style="border-radius: 15px;" type="text" placeholder="Search Locations">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <label class="checkbox-inline">
                                    <input id="showInactive" type="checkbox"> Show Inactive
                                </label>
                                <label class="checkbox-inline">
                                    <input id="showDeleted" type="checkbox"> Show Deleted
                                </label>
                            </div>
                            <!--<div class="col-md-6">
                                <input id="showInactive" type="checkbox">
                                <label class="checkbox" for="showInactive">
                                    <span></span> Show Inactive
                                </label>
                            </div>
                            <div class="col-md-6">
                                <input id="showDeleted" type="checkbox">
                                <label class="checkbox" for="showDeleted">
                                    <span></span> Show Deleted
                                </label>
                            </div>-->
                        </div>
                        <div class="">
                            <table class="table box" style="margin-top: 10px;">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>City</th>
                                        <th>Zipcode</th>
                                    </tr>
                                </thead>
                                <tbody id="locResultsArea">

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3">
                                            <div class="pagination" style="margin: 0px;">
                                              <ul class="pagination" id="locPaginationBottom">
                                                <li><a id="locPrev" data-type="loc" href="#">&lsaquo; Prev</a></li>
                                                <li><span id="locLblCurrentPage" style="color: #0088CC;"></span></li>
                                                <li><a id="locNext" data-type="loc" href="#">Next &rsaquo;</a></li>
                                              </ul>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div id="addressArea" class="col-md-8">
                        <legend>Address</legend>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Location Name</label>
                                <div class="controls">
                                    <input id="name" name="name" class="form-control input-block-level" type="text" placeholder="Location Name*">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Display Name</label>
                                <div class="controls">
                                    <input id="display_name" name="display_name" class="form-control input-block-level" type="text" placeholder="Display Name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="is_address_hidden" name="is_address_hidden" style="display:block">
                                        Hide Address?
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-8 form-group">
                                <label>Serving Area / Custom Address Text</label>
                                <div class="controls">
                                    <input id="custom_address_text" name="custom_address_text" class="form-control input-block-level" type="text" placeholder="Serving Oakland County">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Street Address</label>
                                <div class="controls">
                                    <input id="address" name="address" class="form-control input-block-level" type="text" placeholder="Street Address*">
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Address Line 2</label>
                                <div class="controls">
                                    <input id="address2" name="address2" class="form-control input-block-level" type="text" placeholder="Address Line 2">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>City</label>
                                <div class="controls">
                                    <input id="city" name="city" class="form-control input-block-level" type="text" placeholder="City*">
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>State</label>
                                <div class="controls">
                                    <select id="state" name="state" class="form-control input-block-level">
                                        <option value="">State*</option>
                                        @foreach($states as $abbr => $state)
                                        <option value="{{$abbr}}">{{ucwords(strtolower($state))}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Zip Code</label>
                                <div class="controls">
                                    <input id="zipcode" name="zipcode" class="form-control input-block-level" type="text" placeholder="Zip Code*">
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Phone Number</label>
                                <div class="controls">
                                    <input id="phone" name="phone" class="form-control input-block-level" type="text" placeholder="Phone Number*">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Fax</label>
                                <div class="controls">
                                    <input id="fax" name="fax" class="form-control input-block-level" type="text" placeholder="Fax">
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Website</label>
                                <div class="controls">
                                    <input id="website" name="website" class="form-control input-block-level" type="text" placeholder="Website">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Facebook</label>
                                <div class="controls">
                                    <input id="facebook" name="facebook" class="form-control input-block-level" type="text" placeholder="Facebook">
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Twitter</label>
                                <div class="controls">
                                    <input id="twitter" name="twitter" class="form-control input-block-level" type="text" placeholder="Twitter">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Status</label>
                                <div class="controls">
                                    <select id="status" class="form-control input-block-level">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Company</label>
                                <div class="controls">
                                    <select id="company_id" class="form-control input-block-level">
                                        <option value="0">None</option>
                                        @foreach($companies as $company)
                                        <option value="{{$company->id}}">{{$company->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Location Deleted?</label>
                                <div class="controls">
                                    <select id="is_deleted" class="form-control input-block-level">
                                        <option value="1">Yes</option>
                                        <option value="0" selected="selected">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Twilio Redirect Number</label>
                                <div class="controls">
                                    <input id="redirect_number" name="redirect_number" class="form-control input-block-level" type="text" placeholder="Phone">
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Twilio Redirect Text</label>
                                <div class="controls">
                                    <input id="redirect_text" name="redirect_text" class="form-control input-block-level" type="text" placeholder="Click To Call">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Custom Website URL</label>
                                <div class="controls">
                                    <input id="custom_website" name="custom_website" class="form-control input-block-level" type="text" placeholder="Website">
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Custom Website Link</label>
                                <div class="controls">
                                    <input id="custom_website_text" name="custom_website_text" class="form-control input-block-level" type="text" placeholder="Website Link">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 form-group">
                        <label>Subheader Text</label>
                        <textarea id="subheader" name="subheader" class="fill-up"; rows="10"></textarea>
                    </div>
                    <div class="col-md-4 form-group">
                        <div id="divDynamic">
                            <legend>Dynamic Text</legend>
                            <div class="row">
                                <button type="button" class="col-md-4 btn btn-primary dynamic property-top" data-text="[merchant]">Merchant</button>
                                <button type="button" class="col-md-4 btn btn-primary dynamic property-top" data-text="[address]">Address</button>
                                <button type="button" class="col-md-4 btn btn-primary dynamic property-top" data-text="[city]">City</button>
                            </div>
                            <div class="row">
                                <button type="button" class="col-md-4 btn btn-primary dynamic property-bottom" data-text="[state]">State</button>
                                <button type="button" class="col-md-4 btn btn-primary dynamic property-bottom" data-text="[phone]">Phone</button>
                                <button type="button" class="col-md-4 btn btn-primary dynamic property-bottom" data-text="[website]">Website</button>
                            </div>
                            <div class="row">
                                <button type="button" class="col-md-4 btn btn-primary dynamic proptery-bottom" data-text="[category]">Category</button>
                                <button type="button" class="col-md-4 btn btn-primary dynamic proptery-bottom" data-text="[subcategory]">Subcategory</button>
                                <button type="button" class="col-md-4 btn btn-primary dynamic proptery-bottom" data-text="[category_slug]">category-slug</button>
                            </div>
                            <div class="row">
                                <button type="button" class="col-md-4 btn btn-primary dynamic proptery-bottom" data-text="[subcategory_slug]">subcategory-slug</button>
                                <button type="button" class="col-md-4 btn btn-primary dynamic proptery-bottom" data-text="[city_slug]">city-slug</button>
                                <button type="button" class="col-md-4 btn btn-primary dynamic proptery-bottom" data-text="[state_lower]">state lower case</button>
                            </div>
                            <div class="row vpadded">
                                <button id="btnSynonym" type="button" class="col-md-6 col-md-offset-3 btn btn-primary dynamic" data-text="">Synonym List</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="is_logo_specific" style="display:block">
                                Has Own Logo
                            </label>
                        </div>
                        <div id="logoRow" class="row" style="display:none;">
                            <div class="col-md-12">
                                <div class="row form-group">
                                    <form enctype="multipart/form-data" class="col-md-12" action="/upload-location-image" method="POST" target="ieSubmitFrame">
                                        <label>Upload New Image</label>
                                        <div class="controls">
                                            <input type="file" name="upload_img">
                                            <input type="hidden" name="type" value="logo1">
                                            <input id="logoLocationId" type="hidden" name="location_id">
                                            <button type="submit" class="btn btn-primary">Upload</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <img id="logoImg" src="http://placehold.it/500X300">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="is_banner_specific" style="display:block">
                                Has Own Banner
                            </label>
                        </div>
                        <div id="bannerRow" class="row" style="display:none;">
                            <div class="col-md-12">
                                <div class="row form-group">
                                    <form enctype="multipart/form-data" class="col-md-12" action="/upload-location-image" method="POST" target="ieSubmitFrame">
                                        <label>Upload New Image</label>
                                        <div class="controls">
                                            <input type="file" name="upload_img">
                                            <input type="hidden" name="type" value="banner">
                                            <input id="bannerLocationId" type="hidden" name="location_id">
                                            <button type="submit" class="btn btn-primary">Upload</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <img id="bannerImg" src="http://placehold.it/988X250">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <iframe name="ieSubmitFrame" id="ieSubmitFrame" height="0" border="0" scrolling="auto" style="display: none;" src=""></iframe>
                <button id="ieDoneButton" type="button" style="display: none;"></button>

                <div id="hoursArea" class="row">
                    <div class="col-md-12">
                        <legend>
                            Hours
                            &nbsp;
                            &nbsp;
                            &nbsp;
                            <input id="is_24_hours" type="checkbox">
                            <label style="display:inline-block;" for="is_24_hours">
                                <span style="margin-top: -5px"></span>
                                 Open 24 Hours?
                            </label>
                        </legend>

                        
                        
                        <div class="row">
                            
                            <div class="col-md-6"><label>Bulk Hours Assignment</label>
                                <div class="row form-group">
                                    <div class="col-md-4 controls">
                                        <select id="Bulk_start" name="bulk_start" class="form-control input-block-level">
                                            <option value="">- Start Time -</option>
                                            @for ($i = 1; $i <= 24; $i++)
                                                <option value="{{str_pad(ceil($i/2), 2, '0', STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, '0', STR_PAD_LEFT)}}">{{str_pad(ceil($i/2), 2, "0", STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, "0", STR_PAD_LEFT)}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2 controls">
                                        <select id="Bulk_start_ampm" name="bulk_start_ampm" class="form-control input-block-level">
                                            <option value=''>---</option>
                                            <option value='AM'>AM</option>
                                            <option value='PM'>PM</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 controls">
                                        <select id="Bulk_end" name="bulk_end" class="form-control input-block-level">
                                            <option value="">- End Time -</option>
                                            @for ($i = 1; $i <= 24; $i++)
                                                <option value="{{str_pad(ceil($i/2), 2, '0', STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, '0', STR_PAD_LEFT)}}">{{str_pad(ceil($i/2), 2, "0", STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, "0", STR_PAD_LEFT)}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2 controls">
                                        <select id="Bulk_end_ampm" name="bulk_end_ampm" class="form-control input-block-level">
                                            <option value=''>---</option>
                                            <option value='AM'>AM</option>
                                            <option value='PM'>PM</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"><label>Apply To:</label>
                                <input id="allCheck" type="checkbox">
                                <label style="display:inline;" for="allCheck">
                                    <span></span>
                                     All
                                </label>
                                <input id="Sunday_bulkCheck" type="checkbox">
                                <label style="display:inline;" for="Sunday_bulkCheck">
                                    <span></span>
                                     Su
                                </label>
                                <input id="Monday_bulkCheck" type="checkbox">
                                <label style="display:inline;" for="Monday_bulkCheck">
                                    <span></span>
                                     M
                                </label>
                                <input id="Tuesday_bulkCheck" type="checkbox">
                                <label style="display:inline;" for="Tuesday_bulkCheck">
                                    <span></span>
                                     Tu
                                </label>
                                <input id="Wednesday_bulkCheck" type="checkbox">
                                <label style="display:inline;" for="Wednesday_bulkCheck">
                                    <span></span>
                                     W
                                </label>
                                <input id="Thursday_bulkCheck" type="checkbox">
                                <label style="display:inline;" for="Thursday_bulkCheck">
                                    <span></span>
                                     Th
                                </label>
                                <input id="Friday_bulkCheck" type="checkbox">
                                <label style="display:inline;" for="Friday_bulkCheck">
                                    <span></span>
                                     F
                                </label>
                                <input id="Saturday_bulkCheck" type="checkbox">
                                <label style="display:inline;" for="Saturday_bulkCheck">
                                    <span></span>
                                     Sa
                                </label>
                                <button id="btnBulkApply" type="button" class="btn btn-primary" style="margin-left:10px;">Apply</button>
                            </div>
                        </div>
                        <legend></legend>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Monday  <a class="hours-clear" data-day="Monday">Clear</a></label>
                                <div class="row form-group">
                                    <div class="col-md-4 controls">
                                        <select id="Monday_start" name="monday_start" class="form-control input-block-level">
                                            <option value="">- Start Time -</option>
                                            @for ($i = 1; $i <= 24; $i++)
                                                <option value="{{str_pad(ceil($i/2), 2, '0', STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, '0', STR_PAD_LEFT)}}">{{str_pad(ceil($i/2), 2, "0", STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, "0", STR_PAD_LEFT)}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2 controls">
                                        <select id="Monday_start_ampm" name="monday_start_ampm" class="form-control input-block-level">
                                            <option value=''>---</option>
                                            <option value='AM'>AM</option>
                                            <option value='PM'>PM</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 controls">
                                        <select id="Monday_end" name="monday_end" class="form-control input-block-level">
                                            <option value="">- End Time -</option>
                                            @for ($i = 1; $i <= 24; $i++)
                                                <option value="{{str_pad(ceil($i/2), 2, '0', STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, '0', STR_PAD_LEFT)}}">{{str_pad(ceil($i/2), 2, "0", STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, "0", STR_PAD_LEFT)}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2 controls">
                                        <select id="Monday_end_ampm" name="monday_end_ampm" class="form-control input-block-level">
                                            <option value=''>---</option>
                                            <option value='AM'>AM</option>
                                            <option value='PM'>PM</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Tuesday  <a class="hours-clear" data-day="Tuesday">Clear</a></label>
                                <div class="row form-group">
                                    <div class="col-md-4 controls">
                                        <select id="Tuesday_start" name="tuesday_start" class="form-control input-block-level">
                                            <option value="">- Start Time -</option>
                                            @for ($i = 1; $i <= 24; $i++)
                                                <option value="{{str_pad(ceil($i/2), 2, '0', STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, '0', STR_PAD_LEFT)}}">{{str_pad(ceil($i/2), 2, "0", STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, "0", STR_PAD_LEFT)}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2 controls">
                                        <select id="Tuesday_start_ampm" name="tuesday_start_ampm" class="form-control input-block-level">
                                            <option value=''>---</option>
                                            <option value='AM'>AM</option>
                                            <option value='PM'>PM</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 controls">
                                        <select id="Tuesday_end" name="tuesday_end" class="form-control input-block-level">
                                            <option value="">- End Time -</option>
                                            @for ($i = 1; $i <= 24; $i++)
                                                <option value="{{str_pad(ceil($i/2), 2, '0', STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, '0', STR_PAD_LEFT)}}">{{str_pad(ceil($i/2), 2, "0", STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, "0", STR_PAD_LEFT)}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2 controls">
                                        <select id="Tuesday_end_ampm" name="tuesday_end_ampm" class="form-control input-block-level">
                                            <option value=''>---</option>
                                            <option value='AM'>AM</option>
                                            <option value='PM'>PM</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label>Wednesday  <a class="hours-clear" data-day="Wednesday">Clear</a></label>
                                <div class="row form-group">
                                    <div class="col-md-4 controls">
                                        <select id="Wednesday_start" name="wednesday_start" class="form-control input-block-level">
                                            <option value="">- Start Time -</option>
                                            @for ($i = 1; $i <= 24; $i++)
                                                <option value="{{str_pad(ceil($i/2), 2, '0', STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, '0', STR_PAD_LEFT)}}">{{str_pad(ceil($i/2), 2, "0", STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, "0", STR_PAD_LEFT)}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2 controls">
                                        <select id="Wednesday_start_ampm" name="wednesday_start_ampm" class="form-control input-block-level">
                                            <option value=''>---</option>
                                            <option value='AM'>AM</option>
                                            <option value='PM'>PM</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 controls">
                                        <select id="Wednesday_end" name="wednesday_end" class="form-control input-block-level">
                                            <option value="">- End Time -</option>
                                            @for ($i = 1; $i <= 24; $i++)
                                                <option value="{{str_pad(ceil($i/2), 2, '0', STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, '0', STR_PAD_LEFT)}}">{{str_pad(ceil($i/2), 2, "0", STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, "0", STR_PAD_LEFT)}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2 controls">
                                        <select id="Wednesday_end_ampm" name="wednesday_end_ampm" class="form-control input-block-level">
                                            <option value=''>---</option>
                                            <option value='AM'>AM</option>
                                            <option value='PM'>PM</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Thursday  <a class="hours-clear" data-day="Thursday">Clear</a></label>
                                <div class="row form-group">
                                    <div class="col-md-4 controls">
                                        <select id="Thursday_start" name="thursday_start" class="form-control input-block-level">
                                            <option value="">- Start Time -</option>
                                            @for ($i = 1; $i <= 24; $i++)
                                                <option value="{{str_pad(ceil($i/2), 2, '0', STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, '0', STR_PAD_LEFT)}}">{{str_pad(ceil($i/2), 2, "0", STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, "0", STR_PAD_LEFT)}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2 controls">
                                        <select id="Thursday_start_ampm" name="thursday_start_ampm" class="form-control input-block-level">
                                            <option value=''>---</option>
                                            <option value='AM'>AM</option>
                                            <option value='PM'>PM</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 controls">
                                        <select id="Thursday_end" name="thursday_end" class="form-control input-block-level">
                                            <option value="">- End Time -</option>
                                            @for ($i = 1; $i <= 24; $i++)
                                                <option value="{{str_pad(ceil($i/2), 2, '0', STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, '0', STR_PAD_LEFT)}}">{{str_pad(ceil($i/2), 2, "0", STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, "0", STR_PAD_LEFT)}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2 controls">
                                        <select id="Thursday_end_ampm" name="thursday_end_ampm" class="form-control input-block-level">
                                            <option value=''>---</option>
                                            <option value='AM'>AM</option>
                                            <option value='PM'>PM</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Friday  <a class="hours-clear" data-day="Friday">Clear</a></label>
                                <div class="row form-group">
                                    <div class="col-md-4 controls">
                                        <select id="Friday_start" name="friday_start" class="form-control input-block-level">
                                            <option value="">- Start Time -</option>
                                            @for ($i = 1; $i <= 24; $i++)
                                                <option value="{{str_pad(ceil($i/2), 2, '0', STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, '0', STR_PAD_LEFT)}}">{{str_pad(ceil($i/2), 2, "0", STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, "0", STR_PAD_LEFT)}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2 controls">
                                        <select id="Friday_start_ampm" name="friday_start_ampm" class="form-control input-block-level">
                                            <option value=''>---</option>
                                            <option value='AM'>AM</option>
                                            <option value='PM'>PM</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 controls">
                                        <select id="Friday_end" name="friday_end" class="form-control input-block-level">
                                            <option value="">- End Time -</option>
                                            @for ($i = 1; $i <= 24; $i++)
                                                <option value="{{str_pad(ceil($i/2), 2, '0', STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, '0', STR_PAD_LEFT)}}">{{str_pad(ceil($i/2), 2, "0", STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, "0", STR_PAD_LEFT)}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2 controls">
                                        <select id="Friday_end_ampm" name="friday_end_ampm" class="form-control input-block-level">
                                            <option value=''>---</option>
                                            <option value='AM'>AM</option>
                                            <option value='PM'>PM</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Saturday  <a class="hours-clear" data-day="Saturday">Clear</a></label>
                                <div class="row form-group">
                                    <div class="col-md-4 controls">
                                        <select id="Saturday_start" name="saturday_start" class="form-control input-block-level">
                                            <option value="">- Start Time -</option>
                                            @for ($i = 1; $i <= 24; $i++)
                                                <option value="{{str_pad(ceil($i/2), 2, '0', STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, '0', STR_PAD_LEFT)}}">{{str_pad(ceil($i/2), 2, "0", STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, "0", STR_PAD_LEFT)}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2 controls">
                                        <select id="Saturday_start_ampm" name="saturday_start_ampm" class="form-control input-block-level">
                                            <option value=''>---</option>
                                            <option value='AM'>AM</option>
                                            <option value='PM'>PM</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 controls">
                                        <select id="Saturday_end" name="saturday_end" class="form-control input-block-level">
                                            <option value="">- End Time -</option>
                                            @for ($i = 1; $i <= 24; $i++)
                                                <option value="{{str_pad(ceil($i/2), 2, '0', STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, '0', STR_PAD_LEFT)}}">{{str_pad(ceil($i/2), 2, "0", STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, "0", STR_PAD_LEFT)}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2 controls">
                                        <select id="Saturday_end_ampm" name="saturday_end_ampm" class="form-control input-block-level">
                                            <option value=''>---</option>
                                            <option value='AM'>AM</option>
                                            <option value='PM'>PM</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Sunday  <a class="hours-clear" data-day="Sunday">Clear</a></label>
                                <div class="row form-group">
                                    <div class="col-md-4 controls">
                                        <select id="Sunday_start" name="sunday_start" class="form-control input-block-level">
                                            <option value="">- Start Time -</option>
                                            @for ($i = 1; $i <= 24; $i++)
                                                <option value="{{str_pad(ceil($i/2), 2, '0', STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, '0', STR_PAD_LEFT)}}">{{str_pad(ceil($i/2), 2, "0", STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, "0", STR_PAD_LEFT)}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2 controls">
                                        <select id="Sunday_start_ampm" name="sunday_start_ampm" class="form-control input-block-level">
                                            <option value=''>---</option>
                                            <option value='AM'>AM</option>
                                            <option value='PM'>PM</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 controls">
                                        <select id="Sunday_end" name="sunday_end" class="form-control input-block-level">
                                            <option value="">- End Time -</option>
                                            @for ($i = 1; $i <= 24; $i++)
                                                <option value="{{str_pad(ceil($i/2), 2, '0', STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, '0', STR_PAD_LEFT)}}">{{str_pad(ceil($i/2), 2, "0", STR_PAD_LEFT)}}:{{str_pad(($i-1)%2*30, 2, "0", STR_PAD_LEFT)}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2 controls">
                                        <select id="Sunday_end_ampm" name="sunday_end_ampm" class="form-control input-block-level">
                                            <option value=''>---</option>
                                            <option value='AM'>AM</option>
                                            <option value='PM'>PM</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="margin-bottom-10">
                    <div class="pull-right">
                        <span id="messages"></span>
                        <button id="btnSave" type="button" class="btn btn-primary">Save New</button>
                        <button id="btnDelete" type="button" class="btn btn-danger" style="display:none;">Delete</button>
                        <button id="btnCopy" type="button" class="btn btn-info" style="display:none;">Copy</button>
                        <button id="btnAdd" type="button" class="btn btn-success" style="display:none;">Add New</button>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="divider"></div>

                <div class="clearfix"></div>

                <div class="" style="padding-bottom: 0px;">
                    <div class="pull-left">
                        <a href="/wizard?viewing={{$franchise_id}}" class="btn btn-primary"><i class="icon-hand-left"></i>  Back</a>
                    </div>
                    <div class="pull-right">
                        <a href="/coupon?viewing={{$franchise_id}}" class="btn btn-success">Next  <i class="icon-hand-right"></i></a>
                    </div>
                </div>

                <!-- Delete Modal -->
                <div id="deleteModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 id="deleteModalLabel">Are you sure?</h3>
                            </div>
                            <div class="modal-body text-center">
                                <p>Are you really, really sure you want to delete this location?</p>
                                <p><button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>&nbsp;&nbsp;&nbsp;<button id="btnDeleteConfirm" type="button" class="btn btn-danger">Delete</button></p>
                            </div>
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