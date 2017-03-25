@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.content.master.templates.sidebar', array())
@stop
@section('body')
<script>
    currentPage = 0;
    selectedContest = 0;
    selectedOffer = 0;
    doneTypingInterval = 750;
    typingTimer = '';
    img_path = "";
    selectedCategory = 0;
    selectedSubcategory = 0;
    selectedPage = 0;
</script>

<script type="text/ejs" id="template_subcategory">
    <% list(subcategories, function(subcat){ %>
      <option value="<%= subcat.id %>"><%= subcat.name %></option>
    <% }) %>
</script>

<script type="text/ejs" id="template_location">
    <% list(locations, function(location){ %>
      <option value="<%= location.id %>"><%= location.name %></option>
    <% }) %>
</script>

<script type="text/ejs" id="template_independent_location">
    <% list(locations, function(location){ %>
      <div class="row">
        <div class="col-md-6 form-group">
            <input type="text" class="form-control" readonly="readonly" value="<%= location.zipcode %>">
        </div>
        <div class="col-md-6 form-group">
            <div class="input-group">
                <input type="text" class="form-control" readonly="readonly" value="<%= location.service_radius/1607 %>">
                <span class="input-group-btn">
                    <button class="btn btn-danger btn-remove-zipcode" data-zipcode="<%= location.zipcode %>">Remove</button>
                </span>
            </div>
        </div>
      </div>
    <% }) %>
</script>

<script type="text/ejs" id="template_award_date">
    <% list(dates, function(date){ %>
    <tr>
        <td style="border:none;"><%= date.award_at %></td>
        <td style="border:none;"><%= date.verify_attempts %></td>
        <td style="border:none;"><%= date.winners %></td>
        <td style="border:none;"><button data-award_date_id="<%= date.id %>" class="btn btn-primary btn-award-date-edit">Edit</button><button style="margin-left: 5px;" data-award_date_id="<%= date.id %>" class="btn btn-danger btn-award-date-delete">Delete</button><button style="margin-left: 5px;" data-award_date_id="<%= date.id %>" class="btn btn-success btn-award-date-copy">Copy</button><a href="http://www.saveon.com/contest-reward?demo=<%= date.id %>" style="margin-left: 5px;" target="_blank" class="btn btn-default">Preview</a></td>
    </tr>
    <% }) %>
</script>

<!--BEGIN MAIN CONTENT-->
<div id="main" role="main">
    <div class="block">
        <div class="clearfix"></div>

        <!-- info-box -->
        <div class="grid">
            <div class="grid-title">
                <div class="pull-left">
                    <span>Contests</span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="grid-content overflow">
                  <div id="resultsGrid" class="col-md-12 box">
                    <div class="row">
                      <div class="col-md-6">
                        <input id="nameSearch" type="text" class="form-control search-query" placeholder="Contest...">
                      </div>
                      <div class="col-md-6">
                        <button id="addContest" type="button" class="pull-right btn btn-primary">Add Contest</button>
                      </div>
                    </div>
                    <div class="row" style="margin-top: 5px;">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered box">
                              <thead>
                                <tr>
                                  <th>Actions</th>
                                  <th>Contest</th>
                                  <th>Type</th>
                                  <th>Start Date</th>
                                  <th>End Date</th>
                                  <th>Status</th>
                                </tr>
                              </thead>
                              <tbody id="resultsArea">
                                <script type='text/ejs' id='template_contest'>
                                  <% 
                                  list(contests, function(contest)
                                  { %>
                                    <tr>
                                      <td><button class="btn btn-sm edit" data-contest_id="<%= contest.id %>"><i class="icon-pencil"></i></button></td>
                                      <td><%= contest.display_name %></td>
                                      <td><%= contest.type %></td>
                                      <td><%= contest.start%></td>
                                      <td><%= contest.end%></td>
                                      <td><%= contest.is_active == '1' ? 'Active' : 'Inactive' %></td>
                                    </tr>
                                  <% }); %>
                                </script>
                              </tbody>
                              <tfoot>
                                <tr>
                                  <td colspan="7">
                                      <div id="paginationTop" class="pagination">
                                        <ul class="pagination">
                                          <li><a class="pagingButton" style="cursor:pointer;" id="first">&lsaquo;&lsaquo; First</a></li>
                                          <li><a class="pagingButton" style="cursor:pointer;" id="prev">&lsaquo; Prev</a></li>
                                          <li><a class="pagingButton" style="cursor:pointer;" id="next">Next &rsaquo;</a></li>
                                          <li><a class="pagingButton" style="cursor:pointer;" id="last">Last &rsaquo;&rsaquo;</a></li>
                                        </ul>
                                      </div>
                                  </td>
                                </tr>
                              </tfoot>
                            </table>
                        </div>
                    </div>
                  </div>

                  <div id="editBox" class="box" style="display:none;">
                    <div class="tab-header">
                      Edit Contest
                    </div>
                    <div class="padded">
                      <div class="row">
                        <div class="col-md-4 form-group">
                          <label class="control-label" for="contestName">Contest Name</label>
                          <div class="controls">
                            <input id="contestName" type="text" class="form-control fill-up"/>
                          </div>
                        </div>
                        <div class="col-md-4 form-group">
                          <label class="control-label" for="contestType">Type</label>
                          <div class="controls">
                            <select id="contestType" class="form-control fill-up">
                              <option value="generic">Contest</option>
                              <option value="internal">Internal Sweepstakes</option>
                              <option value="external">External Sweepstakes</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-4 form-group">
                          <label class="control-label" for="contestStatus">Status</label>
                          <div class="controls">
                            <select id="contestStatus" class="form-control fill-up">
                              <option value="1">Active</option>
                              <option value="0">Inactive</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div id="displayDiv" class="row">
                        <div class="col-md-4 form-group">
                          <label class="control-label" for="contestDisplayName">Display Text</label>
                          <div class="controls">
                            <input id="contestDisplayName" type="text" class="form-control fill-up"/>
                          </div>
                        </div>
                        <div id="divNumbers" class="col-md-8" style="display:none;">
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <label class="control-label" for="contestNumberMin">Minimum Number</label>
                                    <div class="controls">
                                        <input id="contestNumberMin" type="text" class="input-block-level"/>
                                    </div>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label class="control-label" for="contestNumberMax">Maximum Number</label>
                                    <div class="controls">
                                        <input id="contestNumberMax" type="text" class="input-block-level"/>
                                    </div>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label class="control-label" for="contestNumberLength">Number Length</label>
                                    <div class="controls">
                                        <input id="contestNumberLength" type="text" class="input-block-level"/>
                                    </div>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label class="control-label" for="contestNumberType">Number Type</label>
                                    <div class="controls">
                                        <select id="contestNumberType" class="input-block-level">
                                            <option value="num">Numeric</option>
                                            <option value="alphanum">Alpha-Numeric</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 form-group">
                          <label class="control-label" for="contestMerchant">Related Franchise</label>
                          <div class="controls">
                            <input class="form-control" id="contestFranchise" type="text">
                          </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Demo</label>
                            <select class="form-control" id="contestDemo">
                                <option value="0" selected="selected">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                      </div>
                      <div class="row" id="franchiseLocationsRow">
                        <div class="col-md-4 form-group">
                            <label>Participating Locations</label>
                            <div class="input-group">
                                <select class="form-control" id="contestLocations">
                                    <option value="0">All</option>
                                </select>
                                <span class="input-group-btn">
                                    <button id="moreLocations" type="button" class="btn btn-sm" style="height: 28px;"><i class="icon-plus"></i></button>
                                </span>
                            </div>
                        </div>
                      </div>
                      <div class="row" id="independentLocationsRow">
                        <div class="col-md-4 form-group">
                            <div class="checkbox">
                                <label>
                                    <input id="is_location_independent" type="checkbox" style="display:inline;">
                                    Location Independent?
                                </label>
                            </div>
                            <div class="row" id="independentLocationsControls" style="display:none;">
                                <div class="col-md-12">
                                    <div id="independentLocationsAlert" class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-danger" role="alert">
                                              Please Save Contest Before Creating Independent Locations
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>Zipcode</label>
                                            <input id="independentZipcode" class="form-control" type="text">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Radius</label>
                                            <div class="input-group">
                                                <input id="independentRadius" class="form-control" type="text">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary btn-add-zipcode" disabled>Add</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" id="independentLocationsArea">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <span id="independentLocationsMessages">

                                    </span>
                                </div>
                            </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 form-group">
                          <label class="control-label" for="contestStart">Start Date</label>
                          <div class="controls">
                            <input id="contestStart" type="text" class="form-control fill-up"/>
                          </div>
                        </div>
                        <div class="col-md-4 form-group">
                          <label class="control-label" for="contestEnd">End Date</label>
                          <div class="controls">
                            <input id="contestEnd" type="text" class="form-control fill-up"/>
                          </div>
                        </div>
                      </div>
                      <div id="listingDiv" class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="control-label">Contest Listing Image (222px X 220px) <span id="listing_messages" style="display:none; margin-left: 10px;"></span></label>
                            <div id="listing_LinkDiv" class="controls input-group">
                              <input id="contest_listing" type="text" class="form-control input-xlarge"/>
                              <span class="input-group-btn">
                                <button id="btnAdd_listing" type="button" style="height:32px;" class="btn btn-primary">New Image</button>
                              </span>
                            </div>
                            <div id="listing_InputDiv" style="display: none;">
                              <input id="listing_Input" name="banner" type="file"/><button id="listing_InputCancel" type="button" class="btn btn-warning" style="margin-left: 10px;">Cancel</button><button id="listing_Save" type="button" class="btn btn-success" style="margin-left: 10px;">Upload</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="control-label">Contest Banner <span id="bannerDim"></span> <span id="contestBanner_messages" style="display:none; margin-left: 10px;"></span></label>
                            <div id="contestBanner_LinkDiv" class="controls input-group">
                              <input id="contest_contestBanner" type="text" class="form-control input-xlarge"/>
                              <span class="input-group-btn">
                                <button id="btnAdd_contestBanner" type="button" style="height:32px;" class="btn btn-primary">New Image</button>
                              </span>
                            </div>
                            <div id="contestBanner_InputDiv" style="display: none;">
                              <input id="contestBanner_Input" name="contestBanner" type="file"/><button id="contestBanner_InputCancel" type="button" class="btn btn-warning" style="margin-left: 10px;">Cancel</button><button id="contestBanner_Save" type="button" class="btn btn-success" style="margin-left: 10px;">Upload</button>
                            </div>
                          </div>
                        </div>
                        <div id="logoDiv" class="col-md-6">
                          <div class="form-group">
                            <label class="control-label">Contest Logo Button (280px X 100px) <span id="logoButton_messages" style="display:none; margin-left: 10px;"></span></label>
                            <div id="logoButton_LinkDiv" class="controls input-group">
                              <input id="contest_logoButton" type="text" class="form-control input-xlarge"/>
                              <span class="input-group-btn">
                                <button id="btnAdd_logoButton" type="button" style="height:32px;" class="btn btn-primary">New Image</button>
                              </span>
                            </div>
                            <div id="logoButton_InputDiv" class="row" style="display: none;">
                              <input id="logoButton_Input" name="contest_logo" type="file"/><button id="logoButton_InputCancel" type="button" class="btn btn-warning" style="margin-left: 10px;">Cancel</button><button id="logoButton_Save" type="button" class="btn btn-success" style="margin-left: 10px;">Upload</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div id="landingDiv" class="col-md-6">
                          <div class="form-group">
                            <label class="control-label">Landing Image <span id="landingDim"></span>(JPG) <span id="landing_messages" style="display:none; margin-left: 10px;"></span></label>
                            <div id="landing_LinkDiv" class="controls input-group">
                              <input id="contest_landing" type="text" class="form-control input-xlarge"/>
                              <span class="input-group-btn">
                                <button id="btnAdd_landing" type="button" style="height:32px;" class="btn btn-primary">New Image</button>
                              </span>
                            </div>
                            <div id="landing_InputDiv" style="display: none;">
                              <input id="landing_Input" name="landing" type="file"/><button id="landing_InputCancel" type="button" class="btn btn-warning" style="margin-left: 10px;">Cancel</button><button id="landing_Save" type="button" class="btn btn-success" style="margin-left: 10px;">Upload</button>
                            </div>
                          </div>
                        </div>
                        <div id="companyDiv" class="col-md-6">
                          <div class="form-group">
                            <label class="control-label">Company Logo Button (280px X 100px) <span id="companyButton_messages" style="display:none; margin-left: 10px;"></span></label>
                            <div id="companyButton_LinkDiv" class="controls input-group">
                              <input id="contest_companyButton" type="text" class="form-control input-xlarge"/>
                              <span class="input-group-btn">
                                <button id="btnAdd_companyButton" type="button" style="height:32px;" class="btn btn-primary">New Image</button>
                              </span>
                            </div>
                            <div id="companyButton_InputDiv" class="row" style="display: none;">
                              <input id="companyButton_Input" name="company_logo" type="file"/><button id="companyButton_InputCancel" type="button" class="btn btn-warning" style="margin-left: 10px;">Cancel</button><button id="companyButton_Save" type="button" class="btn btn-success" style="margin-left: 10px;">Upload</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div id="descriptionDiv" class="row">
                        <div class="col-md-12 form-group">
                          <label class="control-label" for="contestDescription">Contest Description</label>
                          <div class="controls">
                            <textarea id="contestDescription" name="contestDescription" class="form-control fill-up" rows="6"></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12 form-group">
                          <label class="control-label" for="contestRules">Contest Rules</label>
                          <div class="controls">
                            <textarea id="contestRules" name="contestRules" class="form-control fill-up" rows="6"></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 form-group">
                          <label class="control-label" for="contestLogoLink">Custom Redirect</label>
                          <div class="controls">
                            <input id="contestLogoLink" type="text" class="form-control fill-up"/>
                          </div>
                        </div>
                        <div id="wufooDiv" class="col-md-4 form-group">
                          <label class="control-label" for="contestWufooLink">Wufoo Link</label>
                          <div class="controls">
                            <input id="contestWufooLink" type="text" class="form-control fill-up"/>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Featured Contest</label>
                            <select class="form-control" id="is_featured_contest" class="fill-up">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div id="nationalRow" class="col-md-4 form-group">
                            <label>National Contest</label>
                            <select class="form-control" id="is_national" class="fill-up">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                      </div>
                      <div class="row">
                        <div id="locationRow" class="col-md-4">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Location</label>
                                    <input class="form-control" id="contestLocation" type="text">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Radius</label>
                                    <input class="form-control" id="contestRadius" type="text">
                                </div>
                            </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Follow Up Text</label>
                            <input class="form-control" id="contestFollowText" type="text">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Follow Up Coupon</label>
                            <div class="input-group">
                              <input class="form-control" id="contestCoupon" type="text">
                              <span class="input-group-btn">
                                <button id="btnCouponClear" class="btn btn-danger">Clear</button>
                                <a id="btnCouponEdit" class="btn btn-success" target="_blank" style="display:none;">Edit</a>
                                <button id="btnCouponCreate" class="btn btn-primary disabled" data-toggle="modal" data-target="#followUpModal">Create</button>
                              </span>
                            </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-4 form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="contestAutomated" style="display:inline;"> Electonic Prize?
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Total Inventory</label>
                            <input class="form-control" id="totalInventory" type="text" value="0">
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Remaining Inventory</label>
                            <input class="form-control" id="remainingInventory" type="text" value="0" disabled="disabled">
                            <small><a class="pointer" id="newRemainingInventoryPrompt">Is this wrong?</a></small>
                        </div>
                      </div>
                      <div id="awardDatesDiv">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Award At</th>
                                        <th>Award Attempts</th>
                                        <th>Winners</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="awardDates">

                                </tbody>
                            </table>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-primary pull-left disabled" id="btn-add-date">Add Award Date</button> <span id="dateMessage" style="color:red;margin-left:10px;">Save Contest First</span>
                        </div>
                      </div>

                      <div class="divider"></div>
                      <div style="padding-bottom: 250px;">
                        <div class="pull-right">
                          <span id="messages" class="hpadded" style="display:none;"></span>
                          <button id="btnClose" class="btn btn-warning">Close</button>
                          <button id="btnSave" class="btn btn-success">Save</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div id="dateBox" class="box" style="display:none;">
                    <div class="tab-header">
                      Edit Award Date
                    </div>
                    <div class="padded">
                      <div class="row">
                        <div class="col-md-6 control-group">
                            <label>Award At</label>
                            <input type="text" class="form-control" id="award_at">
                        </div>
                        <div class="col-md-6 control-group">
                            <label>Number of Winners</label>
                            <input type="text" class="form-control" id="winners">
                        </div>
                      </div>
                      <legend>Prize Info</legend>
                      <div class="row">
                        <div class="col-md-6 control-group">
                            <label>Prize Name</label>
                            <input type="text" class="form-control" id="prize_name">
                        </div>
                        <div class="col-md-6 control-group">
                            <label>Redeemable At</label>
                            <input type="text" class="form-control" id="redeemable_at">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12 control-group">
                            <label>Prize Description</label>
                            <textarea class="form-control" id="prize_description" placeholder="Enter the description and any restrictions" rows="4"></textarea>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4 control-group">
                            <label>Prize Expiration Date</label>
                            <input type="text" class="form-control" id="prize_expiration_date">
                        </div>
                        <div class="col-md-4 control-group">
                            <label>Prize Authorizer</label>
                            <input type="text" class="form-control" id="prize_authorizer">
                        </div>
                        <div class="col-md-4 control-group">
                            <label>Authorizer Title</label>
                            <input type="text" class="form-control" id="prize_authorizer_title">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-default pull-right" style="margin-left: 5px;" id="btn-close-date">Close</button>
                            <button class="btn btn-primary pull-right" id="btn-save-date">Save</button>
                        </div>
                      </div>
                    </div>
                  </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="followUpModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title">Follow Up Creation</h4>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-md-12 form-group">
                    <label>Coupon Title*</label>
                    <div class="controls">
                        <input id="name" name="name" class="form-control" type="text" placeholder="Coupon Title*">
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
                <div class="col-md-12 form-group">
                    <label class="control-label" for="description">Disclaimer*</label>
                    <div class="controls">
                        <textarea id="description" name="description" class="form-control fill-up" rows="6"></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div id="detailsArea" class="col-md-12">
                    <div class="row">
                        <div class="col-md-4 form-group">
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

                        <div class="col-md-4 form-group">
                            <label>Demo Status</label>
                            <div class="controls">
                                <select name="is_demo" id="is_demo" class="form-control fill-up">
                                    <option value="1">Demo</option>
                                    <option value="0" selected="selected">Live</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 ">
                            <label>Featured Offer</label>
                            <select id="is_featured_offer" class="form-control fill-up">
                                <option value="0" selected="selected">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Savings</label>
                            <div class="controls input-group">
                                <span class="input-group-btn">
                                    <button class="btn" style="cursor:default;">$</button>
                                </span>
                                <input id="savings" name="savings" class="form-control fill-up" type="text" placeholder="Savings">
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Coupon Code</label>
                            <div class="controls">
                                <input id="code" class="form-control fill-up" type="text" placeholder="Code">
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
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
                        <div class="col-md-4 form-group">
                            <label>Start Date*</label>
                            <div class="controls input-group">
                                <input id="starts_at" name="starts_at" class="form-control fill-up" type="text" placeholder="Start Date">
                                <span class="input-group-btn">
                                    <button id="startNow" class="btn"><i class="icon-time"></i></button>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Expiration Date*</label>
                            <div class="controls">
                                <input id="expires_at" name="expires_at" class="form-control fill-up" type="text" placeholder="Expiration Date">
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
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
                        <div class="col-md-4  form-group" id="savetdy" >
                            <label>Quantity</label>
                            <div class="controls">
                                <input id="quantity" class="form-control fill-up" type="number" placeholder="Quantity">
                            </div>
                        </div>
                        <div class="col-md-4 form-group" id="regularPrice">
                            <label>Regular Price</label>
                            <div class="controls">
                                <input id="regularprice" class="form-control fill-up" type="number" placeholder="Regular Price">
                            </div>
                        </div>
                        <div class="col-md-4 form-group" >
                            <label>Special Price</label>
                            <div class="controls">
                                <input id="specialprice" class="form-control fill-up" type="number" placeholder="Special Price">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Print Button Link</label>
                            <input id="print_override" class="form-control fill-up" type="text">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Requires Member</label>
                            <select class="form-control" id="member_print">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Mobile Only</label>
                            <select class="form-control" id="is_mobile_only">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Secondary Type</label>
                            <select class="form-control" id="secondary_type">
                                <option value="">None</option>
                                <option value="lease">Lease</option>
                                <option value="purchase">Purchase</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

          </div>
          <div class="modal-footer">
            <span class="coupon-messages" style="display:none;"></span>
            <button type="button" class="btn btn-primary btn-save" data-loading-text="Loading...">Save</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Modal -->
    <div id="newRemainingInventoryModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3>Remaining Inventory</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4 form-group">
                            <input class="form-control" id="newRemainingInventory" type="text">
                        </div>
                    </div>
                    <p class="text-center">
                        <button id="btnUpdateRemainingInventory" class="btn btn-success">Update</button>
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>

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
                                <input id="coupon" name="upload_img" type="file" class="margin-bottom-10">
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