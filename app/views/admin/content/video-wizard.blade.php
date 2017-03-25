@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.content.master.templates.sidebar', array())
@stop
@section('body')
<script>
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
                    <span>Video</span>
                </div>
                <div class="pull-right" style="padding-top: 8px; padding-right: 5px;">
                    <select id="selLocation">
                        <option value="0">Default</option>
                        @foreach($locations as $location)
                        <option value="{{ $location->id }}" data-specific="{{ $location->is_video_specific }}">{{$location->name}}</option>
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
                <div class="row form-group">
                    <div class="col-md-6">
                        <input id="videoTitle" name="videoTitle" class="form-control fill-up" placeholder="Video Title">
                    </div>
                </div>
                <div class="">
                    <div id="medVideoCodeArea" class="form-group">
                      <legend>Youtube/Vimeo Embed Code</legend>
                      <div class="row">
                        <div class="col-md-6">
                            <textarea id="medVideoCode" class="form-control input-block-level" rows="6"></textarea>
                        </div>
                        <div class="col-md-4">
                          <button id="medPreview" type="button">Preview</button>
                        </div>
                      </div>
                    </div>
                    <div id="medVideoArea" class="row" style="display:none;">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <legend>Youtube/Vimeo Preview</legend>
                                </div>
                            </div>
                            <div class="row">
                                <div id="videoPlayer" class="col-md-8">
                                </div>
                                <div class="col-md-2">
                                    <button id="medEdit" type="button">Edit Embed Code</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="clearfix"></div>

                <div class="" style="padding-bottom: 0px;">
                    <div class="pull-left">
                        <button id="goBack" class="btn btn-primary"><i class="icon-hand-left"></i>  Back</button>
                    </div>
                    <div class="pull-right">
                        <button id="goNext" class="btn btn-success">Next  <i class="icon-hand-right"></i></button>
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