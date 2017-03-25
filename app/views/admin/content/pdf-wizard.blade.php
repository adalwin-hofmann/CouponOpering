@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.content.master.templates.sidebar', array())
@stop
@section('body')
<script type="text/ejs" id="template_pdf">
<%  list(results, function(result){ %>
    <div id="pdfRow_<%= result.name.substring(3) %>" class="row box" style="padding:10px; background-color: #D6D6D6;">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-2">
                    <img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/acrobat_icon.png">
                </div>
                <div class="col-md-10">
                    <div class="row input-append">
                        <input id="long_description_<%= result.id %>" data-pdf_id="<%= result.id %>" type="text" placeholder="PDF Title" value="<%= result.long_description %>" class="col-md-10">
                        <button data-pdf_id="<%= result.id %>" type="button" class="btn btn-primary pdfSave" style="height:32px;">Save</button>
                    </div>
                    <div class="row">
                        <a href="<%= result.path %>" target="_blank">View PDF</a>
                        <span id="messages_<%= result.id %>" style="display:none margin-left:20px;"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <button data-pdf_ident="<%= result.name.substring(3) %>" type="button" class="btn btn-danger pull-right pdfDelete">Delete</button>
        </div>
    </div>
<% }) %>
</script>

<script>
    allowDrag = true;
    selectedFranchise = "{{ $franchise_id }}";
    selectedMerchant = "{{ $merchant_id }}";
    selectedLocation = 0;
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
                    <span>PDFs</span>
                </div>
                <div class="pull-right" style="padding-top: 8px; padding-right: 5px;">
                    <select id="selLocation">
                        <option value="0">Default</option>
                        @foreach($locations as $location)
                        <option value="{{ $location->id }}" data-specific="{{ $location->is_pdf_specific }}">{{$location->name}}</option>
                        @endforeach
                    </select>
                    <div class="checkbox pull-right" style="padding-top: 0px; margin-top: 0px">
                        <label>
                            <input type="checkbox" id="is_location_specific" style="display:inline;" disabled="disabled">
                            Location Specific
                        </label>
                    </div>
                </div>
            </div>
            <div class="grid-content overflow">
                <div class="row">
                    <div class="col-md-6">
                        <div id="pdfArea">

                        </div>
                    </div>
                    <div class="col-md-6">
                        <legend>Drag &amp; Drop to Upload PDFs</legend>
                        <div class="">
                            <h4>Or manually upload...</h4>
                            <button id="btnAdd" type="button" class="btn btn-success">Add PDF</button>
                            <button id="btnCancel" type="button" class="btn btn-warning" style="display:none">Cancel</button>
                            <form method="post" enctype="multipart/form-data" action="/pdf-upload" target="ieSubmitFrame" onsubmit="return main_drop.ManualSubmit();" class="pull-right">
                                <input name="merchant_id" type="hidden" value="{{$merchant_id}}">
                                <input id="location_id" name="location_id" type="hidden">
                                <div id="individualDiv" class="" style="display:none;">

                                </div>
                            </form>
                            <iframe name="ieSubmitFrame" id="ieSubmitFrame" height="0" border="0" scrolling="auto" style="display: none;" src=""></iframe>
                            <button id="ieDoneButton" type="button" style="display: none;"></button>
                        </div>
                    </div>
                </div>

                <div class="divider"><hr></div>

                <div class="" style="padding-bottom: 0px;">
                    <div class="pull-left">
                        <a href="/video?viewing={{$franchise_id}}" type="button" class="btn btn-primary"><i class="icon-hand-left"></i>  Back</a>
                    </div>
                    <div class="pull-right">
                        <a href="/syndication?viewing={{$franchise_id}}" type="button" class="btn btn-success">Next  <i class="icon-hand-right"></i></a>
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
      <div class="upload-window-fade" style="display:none;"></div>
    </div>
    <!--MAIN CONTENT END-->

@stop