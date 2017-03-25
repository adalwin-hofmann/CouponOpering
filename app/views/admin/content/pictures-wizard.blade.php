@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.content.master.templates.sidebar', array())
@stop
@section('body')
<script>
    currentPage = 0;
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
                    <span>Pictures</span>
                </div>
                <div class="pull-right" style="padding-top: 8px; padding-right: 5px;">
                    <select id="selLocation">
                        <option value="0">Default</option>
                        @foreach($locations as $location)
                        <option value="{{ $location->id }}" data-specific="{{ $location->is_about_specific }}">{{$location->name}}</option>
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
            <div id="picturesBox" class="grid-content overflow">
                <div class="row">
                    <div class="col-md-5" style="border-right: 2px solid black">
                        <legend>About Us Images</legend>
                        
                          <div class="row">
                            <div class="col-md-12"><a><img id="img_Thumb_1" class="img-responsive merchant-thumb-huge merchant-thumb-large merchant-thumb" src="http://placehold.it/320X300"></a></div>
                          </div>
                          <div class="row">
                            <div class="col-md-4"><a><img id="img_Thumb_2" class="img-responsive merchant-thumb-medium merchant-thumb" src="http://placehold.it/100X100"></a></div>
                            <div class="col-md-4"><a><img id="img_Thumb_3" class="img-responsive merchant-thumb-medium merchant-thumb" src="http://placehold.it/100X100"></a></div>
                            <div class="col-md-4"><a><img id="img_Thumb_4" class="img-responsive merchant-thumb-medium merchant-thumb" src="http://placehold.it/100X100"></a></div>
                          </div>
                          <div class="row">
                            <div class="col-md-4"><a><img id="img_Thumb_5" class="img-responsive merchant-thumb-medium merchant-thumb" src="http://placehold.it/100X100"></a></div>
                            <div class="col-md-4"><a><img id="img_Thumb_6" class="img-responsive merchant-thumb-medium merchant-thumb" src="http://placehold.it/100X100"></a></div>
                            <div class="col-md-4"><a><img id="img_Thumb_7" class="img-responsive merchant-thumb-medium merchant-thumb" src="http://placehold.it/100X100"></a></div>
                          </div>
                          <div class="row">
                            <div class="col-md-4"><a><img id="img_Thumb_8" class="img-responsive merchant-thumb-medium merchant-thumb" src="http://placehold.it/100X100"></a></div>
                            <div class="col-md-4"><a><img id="img_Thumb_9" class="img-responsive merchant-thumb-medium merchant-thumb" src="http://placehold.it/100X100"></a></div>
                            <div class="col-md-4"><a><img id="img_Thumb_10" class="img-responsive merchant-thumb-medium merchant-thumb" src="http://placehold.it/100X100"></a></div>
                          </div>
                        
                        <iframe name="ieSubmitFrame" id="ieSubmitFrame" height="0" border="0" scrolling="auto" style="display: none;" src=""></iframe>
                        <button id="ieDoneButton" type="button" style="display: none;"></button>
                    </div>
                    <div id="detailsArea" class="col-md-7" style="display:none;">
                        <div class="">
                            <button id="medRemove" type="button" class="btn btn-danger" style="display:none;">Delete Image</button>
                            <button id="medChange" type="button" class="btn btn-primary" style="display:none;">Change Image</button>
                            <button id="medSave" type="button" class="btn btn-success" style="display:none;">Save Changes</button>
                            <button id="medCancel" type="button" class="btn btn-warning" style="display:none;">Cancel</button>
                            <div id="medFilesDiv" class="pull-right" style="display:none;">
                                <form method="post" enctype="multipart/form-data" action="/wizard-imgupload" target="ieSubmitFrame">
                                    <span id="medFilesGroup">
                                        <input id="file_Thumb_1" name="file_Thumb_1" type="file" class="imgupload" style="display:none;">
                                        <input id="file_Thumb_2" name="file_Thumb_2" type="file" class="imgupload" style="display:none;">
                                        <input id="file_Thumb_3" name="file_Thumb_3" type="file" class="imgupload" style="display:none;">
                                        <input id="file_Thumb_4" name="file_Thumb_4" type="file" class="imgupload" style="display:none;">
                                        <input id="file_Thumb_5" name="file_Thumb_5" type="file" class="imgupload" style="display:none;">
                                        <input id="file_Thumb_6" name="file_Thumb_6" type="file" class="imgupload" style="display:none;">
                                        <input id="file_Thumb_7" name="file_Thumb_7" type="file" class="imgupload" style="display:none;">
                                        <input id="file_Thumb_8" name="file_Thumb_8" type="file" class="imgupload" style="display:none;">
                                        <input id="file_Thumb_9" name="file_Thumb_9" type="file" class="imgupload" style="display:none;">
                                        <input id="file_Thumb_10" name="file_Thumb_10" type="file" class="imgupload" style="display:none;">
                                    </span>
                                    <button type="submit" id="medSubmit" class="btn btn-success">Upload</button>
                                    <input id="merchant_id" name="merchant_id" type="hidden" value="{{$merchant_id}}">
                                    <input id="location_id" name="location_id" type="hidden" value="0">
                                    <input id="medType" name="type" type="hidden" value="asset">
                                </form>
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group">
                            <label>Image Title <span id="messages" style="display:none; margin-left:20px;"></span></label>
                            <textarea id="short_description" class="fill-up"></textarea>
                        </div>
                        <div class="rform-groupow">
                            <label>Description</label>
                            <textarea id="long_description" class="fill-up"></textarea>
                        </div>
                    </div>
                </div>

                <div class="divider"><hr></div>

                <div class="" style="padding-bottom: 0px;">
                    <div class="pull-left">
                        <a href="/about?viewing={{$franchise_id}}" class="btn btn-primary"><i class="icon-hand-left"></i>  Back</a>
                    </div>
                    <div class="pull-right">
                        <a href="/video?viewing={{$franchise_id}}" class="btn btn-success">Next  <i class="icon-hand-right"></i></a>
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