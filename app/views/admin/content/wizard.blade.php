@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.content.master.templates.sidebar', array())
@stop
@section('body')
<script type="text/ejs" id="template_result">
<% list(results, function(result){ %>
  <tr class="">
    <td class="center">
        <div class="input">
            <button class="btn btn-sm btn-edit modalClose" data-dismiss="modal" type="button" data-merchant_id="<%= result.merchant_id %>" data-franchise_id="<%= result.id %>" data-toggle="tooltip" data-placement="right" title="Edit This Franchise"><i class="icon-pencil"></i></button>
        </div>
    </td>
    <td><%== result.display %> - <%= result.maghub_id %></td>
    <td><%= result.sales %></td>
    <td><%= result.is_active %></td>
  </tr>
<% }) %>
</script>

<script type="text/ejs" id="template_merchant_result">
<% list(results, function(result){ %>
  <tr class="">
    <td class="center">
        <div class="input">
            <button class="btn btn-sm btn-choose modalClose" data-dismiss="modal" type="button" data-merchant_id="<%= result.id %>" data-toggle="tooltip" data-placement="right" title="Select This Merchant"><i class="icon-ok"></i></button>
        </div>
    </td>
    <td><%== result.display %></td>
    <td><%= result.is_active %></td>
  </tr>
<% }) %>
</script>

<script type="text/ejs" id="template_subcategory">
<% list(subcategories, function(subcat){ %>
  <option value="<%= subcat.id %>"><%= subcat.name %></option>
<% }) %>
</script>

<script type="text/ejs" id="template_assignment">
<% for(var i=0; i < users.length; i++){ %>
    <option value="<%= users[i].attributes.id %>"><%= users[i].attributes.name %></option>
<% } %>
</script>

<script type="text/ejs" id="template_lead_email">
<%  var i=0;
    list(emails, function(email) {%>
    <div class="row lead-email">
        <div class="col-md-12">
            <div class="row form-group">
                <div class="col-md-12 controls input-group">
                    <input class="form-control" type="email" value="<%= email.email_address %>">
                    <span class="input-group-btn">
                        <button class="btn btn-danger btn-delete-email"><i class="icon-minus"></i></button>
                    </span>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-12 controls">
                    <select class="form-control">
                        <option value="pretty" <%= email.format == 'pretty' ? 'selected="selected"' : '' %>>Pretty</option>
                        <option value="raw" <%= email.format == 'raw' ? 'selected="selected"' : '' %>>Raw</option>
                        <option value="adf" <%= email.format == 'adf' ? 'selected="selected"' : '' %>>ADF</option>
                        <option value="custom-adf" <%= email.format == 'custom-adf' ? 'selected="selected"' : '' %>>Custom ADF</option> 
                    </select>
                </div>
            </div>
        </div>
    </div>
<% }); %>
</script>

<script type="text/ejs" id="template_blank_lead_email">
<div class="row lead-email">
    <div class="col-md-12">
        <div class="row form-group">
            <div class="col-md-12 controls input-group">
                <input class="form-control" type="email">
                <span class="input-group-btn">
                    <button class="btn btn-danger btn-delete-email"><i class="icon-minus"></i></button>
                </span>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-12 controls">
                <select class="form-control">
                    <option value="pretty" selected="selected">Pretty</option>
                    <option value="raw">Raw</option>
                    <option value="adf">ADF</option>
                    <option value="custom-adf">Custom ADF</option>
                </select>
            </div>
        </div>
    </div>
</div>
</script>

<script type="text/ejs" id="template_note">
<% list(notes, function(note){ %>
<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                <input type="text" class="note-title pull-left" value="<%= note.title %>">
            </div>
        </div>
        <div class="clearfix"></div>
        <textarea class="note-content" rows="5"><%= note.content %></textarea>
        <div>
            Updated At: <span class="updated-text"><%= merch_control.GetDate(note.updated_at) %></span><span class="note-message" style="margin-left: 5px;color:green;"></span>
        </div>
        <div>
            <button class="btn btn-success btn-save-note" data-note_id="<%= note.id %>">Save</button>
            <button class="btn btn-danger btn-delete-note" data-note_id="<%= note.id %>">Delete</button>
        </div>
    </div>
</div>
<% }); %>
</script>

<script type="text/ejs" id="template_dealer_order">
<% list(orders, function(order){ %>
<div class="row form-group">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-2">
                <label>Vehicle Make</label>
                <select class="form-control orderMake">
                    @foreach($makes as $make)
                    <option value="{{$make->id}}" <%= order.make_id == {{$make->id}} ? 'selected="selected"' : '' %>>{{$make->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Center Zipcode</label>
                <input type="text" class="form-control orderZip" value="<%= order.zipcode %>">
            </div>
            <div class="col-md-1">
                <label>Radius</label>
                <input type="text" class="form-control orderRadius" value="<%= order.radius / 1607 %>">
            </div>
            <div class="col-md-1">
                <label>Budget</label>
                <input type="text" class="form-control orderBudget" value="<%= order.budget %>">
            </div>
            <div class="col-md-3">
                <label>Starts At</label>
                <input type="text" class="form-control orderStartsAt" value="<%= merch_control.GetDate(order.starts_at) %>">
            </div>
            <div class="col-md-3">
                <label>Ends At</label>
                <input type="text" class="form-control orderEndsAt" value="<%= merch_control.GetDate(order.ends_at) %>">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary btn-order-save" data-order_id="<%= order.id %>" data-loading-text="Saving">Save</button>
                <button class="btn btn-danger btn-order-delete" data-order_id="<%= order.id %>" data-loading-text="Deleting">Delete</button>
            </div>
        </div>
    </div>
</div>
<% }); %>
</script>

<script>
  current_page = 0;
  merchant_current_page = 0;
  selectedCoupon = 0;
  selectedFranchise = "{{ $franchise_id }}";
  selectedMerchant = "{{ $merchant_id }}";
  img_path = "";
  typingTimer = '';
  doneTypingInterval = 750;
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
        <!--page title end-->

        <div class="clearfix"></div>
         
         <!-- info-box -->
         <div class="grid">
            <div class="grid-title">
                <div class="pull-left">
                    <span>Merchant</span>
                    <div class="clearfix"></div>
                </div>
                <div class="pull-right">
                    <button id="addNew" class="pull-right btn btn-sm btn-success" disabled="disabled" style="margin-right: 10px;">Add New  <i class="icon-plus"></i></button>
                    <button id="findExisting" class="pull-right btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal" style="margin-right: 10px;">Find Existing  <i class="icon-search"></i></button>
                </div>
            </div>
            <div id="step1" class="grid-content overflow">
                <legend id="MerchantTitle">Merchant - Step 1</legend>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="sales">Sales Rep</label>
                            <div class="controls">
                                <select id="sales" name="sales" class="form-control fill-up">
                                    <option value="">----</option>
                                    @foreach($sales as $sale)
                                        <option value="{{ $sale->id }}">{{ $sale->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="merchant_type">Merchant Type</label>
                            <div class="controls">
                                <select id="merchant_type" class="form-control fill-up">
                                    <option value="">----</option>
                                    <option value="RETAIL">Retail</option>
                                    <option value="PPC">PPC</option>
                                    <option value="PPL">PPL</option>
                                    <option value="PROSPECT">Prospect</option>
                                </select>
                            </div>
                        </div>
                        <div id="divNational" class="row form-group" style="display:none;">
                            <label class="checkbox">
                                <input id="chkNational" type="checkbox"> National Prospect
                            </label>
                        </div>
                        <div id="pplCertifiedDiv" class="row" style="display:none;">
                            <div class="col-md-12 form-group">
                                <label class="control-label">Contractor Certified?</label>
                                <input id="is_certified_yes" type="radio" name="is_certified" value="1"> 
                                <label class="radio" for="is_certified_yes">
                                    <span></span>Yes
                                </label>
                                <input id="is_certified_no" type="radio" name="is_certified" value="0"> 
                                <label class="radio" for="is_certified_no">
                                    <span></span>No
                                </label>
                            </div>
                        </div>
                        <div id="pplLeadAllowancesDiv" class="row" style="display:none;">
                            <div class="col-md-12 form-group">
                                <input type="checkbox" id="allowGeneric" checked="checked">
                                <label for="allowGeneric">
                                    <span></span>
                                    Generic Leads
                                </label>
                                <input type="checkbox" id="allowDirected" checked="checked">
                                <label for="allowDirected">
                                    <span></span>
                                    Directed Leads
                                </label>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="parentcategory_id">Category</label>
                            <div class="controls">
                                <select id="parentcategory_id" name="parentcategory_id" class="form-control fill-up">
                                    <option value="">----</option>
                                    @foreach($categories as $cat)
                                        <option value="{{$cat->id}}">{{$cat->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="category_id">Subcategory</label>
                            <div class="controls">
                                <select id="category_id" name="category_id" class="form-control fill-up">
                                    <option value="">----</option>
                                </select>
                            </div>
                        </div>
                        <div id="divFeaturedDealer" class="row form-group" style="display:none;">
                            <input type="checkbox" id="featuredDealer">
                            <label for="featuredDealer">
                                <span></span>
                                Featured Dealer
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="display">Merchant Name</label>
                            <div class="controls input-group">
                                <input id="display" type="text" class="form-control fill-up">
                                <span class="input-group-btn">
                                    <button id="btnSearchMerchants" class="btn" type="button" data-toggle="modal" data-target="#merchantModal"><i class="icon-search"></i></button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="display">Company</label>
                            <div class="controls">
                                <select id="company_id" class="form-control input-block-level">
                                    @foreach($companies as $company)
                                    <option value="{{$company->id}}">{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="magazinemanager_id">Magazine Manager ID</label>
                            <div class="controls">
                                <input id="magazinemanager_id" type="text" class="form-control fill-up">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="status">Franchise Status</label>
                            <div class="controls">
                                <select id="status" name="status" class="form-control fill-up">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="category_id">Franchise Deleted?</label>
                            <div class="controls">
                                <select id="is_deleted" name="is_deleted" class="form-control fill-up">
                                    <option value="1">Yes</option>
                                    <option value="0" selected="selected">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="primary_contact">Primary Contact</label>
                            <div class="controls">
                                <input id="primary_contact" type="text" class="form-control fill-up">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <div class="row">
                            <div class="col-md-12 form-group margin-top-5">
                                <input type="checkbox" id="is_permanent" name="is_permanent">
                                <label for="is_permanent">
                                    <span style="margin-top:-5px"></span>
                                    &nbsp;&nbsp;Permanent?
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="contract_start">Contract Start</label>
                        <input id="contract_start" type="text" class="form-control">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="contract_end">Contract End</label>
                        <input id="contract_end" type="text" class="form-control">
                    </div>
                </div>
                <hr id="pplDivider" style="display:none;">
                <div class="row" id="pplArea" style="display:none;">
                    <div class="col-md-4 form-group">
                        <label class="control-label">Lead Emails</label>
                        <div id="leadEmailArea">
                            <div class="row lead-email">
                                <div class="col-md-12">
                                    <div class="row form-group">
                                        <div class="col-md-12 controls input-group">
                                            <input class="form-control" type="email">
                                            <span class="input-group-btn">
                                                <button class="btn btn-danger btn-delete-email"><i class="icon-minus"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <select>
                                                <option value="pretty" selected="selected">Pretty</option>
                                                <option value="raw">Raw</option>
                                                <option value="adf">ADF</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-success btn-add-email"><i class="icon-plus"></i></button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label class="control-label">Plan Type</label>
                                <input id="service_plan_premium" type="radio" name="service_plan" value="premier"> 
                                <label class="radio inline" for="service_plan_premium">
                                    <span></span>Premium
                                </label>
                                <input id="service_plan_basic" type="radio" name="service_plan" checked="checked" value="basic"> 
                                <label class="radio inline" for="service_plan_basic">
                                    <span></span>Basic
                                </label>
                                <input id="service_plan_trial" type="radio" name="service_plan" value="trial"> 
                                <label class="radio inline" for="service_plan_trial">
                                    <span></span>Trial
                                </label>
                            </div>
                        </div>
                        <div id="trialDiv" class="row" style="display:none;">
                            <div class="col-md-12">
                                <div class="row form-group">
                                    <div class="col-md-12 controls">
                                        <label class="control-label">Trial Start</label>
                                        <input id="trialStart" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-12 controls">
                                        <label class="control-label">Trial End</label>
                                        <input id="trialEnd" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-12 controls">
                                        <label class="control-label">Trial Lead Cap</label>
                                        <input id="trialLeadCap" class="form-control" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row pplTextInputs">
                            <div class="col-md-12 form-group">
                                <label class="control-label">Contact Phone</label>
                                <input id="contactPhone" class="form-control" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 pplTextInputs">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label class="control-label">Zipcode</label>
                                <input id="contractorZipcode" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label class="control-label">Radius</label>
                                <input id="contractorRadius" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label class="control-label">Monthly Lead Budget</label>
                                <input id="contractorBudget" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button id="dealerReactivatedNotify" data-loading-text="Notifying..." class="btn btn-primary">Reactivated - Notify Rep</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                <div style="padding-bottom: 0px;">
                    <div class="pull-right">
                        <span id="messages1"></span>
                        <button id="btnUnblockYipits" type="button" class="btn btn-primary" style="display:none">Unblock Yipits</button>
                        <button id="btnBlockYipits" type="button" class="btn btn-danger" style="display:none">Block Yipits</button>
                        <button id="step1Done" type="button" class="btn btn-success" data-loading-text="Loading...">Next  <i class="icon-hand-right"></i></button>
                    </div>
                </div>
            </div>

            <div id="stepNotes" class="grid-content overflow" style="display:none;">
                <div>
                    <legend>Merchant Notes</legend>
                </div>
                <div>
                    <div id="notesArea">

                    </div>
                    <div>
                        <button class="btn btn-primary btn-add-note">Add Note</button>
                    </div>
                </div>

                <div class="divider"></div>

                <div style="padding-bottom: 0px; margin-top: 20px;">
                    <div class="pull-left">
                        <button id="stepNotesBack" type="button" class="btn btn-primary"><i class="icon-hand-left"></i>  Back</button>
                    </div>
                    <div class="pull-right">
                        <span id="messagesNotes"></span>
                        <button id="stepNotesDone" type="button" class="btn btn-success">Next  <i class="icon-hand-right"></i></button>
                    </div>
                </div>
            </div>

            <div id="step2" class="grid-content overflow" style="display:none;">
                <div>
                    <legend id="MerchantTitle">Merchant - Step 2: Logo</legend>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="text-center"><strong>Logo</strong></div>
                        <div>
                            <form enctype="multipart/form-data" action="/wizard-imgupload" method="POST" target="ieSubmitFrame">
                                <div>
                                    <img id="imgLogo" src="http://placehold.it/500X300">
                                </div>
                                <div id="logoUpload" class="form-group" style="margin-top: 10px;">
                                    <label class="control-label" for="Logo">Upload a Logo</label>
                                    <div class="controls row">
                                        <div class="col-xs-4">
                                            <input id="Logo" name="upload_img" type="file" class="fill-up">
                                        </div>
                                        <div class="col-xs-4">
                                            <button id="btnUpload" type="submit" class="btn" style="margin-left: 10px;">Upload</button>
                                        </div>
                                        
                                        <input id="imgMerchant_id" name="merchant_id" type="hidden">
                                        <input name="type" type="hidden" value="logo">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <iframe name="ieSubmitFrame" id="ieSubmitFrame" height="0" border="0" scrolling="auto" style="display: none;" src=""></iframe>
                        <button id="ieDoneButton" type="button" style="display: none;"></button>
                    </div>
                    <div class="col-lg-6">
                        <div class="text-center">
                            <strong>Merchant Banner</strong><br>
                            <small>(988x250)</small>
                        </div>
                        <div>
                            <form enctype="multipart/form-data" action="/wizard-imgupload" method="POST" target="ieSubmitBannerFrame">
                                <div>
                                    <img id="imgBanner" src="http://placehold.it/988X250" class="img-responsive">
                                </div>
                                <div id="bannerUpload" class="form-group" style="margin-top: 10px;">
                                    <label class="control-label" for="Banner">Upload a Banner</label>
                                    <div class="controls row">
                                        <div class="col-xs-4">
                                            <input id="Banner" name="upload_img" type="file" class="fill-up">
                                        </div>
                                        <div class="col-xs-4">
                                            <button id="btnBannerUpload" type="submit" class="btn" style="margin-left: 10px;">Upload</button>
                                        </div>
                                        
                                        <input id="bannerMerchant_id" name="merchant_id" type="hidden">
                                        <input name="type" type="hidden" value="banner">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <iframe name="ieSubmitBannerFrame" id="ieSubmitBannerFrame" height="0" border="0" scrolling="auto" style="display: none;" src=""></iframe>
                        <button id="ieBannerDoneButton" type="button" style="display: none;"></button>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="" style="padding-bottom: 0px;">
                    <div class="pull-left">
                        <button id="step2Back" type="button" class="btn btn-primary"><i class="icon-hand-left"></i>  Back</button>
                    </div>
                    <div class="pull-right">
                        <span id="messages2"></span>
                        <button id="step2Done" type="button" class="btn btn-success">Next  <i class="icon-hand-right"></i></button>
                    </div>
                </div>
            </div>

            <div id="step3" class="grid-content overflow" style="display:none;">
                <div>
                    <legend id="MerchantTitle">Merchant - Step 3: Customize</legend>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="catchphrase">Catchphrase</label>
                            <div class="controls">
                                <textarea id="catchphrase" name="catchphrase"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="control-label" for="facebook">Facebook Page</label>
                        <div class="controls">
                            <input id="facebook" type="text" class="form-control fill-up">
                        </div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="control-label" for="twitter">Twitter Page</label>
                        <div class="controls">
                            <input id="twitter" type="text" class="form-control fill-up">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="control-label" for="redemptions">Track Mobile Redemptions?</label>
                        <div class="controls">
                            <select id="redemptions" class="form-control fill-up">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="control-label" for="page_version">Merchant Page Version?</label>
                        <div class="controls">
                            <select id="page_version" class="form-control fill-up">
                                <option value="1">1</option>
                                <option value="2">2</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-6 controls">
                        <label class="control-label" for="service_radius">Service Radius (In Miles)</label>
                        <input id="service_radius" class="form-control">
                    </div>
                    <div class="col-md-6 controls">
                        <label class="control-label" for="entity_search_parse">Allow Coupon Keyword Searching</label>
                        <select id="entity_search_parse" class="form-control">
                            <option value="1">Enabled</option>
                            <option value="0">Disabled</option>
                        </select>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-6 controls">
                        <label class="control-label" for="coupon_tab_type">Coupon Tab Text</label>
                        <input id="coupon_tab_type" class="form-control">
                    </div>
                    <div class="col-md-6 controls">
                        <label class="control-label" for="is_offer_notifications">Expired Offer Notifications</label><br>                        
                        <select id="is_offer_notifications" class="form-control fill-up">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>                       
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="control-label" for="sponsor_level">Sponsor Level</label>
                        <div class="controls">
                            <select id="sponsor_level" class="form-control fill-up">
                                <option value="">None</option>
                                <option value="bronze">Bronze</option>
                                <option value="silver">Silver</option>
                                <option value="gold">Gold</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 form-group" style="display:none;">
                        <label class="control-label" for="sponsor_districts">Sponsor Districts</label>
                        <div class="controls">
                            <select id="sponsor_districts" multiple="true" rows="5" class="form-control">
                                @foreach($districts as $district)
                                <option value="{{$district->id}}">{{$district->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row" id="sponsorBannerRow">
                    <div class="col-md-12">
                        <div class="text-center">
                            <strong>Sponsor Banner</strong><br>
                            <small>(975x195)</small>
                        </div>
                        <div>
                            <form enctype="multipart/form-data" action="/wizard-imgupload" method="POST" target="ieSubmitSponsorBannerFrame">
                                <div>
                                    <img id="sponsorBanner" src="http://placehold.it/975X195" class="img-responsive">
                                </div>
                                <div id="sponsorBannerUpload" class="form-group" style="margin-top: 10px;">
                                    <label class="control-label" for="Banner">Upload a Banner</label>
                                    <div class="controls row">
                                        <div class="col-xs-4">
                                            <input id="sponsorBanner" name="upload_img" type="file" class="fill-up form-control">
                                        </div>
                                        <div class="col-xs-4">
                                            <button id="btnSponsorBannerUpload" type="submit" class="btn" style="margin-left: 10px;">Upload</button>
                                        </div>
                                        
                                        <input id="sponsorBannerFranchise_id" name="franchise_id" type="hidden">
                                        <input name="type" type="hidden" value="sponsor_banner">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <iframe name="ieSubmitSponsorBannerFrame" id="ieSubmitBannerFrame" height="0" border="0" scrolling="auto" style="display: none;" src=""></iframe>
                        <button id="ieSponsorBannerDoneButton" type="button" style="display: none;"></button>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="" style="padding-bottom: 0px;">
                    <div class="pull-left">
                        <button id="step3Back" type="button" class="btn btn-primary"><i class="icon-hand-left"></i>  Back</button>
                    </div>
                    <div class="pull-right">
                        <span id="messages2"></span>
                        <button id="step3Done" type="button" class="btn btn-success">Next  <i class="icon-hand-right"></i></button>
                    </div>
                </div>
                            
            </div>

            <div id="dealer-step4" class="grid-content overflow" style="display:none;">
                <div>
                    <legend>Merchant - Step 4: Vehicle Makes</legend>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php $i=0;$half = floor(count($makes) / 2); ?>
                        @foreach($makes as $make)
                        <div>
                            <input id="tag{{$make->id}}" type="checkbox" name="vehicle_make_id" value="{{$make->id}}">
                            <label for="tag{{$make->id}}">
                                <span></span>
                                {{$make->name}}
                            </label>
                        </div>
                        @if($i++ == $half)
                        </div>
                        <div class="col-md-6">
                        @endif
                        @endforeach
                    </div>
                </div>

                <div class="divider"></div>

                <div class="" style="padding-bottom: 0px;">
                    <div class="pull-left">
                        <button id="dealer-step4Back" type="button" class="btn btn-primary"><i class="icon-hand-left"></i>  Back</button>
                    </div>
                    <div class="pull-right">
                        <span id="messages2"></span>
                        <button id="dealer-step4Done" type="button" class="btn btn-success">Next  <i class="icon-hand-right"></i></button>
                    </div>
                </div>
                            
            </div>

            <div id="dealer-step5" class="grid-content overflow" style="display:none;">
                <div>
                    <legend>Merchant - Step 5: Orders</legend>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                <input id="testLeadsCheck" type="checkbox" style="display:inline;"> Test Leads Confirmed?
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-2">
                        <label>Vehicle Make</label>
                        <select id="orderMake" class="form-control">
                            <option value="">-- Choose --</option>
                            @foreach($makes as $make)
                            <option value="{{$make->id}}">{{$make->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Center Zipcode</label>
                        <input id="orderZip" type="text" class="form-control">
                    </div>
                    <div class="col-md-1">
                        <label>Radius</label>
                        <input id="orderRadius" type="text" class="form-control">
                    </div>
                    <div class="col-md-1">
                        <label>Budget</label>
                        <input id="orderBudget" type="text" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Starts At</label>
                        <input id="orderStartsAt" type="text" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Ends At</label>
                        <input id="orderEndsAt" type="text" class="form-control">
                    </div>
                </div>
                <div>
                    <button id="orderSaveNew" class="btn btn-primary" data-loading-text="Saving...">Save New</button>
                </div>
                <legend>Existing Orders</legend>
                <div id="existingOrdersArea">

                </div>

                <div class="divider"></div>

                <div class="" style="padding-bottom: 0px;">
                    <div class="pull-left">
                        <button id="dealer-step5Back" type="button" class="btn btn-primary"><i class="icon-hand-left"></i>  Back</button>
                    </div>
                    <div class="pull-right">
                        <span id="messages2"></span>
                        <button id="dealer-step5Done" type="button" class="btn btn-success">Next  <i class="icon-hand-right"></i></button>
                    </div>
                </div>
            </div>

            <div id="step4" class="grid-content overflow" style="display:none;">
                <div>
                    <legend id="MerchantTitle">Merchant - Step 4: Project Tags</legend>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php $i=0;$half = floor(count($project_tags['objects']) / 2); ?>
                        @foreach($project_tags['objects'] as $tag)
                        <div class="checkbox">
                            <label>
                                <input id="tag{{$tag->id}}" type="checkbox" name="project_tag_id" value="{{$tag->id}}" style="display:inline;"> {{$tag->name}}
                            </label>
                        </div>
                        @if($i++ == $half)
                        </div>
                        <div class="col-md-6">
                        @endif
                        @endforeach
                    </div>
                </div>

                <div class="divider"></div>

                <div class="" style="padding-bottom: 0px;">
                    <div class="pull-left">
                        <button id="step4Back" type="button" class="btn btn-primary"><i class="icon-hand-left"></i>  Back</button>
                    </div>
                    <div class="pull-right">
                        <span id="messages2"></span>
                        <button id="step4Done" type="button" class="btn btn-success">Next  <i class="icon-hand-right"></i></button>
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

      <!-- Modal -->
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="myModalLabel">Select Franchise</h3>
                </div>
                <div class="modal-body" style="max-height: 485px;">
                    <h4>Search</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <input id="modQuery" type="text" class="form-control fill-up">
                        </div>
                        <div class="col-md-6">
                            <label>
                                <input id="showInactive" type="checkbox" style="display:inline;">
                                Show Inactive
                            </label>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <table class="table table-striped box">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Name - Maghub ID</th>
                                    <th>Salesperson</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="resultsArea">
                          
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div id="paginationBottom" class="pagination pull-right" style="margin-top: 0px;">
                            <ul class="pagination">
                                <li><a id="first" data-type="merch" href="#">&lsaquo;&lsaquo; First</a></li>
                                <li><a id="prev" data-type="merch" href="#">&lsaquo; Prev</a></li>
                                <li><span id="lblCurrentPage" style="color: #0088CC;">&nbsp;</span></li>
                                <li><a id="next" data-type="merch" href="#">Next &rsaquo;</a></li>
                                <li><a id="last" data-type="merch" href="#">Last &rsaquo;&rsaquo;</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="ModalClose" type="button" class="btn modalClose" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
      
    </div>
    <!--MAIN CONTENT END-->

    <!-- Modal -->
    <div id="merchantModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="merchantModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="merchantModalLabel">Select Merchant</h3>
                </div>
                <div class="modal-body" style="max-height: 485px;">
                    <h4>Search</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <input id="mQuery" type="text" class="form-control fill-up">
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <table class="table table-striped box">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="merchantResultsArea">
                          
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div id="merchantPaginationBottom" class="pagination pull-right" style="margin-top: 0px;">
                            <ul class="pagination">
                                <li><a id="merchantFirst" data-type="merch" href="#">&lsaquo;&lsaquo; First</a></li>
                                <li><a id="merchantPrev" data-type="merch" href="#">&lsaquo; Prev</a></li>
                                <li><span id="merchantLblCurrentPage" style="color: #0088CC;"></span></li>
                                <li><a id="merchantNext" data-type="merch" href="#">Next &rsaquo;</a></li>
                                <li><a id="merchantLast" data-type="merch" href="#">Last &rsaquo;&rsaquo;</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="merchantModalClose" type="button" class="btn modalClose" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

@stop
