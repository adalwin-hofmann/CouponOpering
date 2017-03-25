@extends('master.templates.master')
@section('page-title')
<h1>FTP Upload</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">File Upload</li>
@stop

@section('sidebar')
@include('master.templates.corporatesidebar')
@stop
@section('body')
 <div class="content-bg">
    <div class="row">
        <div class="col-xs-12">
            <span class="h1">FTP Upload</span>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            @if(!empty($error_msgs))
                @foreach($error_msgs as $err)
                    <div class="row">
                        <div class="col-xs-12">
                            <span class="h2 hblock" style="color:red;">{{ $err }}</span>
                        </div>
                    </div>  
                @endforeach
            @endif
            @if(!empty($uploaded))
                @foreach($uploaded as $up)
                    <div class="row">
                        <div class="col-xs-12">
                            <span class="h2 hblock" style="{{ $up['status'] == 'error' ? 'color:red;' : 'color:green;' }}">{{ $up["message"] }}</span>
                        </div>
                    </div>     
                @endforeach
            @endif
        </div>
    </div>
    <div class="row show-grid team-member color-green">                                    
        <div class="col-xs-12">
            
            <h2>File Transfer</h2><br/>

            <p>The following form has been created to help you when supplying art to SAVE. Fill out all the information and supply all the required elements (ex: graphics, original document and all fonts used*) in an archived format such as SIT or ZIP file. For links to compression tools for both Mac and PC platforms, visit our Online Tools page. We will also accept single graphic files in the following formats: </p>
                <ul>
                    <li>Photos: TIFF, EPS or PSD - 300 DPI, CMYK files</li>
                    <li>Vector: EPS, AI or PDF CMYK files</li>
                </ul>
            <p>* For more information on our Ad Specifications and acceptable file formats, please visit our <a href="{{URL::abs('/')}}/adspecs">Ad Specs</a> page.</p>
            <p>Please archive your files before uploading them to the server. If you do not have a file compression application, click on one of the links below.</p>

            <div class="text-divider3"></div>
<div class = "row">
<div class=" col-md-12">
        <form enctype="multipart/form-data" class = "form" role = "form" action="/fileupload" method="POST">
        <div class = "form-group">
            <span class="h3">Company Name</span>
            <input type ="text" class = "form-control" name="inputName" placeholder = "SaveOn.com"/>
            <br>
            <span class="h3">Client Contact</span>
            <input type = "text" class = "form-control" name="inputClientContact" placeholder ="Susie Saver"/>
            <br>
            <span class="h3">Phone Number</span>
            <input type = "text" class = "form-control" name="inputPhone" placeholder ="248-362-9119"/>
            <br>
            <span class="h3">Name Of Advertiser</span>
            <input type = "text" class = "form-control" name="inputAdvertiser" placeholder ="Sam Saver"/>
            <br>
            <span class="h3">Market</span>
                    <select name = "inputCity" class = "form-control">
                        <option value = "Chicago">Chicago</option>
                        <option value = "Detroit">Detroit</option>
                        <option value = "Lansing">Lansing</option>
                        <option value = "Minneapolis">Minneapolis</option>
                        <option value = "Toledo">Toledo</option>
                        <option value = "Grand Rapids">Grand Rapids</option>
                        <option value = "Lakeshore">Lakeshore</option>
                        <option value = "Kalamazoo">Kalamazoo</option>
                        <option value = "Print.MI.GR">Print.MI.GR</option>
                        <option value = "Print.MI.LK">Print.MI.LK</option>
                        <option value = "Print.MI.KZ">Print.MI.KZ</option>
                    </select>
                <br>
                <span class="h3">Issue (Month)</span>
                
                    <select name = "inputMonth" class = "form-control">
                        <option selected value = "Issue">Issue/Month</option>
                        <option value = "January">January</option>
                        <option value = "February">February</option>
                        <option value = "March">March</option>
                        <option value = "April">April</option>
                        <option value = "May">May</option>
                        <option value = "June">June</option>
                        <option value = "July">July</option>
                        <option value = "August">August</option>
                        <option value = "September">September</option>
                        <option value = "October">October</option>
                        <option value = "November">November</option>
                        <option value = "December">December</option>
                    </select>
                <br>
                <span class="h3">Sales Rep</span>
                <input type = "text" class = "form-control" name="inputSalesRep" placeholder ="Sandra Saver"/>
                <br>
                <span class="h3">E-Mail</span>
                <input type = "email" class = "form-control" name="inputEmail" size = "30" placeholder ="example@saveon.com"/>
                <br>
                <span class="h3">File Attachment</span>
                <input type="file" name="fileattachment1" id="file"><br><button class="btn btn-green">Submit</button>
            <br>
            <br>
        </div>
    </form>
    </div>
</div>

            <div class="text-divider3"></div>
            <a href="http://www.stuffit.com">
                <span class="h4">Stuffit Deluxe for Mac</span>
            </a>

            <p>The standard utility for Macintosh compression, Stuffit Deluxe allows you to compress Macintosh files into .sit or .zip archives for transfer across the Internet.</p>
            <a href="http://www.winzip.com/">
                <span class="h4">WinZip for Windows</span>
            </a>
            
            <p>WinZip is a shareware utility that will compress your Windows files for transfer across the Internet.</p>


            <div class="text-divider3"></div>  

            <span class="h3 hblock">Include in sea/sit/zip file:</span>
            <ul>
                <li>All original ad documents.</li>
                <li>All original graphics &amp; images.</li>
                <li>All fonts used (printer &amp; screen) including all fonts used in .eps files (NO True Type or Multiple Master).</li>
                <li>Photographs supplied on disk must be minimum 300 DPI resolution at 100%. If photo quality is questionable, please supply originals.</li>
            </ul>                                 


        </div>
    </div>
    </div>

@stop