@extends('master.templates.master')
@section('page-title')
<h1>Advertising Frequently Asked Questions</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Frequently Asked Questions</li>
@stop

@section('sidebar')
@include('master.templates.corporatesidebar')
@stop

@section('body')
<div class = "content-bg">
<div class="panel-group" id="accordion">
  <div class="panel panel-default">
    <div class="panel-heading">
      <span class="panel-title h4 hblock">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span>
          Are you able to use files from other programs such as Microsoft Word, Publisher and PowerPoint?
        </a>
      </span>
    </div>
    <div id="collapseOne" class="panel-collapse collapse collapse">
      <div class="panel-body">
        No. Because our production process is based around InDesign 8.0 (CS6), we can only accept files that are compatible with that software. However, we can accept Microsoft Word files for text only. Applications such as Word, Publisher and PowerPoint were not designed for large-scale printing, and any images imported into these programs cannot be exported for use.
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <span class="panel-title h4 hblock">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"><span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span>
          Can images from the internet be used in my ad?
        </a>
      </span>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse">
      <div class="panel-body">
        Rarely. Standard files from a website (JPEG, GIF, etc...) never reproduce well in print.  These low quality images have a standard resolution of 72DPI (dots per inch) and are intended to be viewed on computer monitors only.  Capturing files from the Internet is not recommended. The exception to this rule is if a file is available through a website that has "print specific" (min. 300 DPI) artwork.  These files are usually available through much larger, corporate websites.
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <span class="panel-title h4 hblock">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFAQThree"><span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span>
          What is &quot;finished size&quot;?
        </a>
      </span>
    </div>
    <div id="collapseFAQThree" class="panel-collapse collapse">
      <div class="panel-body">
        On ad sizes which bleed, the finished size, also known as the &quot;trim size&quot; or the &quot;final size&quot;, is the size of the printed piece after the excess areas have been trimmed away. Because of variations inherent in the printing process, the actual trim can and will deviate from the intended trim line. This is why it is important to have a full bleed, and to keep all vital elements within the live area.
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <span class="panel-title h4 hblock">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour"><span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span>
          What is &quot;bleed&quot;?
        </a>
      </span>
    </div>
    <div id="collapseFour" class="panel-collapse collapse collapse">
      <div class="panel-body">
        If a photo or background is set up to extend beyond the finished size, it is said to "bleed" off the page. Bleeds are available on our Full Page ads (Detroit &amp; Chicago only), 2-page spread ads, Insert Media (including DAL Cards), and the back cover of our booklet. Ads with a bleed will generally have the bleed on all 4 sides. The exceptions are the back cover of the Chicago Magazine Size (Detroit back cover bleeds on all four sides), the back cover of the Tabloid Size (Ann Arbor &amp; Twin Cities), and the back (address side) of DAL cards. These bleed only on three sides. When creating a bleed, the photo or background should extend all the way to the edge, so it fills the entire bleed area.
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <span class="panel-title h4 hblock">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive"><span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span>
          What is "live area"? 
        </a>
      </span>
    </div>
    <div id="collapseFive" class="panel-collapse collapse collapse">
      <div class="panel-body">
        The area where vital text, images, and other graphic elements, that are not intended to bleed, should fall within.  This will assure that no critical information is lost on the final trimmed piece.  On all of our products which bleed, the boundaries of the live area lie 1/4" in from the trim line.
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <span class="panel-title h4 hblock">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseSix"><span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span>
          What is "gutter"?
        </a>
      </span>
    </div>
    <div id="collapseSix" class="panel-collapse collapse collapse">
      <div class="panel-body">
        The gutter refers to the center portion of our open booklet, where the left and right hand pages come together.
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <span class="panel-title h4 hblock">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseSeven"><span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span>          Can I place text across the gutter of a 2-page spread?
        </a>
      </span>
    </div>
    <div id="collapseSeven" class="panel-collapse collapse collapse">
      <div class="panel-body">
        No. When designing a 2-page spread ad for our booklet, it is recommended that any text be kept a minimum of 1/8" from each side of the center point (1/4" gutter total), so that no wording is lost or unreadable.  Because photos and backgrounds are easier to visually align, even when slightly off, these may run across this area.
      </div>
    </div>
  </div>
</div>
</div>

@stop