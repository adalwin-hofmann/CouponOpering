@extends('master.templates.master')
@section('page-title')
<h1>Ad Specs</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
  <li class="active">Ad Specs</li>
@stop

@section('sidebar')
@include('master.templates.corporatesidebar')
@stop
@section('body')
<div class="content-bg ad_specs">
    <div class="row">
        <div class="col-xs-12">
           <h2>Acceptable File Formats</h2><br/>
          <ol>
            <li><strong>If sending an Acrobat PDF file, WE REQUIRE THAT IT IS IN THE <a href="http://www.prepressure.com/pdf/basics/pdfx-1a" target="blank">PDF/X-1A:2001</a> FORMAT. This is the format we must deliver to our printer, and PDFs which do not comply with this format may experience errors upon conversion to it. We reserve the right to reject any PDFs that do not comply with the PDF/X-1a:2001 standard.</strong></li>
            <li>Adobe Photoshop CS6 (TIFF, EPS or PSD) or earlier, Adobe Illustrator CS6 or earlier (AI, EPS), and Adobe InDesign CS6 or earlier (package with links and fonts). <strong>No JPEGs or PNGs, please.</strong></li>
            <li><strong>All artwork must be in a CMYK (process color) format. NO SPOT COLORS. Since errors and/or undesirable results can occur when spot colors are converted to process colors, we reserve the right to reject any artwork containing spot colors.</strong></li>
            <li>All other file formats must be approved by the Art Department prior to delivery.</li>
            <li>All documents and/or files sent to SAVE's Art Department must be able to be edited in a Macintosh environment. This includes all graphics, original documents<sup>*</sup> and fonts<sup>**</sup>.</li>
          </ol>
          <hr>
          <h2>Scans/Artwork</h2>
          <ol>
            <li>All supporting artwork must be included (scanned photos, logos, clip art files, etc...). EPS or TIFF files preferred. Do not use JPEG encoding on EPS files, or LZW or other compression on TIFF files (will cause the image to print as black & white). Any other file format must be approved by SAVE prior to submitting. Supporting artwork files must be able to be edited in a Macintosh format (Adobe Photoshop CS6 or Illustrator CS6). Original photos/artwork must be included if computer files are not Mac compliant.</li>
            <li>All rasterized files (TIFF, Photoshop PSD and EPS) must have a minimum resolution of 300 DPI and must be placed in QuarkXpress or InDesign at no larger than 100%. If the resolution is lower we cannot guarantee the quality of the printed piece.<br/>
            *Because of the limited amount of fonts that we fit on our operating system, we reserve the right to replace any body text (non-headline) with a like font.</li>
            <li>All specialty fonts must be included*.<br/>

            *Because of the limited amount of fonts that we fit on our operating system, we reserve the right to replace any body text (non-headline) with a like font.</li>
            <li>All Adobe Illustrator/EPS files must have outline fonts.</li>
            <li>Because of low resolution (72 DPI), most images downloaded from the Internet CANNOT be used.</li>
          </ol>
          <hr>
            <h2>Delivery Options</h2>
            <ol>
              <li>FTP: Please visit the <a href="{{URL::abs('/corporate/fileupload')}}">FTP area</a> on our website to upload your files to our server. We recommend using a file compression utility to avoid file corruption during file transfer.</li>
              <li>Email: To increase efficiency, please contact SAVE's Art Department prior to sending the file. We recommend using a file compression utility (StuffIt/WinZip) to avoid file corruption during file transfer. Send to:</li>
                  <ul>
                      <li>Detroit: <a href="mailto:art.detroit@saveon.com">art.detroit@saveon.com</a></li>
                      <li>Chicago: <a href="mailto:art.chicago@saveon.com">art.chicago@saveon.com</a></li>
                      <li>Twin Cities: <a href="mailto:art.twincities@saveon.com">art.twincities@saveon.com</a></li>
                  </ul>
              <li>Disk: CD/DVD. Use compression if needed. Please supply a color proof of the file for verification. Mail to:<br/>
              SAVE<br/>
              1000 W. Maple Rd., Suite 200<br/>
              Troy, MI 48084<br/>
                Attn: Art Department<br/></li>
            </ol>

            <hr>
            <h2>Questions</h2>
            <ul style="padding-left: 40px;">
              <li>If you have any questions about ad specs or submitting artwork, please contact:<br>
              David Herbst<br>
              248.244.3394<br>
              <a href="mailto:dherbst@saveon.com">dherbst@saveon.com</a><br>
              (Office hours:  9:00 am to 5:00pm EST Monday through Friday.)</li>
            </ul>

            <hr>
              <h2>Book Mailers</h2>
              <div class="pad_top_20">
              <span class="h4 hblock">7.5" x 10.5" Magazine Size (Detroit)</span>
              
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Ad Code</th>
                      <th>Ad Type</th>
                      <th>Finished Size</th>
                      <th>Size w/ Bleed</th>
                      <th>Download Template</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>001, 005, 047</td>
                      <td>Full Page<sup>*</sup></td>
                      <td>7.5"w x 10.5"h</td>
                      <td>7.75"w x 10.75"h</td>
                      <td>
                          <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/001-005-047-SOE-MAG_Full_Page.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                          <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/001-005-047-SOE-MAG_Full_Page.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                          <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/001-005-047-SOE-MAG_Full_Page.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                        </td>
                    </tr>
                    <tr>
                      <td>006</td>
                      <td>Back Cover<sup>*</sup></td>
                      <td>7.5"w x 10.5"h</td>
                      <td>7.75"w x 10.75"h</td>
                      <td>
                        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/6-SOE-MAG_Back_Cover.idml"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                      <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/006-SOE-MAG_Back_Cover.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                      <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/006-SOE-MAG_Back_Cover.ai"><img style="margin:0 15px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                    </td>
                    </tr>
                    <tr>
                      <td>003, 048</td>
                      <td>2-pg Spread*<sup></sup></td>
                      <td>15''w x 10.5''h</td>
                      <td>15.25''w x 10.75''h</td>
                      <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/003-048-SOE-MAG_2Pg_Spread.idml"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                      <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/003-048-SOE-MAG_2Pg_Spread.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                      <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/003-048-SOE-MAG_2Pg_Spread.ai"><img style="margin:0 15px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                    </td>
                    </tr>
                    <tr>
                      <td>007, 049</td>
                      <td>1/2pg Vert.<sup></sup></td>
                      <td>3.375&quot;w x 10&quot;h</td>
                      <td>N/A</td>
                      <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/007-049-SOE-MAG_Half_Page_Vertical.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                      <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/007-049-SOE-MAG_Half_Page_Vertical.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                      <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/007-049-SOE-MAG_Half_Page_Vertical.ai"><img style="margin:0 15px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                    </td>
                    </tr>
                    <tr>
                      <td>004, 008, 045</td>
                      <td>1/2 Pg Horz.</td>
                      <td>7&quot;w x 4.875&quot;h</td>
                      <td>N/A</td>
                      <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/004-008-045-SOE-MAG_Half_Page_Horizontal.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/004-008-045-SOE-MAG_Half_Page_Horizontal.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/004-008-045-SOE-MAG_Half_Page_Horizontal.ai"><img style="margin:0 15px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                      </td>
                    </tr>
                    <tr>
                      <td>009, 016</td>
                      <td>1/4 Pg. <strong>(a)</strong></td>
                      <td>3.375&quot;w x 4.875&quot;h</td>
                      <td>N/A</td>
                      <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/009-0016-SOE-MAG_Quarter_Page.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                      <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/009-0016-SOE-MAG_Quarter_Page.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                      <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/009-0016-SOE-MAG_Quarter_Page.ai"><img style="margin:0 15px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                    </td>
                    </tr>
                    <tr>
                      <td>044</td>
                      <td>Front Cover (main)**<strong></strong></td>
                      <td>7.5&quot; x 10.5&quot;h</td>
                      <td>7.75&quot;w x 10.75&quot;h</td>
                      <td>N/A</td>
                    </tr>
                    <tr>
                      <td>041-043</td>
                      <td>Front Cover Tiles**</td>
                      <td>1.8&quot;w x 2.2&quot;h</td>
                      <td>N/A</td>
                      <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/041-042-043-SOE-MAG_Cover_Tile.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/041-042-043-SOE-MAG_Cover_Tile.psd"><img style="margin:0 4px 0 0;display:inline-block;" title=".PSD for Photoshop" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/041-042-043-SOE-MAG_Cover_Tile.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <p><em><sup>*</sup>Add 1/8" to all sides for bleed area. Keep all text 1/4" in from trim (finished) size.</em> <br/>
              <em><sup>**</sup>For our covers, only separate art elements can to be provided (high-res). We can not accept press-ready files.</em> <br/>
              <em><strong>(a)</strong> - Quarter page ads are limited to only 4 coupon offers, set to the right side of the ad space.</em> <br/>
              <em><strong>(b)</strong> - 1/6 page ads are limiteds to only 3 coupon offers, set to the right side of the ad space.</em></p>
              <div class="pad_top_20">
              <span class="h4 hblock">9" x 10.5" Magazine - Oversized <!--(Twin Cities &amp; Ann Arbor)--></span>
              
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Ad Code</th>
                      <th>Ad Type</th>
                      <th>Finished Size</th>
                      <th>Size w/ Bleed</th>
                      <th>Download Template</th>
                    </tr>
                  </thead>
                  <tbody>
                  <tr>
                      <td>011, 111</td>
                      <td>1/3 Page Ad</td>
                      <td>8.5&quot;w x 3.125&quot;h</td>
                      <td>N/A</td>
                      <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/011_SOE-MAG_Oversized_OneThirdPage.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/011_SOE-MAG_Oversized_OneThirdPage.psd"><img style="margin:0 4px 0 0;display:inline-block;" title=".PSD for Photoshop" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/011_SOE-MAG_Oversized_OneThirdPage.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a></td>
                    </tr>
                    <tr>
                      <td>012, 112, 113</td>
                      <td>2/3 Page Ad</td>
                      <td>8.5&quot;w x 6.5&quot;h</td>
                      <td>N/A</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>013, 015 </td>
                      <td>1/6 Page <strong>(b)</strong></td>
                      <td>4.125&quot;w x 3.125&quot;h</td>
                      <td>N/A</td>
                      <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/013-015-SOE-MAG-OV_Sixth_Page.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/013-015-SOE-MAG-OV_Sixth_Page.psd"><img style="margin:0 4px 0 0;display:inline-block;" title=".PSD for Photoshop" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/013-015-SOE-MAG-OV_Sixth_Page.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a></td>
                    </tr>
                    <tr>
                      <td>020</td>
                      <td>Front Cover Ad (MN &amp;DT SOFH Only)<strong></strong></td>
                      <td>9&quot;w x 8.125&quot;h</td>
                      <td>9.25&quot;w x 8.25&quot;h†</td>
                      <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/020-SOE-MAG-OV_Front_Cover_Ad_MN-and-SOFH-Only.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/020-SOE-MAG-OV_Front_Cover_Ad_MN-and-SOFH-Only.psd"><img style="margin:0 4px 0 0;display:inline-block;" title=".PSD for Photoshop" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/020-SOE-MAG-OV_Front_Cover_Ad_MN-and-SOFH-Only.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                      </td>
                    </tr>
                    <tr>
                      <td>050</td>
                      <td>Half Page Vert.<em class="text-muted"><small></small></em></td>
                      <td>4.125&quot;w x 9.875&quot;h</td>
                      <td>N/A</td>
                      <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/050-SOE-MAG-OV_Half_Page_Vertical.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/050-SOE-MAG-OV_Half_Page_Vertical.psd"><img style="margin:0 4px 0 0;display:inline-block;" title=".PSD for Photoshop" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/050-SOE-MAG-OV_Half_Page_Vertical.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                      </td>
                    </tr>
                    <tr>
                      <td>051</td>
                      <td>2PG Spread</td>
                      <td>17.5&quot;w x 9.875&quot;h</td>
                      <td>N/A</td>
                      <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/051-SOE-MAG-OV_2Page_Spread.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/051-SOE-MAG-OV_2Page_Spread.psd"><img style="margin:0 4px 0 0;display:inline-block;" title=".PSD for Photoshop" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/051-SOE-MAG-OV_2Page_Spread.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                      </td>
                    </tr>
                    <tr>
                      <td>052</td>
                      <td>Qtr. Page <strong>(a)</strong></td>
                      <td>4.125&quot;w x 4.8125&quot;h</td>
                      <td>N/A</td>
                      <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/052-SOE-MAG-OV_Quarter_Page.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/052-SOE-MAG-OV_Quarter_Page.psd"><img style="margin:0 4px 0 0;display:inline-block;" title=".PSD for Photoshop" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/052-SOE-MAG-OV_Quarter_Page.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                      </td>
                    </tr>
                    <!--<tr>
                      <td>11, 12, 13</td>
                      <td>Front Cover (Tiles)<sup>**</sup></td>
                      <td>1.675"w x 2.05"h</td>
                      <td>N/A</td>
                      <td>
                        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/11-12-13-SOE-MAG-OV_Cover_Tile.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                      <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/11-12-13-SOE-MAG-OV_Cover_Tile.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                      <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/11-12-13-SOE-MAG-OV_Front_Cover_Tile.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a></td>
                    </tr>
                    <tr>
                      <td>14</td>
                      <td>Front Cover Header/Billboard<sup>**</sup></td>
                      <td>9.86"w x 1.914"h</td>
                      <td>N/A</td>
                      <td>
                        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/14-SOE-MAG-OV_Front_Cover_Billboard.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/14-SOE-MAG-OV_Front_Cover_Billboard.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/14-SOE-MAG-OV_Front_Cover_Billboard.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a></td>
                    </tr>-->
                    <!--<tr>
                      <td>15</td>
                      <td>1/6 Page<strong>(b)</strong></td>
                      <td>4.75"w x 3.125"h</td>
                      <td>N/A</td>
                      <td>
                        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/15-SOE-MAG-OV_Sixth_Page.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                      <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/15-SOE-MAG-OV_Sixth_Page.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                      <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/15-SOE-MAG-OV_Sixth_Page.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a></td>
                    </tr>-->
                    <tr>
                      <td>053</td>
                      <td>Full Page<sup></sup></td>
                      <td>8.5&quot;w x 9.875&quot;h</td>
                      <td>N/A</td>
                      <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/053-SOE-MAG-OV_Full_Page.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/053-SOE-MAG-OV_Full_Page.psd"><img style="margin:0 4px 0 0;display:inline-block;" title=".PSD for Photoshop" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/053-SOE-MAG-OV_Full_Page.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                      </td>
                    </tr>
                    <!--<tr>
                      <td>17</td>
                      <td>SOFH Front Cover Ad (Insert/Section)<sup>*</sup></td>
                      <td>8.476"w x 10.5"h</td>
                      <td>8.726"w x 10.75"h</td>
                      <td>
                        <a href="http://saveoneverything_assets.s3.amazonaws.com/corporate/ad_spec_templates/17-SOFH-Front_Cover_Ad-Only.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="http://saveoneverything_assets.s3.amazonaws.com/corporate/ad_spec_templates/17-SOFH-Front_Cover_Ad-Only.psd"><img style="margin:0 4px 0 0;display:inline-block;" title=".PSD for Photoshop" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="http://saveoneverything_assets.s3.amazonaws.com/corporate/ad_spec_templates/17-SOFH-Front_Cover_Ad-Only.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                      </td>
                    </tr>
                    <tr>
                      <td>18</td>
                      <td>SOFH Back Cover Ad (Insert)<sup>*</sup></td>
                      <td>10.25"w x 10.5"h</td>
                      <td>10.5"w x 10.75"h</td>
                      <td>
                        <a href="http://saveoneverything_assets.s3.amazonaws.com/corporate/ad_spec_templates/18-SOFH-Back_Cover_Ad.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="http://saveoneverything_assets.s3.amazonaws.com/corporate/ad_spec_templates/18-SOFH-Back_Cover_Ad.psd"><img style="margin:0 4px 0 0;display:inline-block;" title=".PSD for Photoshop" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="http://saveoneverything_assets.s3.amazonaws.com/corporate/ad_spec_templates/18-SOFH-Back_Cover_Ad.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                      </td>
                    </tr>-->
                    <tr>
                      <td>054</td>
                      <td>Half Page Horz.<em class="text-muted"><small></small></em></td>
                      <td>8.5&quot;w x 4.8125&quot;h</td>
                      <td>N/A</td>           
                      <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/054-SOE-MAG-OV_Half_Page_Horizontal.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/054-SOE-MAG-OV_Half_Page_Horizontal.psd"><img style="margin:0 4px 0 0;display:inline-block;" title=".PSD for Photoshop" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/054-SOE-MAG-OV_Half_Page_Horizontal.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                      </td>
                    </tr>
                    <tr>
                      <td>055</td>
                      <td>Front Cover Ad<em class="text-muted"><small></small></em></td>
                      <td>9&quot;w x 7.35&quot;h</td>
                      <td>9.25&quot;w x 7.35&quot;h†††</td>
                      <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/055-SOE-MAG-OV_Front_Cover_Ad.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/055-SOE-MAG-OV_Front_Cover_Ad.psd"><img style="margin:0 4px 0 0;display:inline-block;" title=".PSD for Photoshop" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/055-SOE-MAG-OV_Front_Cover_Ad.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                      </td>
                    </tr>
                    <tr>
                      <td>056</td>
                      <td>Back Cover Ad</td>
                      <td>9.5&quot;w x 7.75&quot;h</td>
                      <td>9.75&quot;w x 7.875&quot;h††</td>
                      <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/056-SOE-MAG-OV_Back_Cover.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/056-SOE-MAG-OV_Back_Cover.psd"><img style="margin:0 4px 0 0;display:inline-block;" title=".PSD for Photoshop" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/056-SOE-MAG-OV_Back_Cover.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                      </td>
                    </tr>
                    <tr>
                      <td>057</td>
                      <td><p>Front Cover 1/2 Page Ad</p></td>
                      <td>8.36&quot;w x 4.446&quot;h</td>
                      <td>N/A</td>
                      <td><a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/057-SOE-MAG-OV_Front_Cover_Half_Page.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a> <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/057-SOE-MAG-OV_Front_Cover_Half_Page.psd"><img style="margin:0 4px 0 0;display:inline-block;" title=".PSD for Photoshop" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a> <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/057-SOE-MAG-OV_Front_Cover_Half_Page.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a></td>
                    </tr>
                    <tr>
                      <td>058 </td>
                      <td>Front Cover 1/3 Page Ad</td>
                      <td>8.36&quot;w x 2.25&quot;h</td>
                      <td>N/A</td>
                      <td><a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/058-SOE-MAG-OV_Front_Cover_Third_Page.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a> <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/058-SOE-MAG-OV_Front_Cover_Third_Page.psd"><img style="margin:0 4px 0 0;display:inline-block;" title=".PSD for Photoshop" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a> <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/058-SOE-MAG-OV_Front_Cover_Third_Page.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a></td>
                    </tr>
                    <tr>
                      <td>059</td>
                      <td>Back Cover (Chicago &amp; Lakeshore only)</td>
                      <td>9&quot;w x 8.5&quot;h</td>
                      <td>9.25&quot;w x 8.625&quot;h†</td>
                      <td><a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/059-SOE-MAG-OV-Back_Cover_Chicago-Lakeshore.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a> <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/059-SOE-MAG-OV-Back_Cover_Chicago-Lakeshore.psd"><img style="margin:0 4px 0 0;display:inline-block;" title=".PSD for Photoshop" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a> <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/059-SOE-MAG-OV-Back_Cover_Chicago-Lakeshore.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a></td>
                    </tr>
                    <tr>
                      <td>119</td>
                      <td>2/9 Page Sponsor Ad</td>
                      <td>5.583&quot;w x 3.125&quot;h</td>
                      <td>N/A</td>
                      <td><a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/119-SOE-MAG-OV_Two-Ninths_Page.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a> <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/119-SOE-MAG-OV_Two-Ninths_Page.psd"><img style="margin:0 4px 0 0;display:inline-block;" title=".PSD for Photoshop" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a> <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/119-SOE-MAG-OV_Two-Ninths_Page.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a></td>
                    </tr>
                    <tr>
                      <td>120</td>
                      <td>1/9 Page Sponsor Ad</td>
                      <td>2.667&quot;w x 3.125&quot;h</td>
                      <td>N/A</td>
                      <td><a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/120-SOE-MAG-OV_One-Ninth_Page.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a> <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/120-SOE-MAG-OV_One-Ninth_Page.psd"><img style="margin:0 4px 0 0;display:inline-block;" title=".PSD for Photoshop" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a> <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/120-SOE-MAG-OV_One-Ninth_Page.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <p><em><sup>*</sup>Add 1/8" to all sides for bleed area. Keep all text 1/4" in from trim (finished) size.<br/>
              <!-- <sup>**</sup>For our cover billboards &amp; footers, only separate art elements can to be provided (high-res). We can not accept press-ready files.<br/>-->
              † No bleed on top. Right, left & bottom only.<br/>
              †† No bleed on bottom. Right, left & top only.<br/>
              ††† No bleed on right. Top, bottom & left only.<br/>
              <!--†† No bleed on top or left sides. Right and bottom only.<br/>-->
              <strong>(a)</strong> - Quarter page ads are limited to only 4 coupon offers, set to the right side of the ad space.<br/>
              <strong>(b)</strong> - 1/6 page ads are limited to only 3 coupon offers, set to the right side of the ad space.</em></p>
              <div class="pad_top_20">
              <span class="h4 hblock">Save On Cars & Trucks Ads (SOCT - All Markets)</span>
              
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Ad Code</th>
                      <th>Ad Type</th>
                      <th>Finished Size</th>
                      <th>Size w/ Bleed</th>
                      <th>Download Template</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>090</td>
                      <td>SCT Spread (H)<sup>*</sup>†</td>
                      <td>21"w x 10.875"h</td>
                      <td>21.25"w x 11.125"h</td>
                      <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/090-SOCT_2Page_Spread_Horzontal.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                      <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/090-SOCT_Horz_2Page_Spread.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                      <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/090-SOCT_2p_Spread_Horizontal.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                    </td>
                    </tr>
                    <tr>
                      <td>092</td>
                      <td>SCT 2 Pg. Spread (V)<sup>*</sup>†</td>
                      <td>10.875"w x 21"h</td>
                      <td>11.125"w x 21.25"h</td>
                      <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/092-SOCT_2Page_Spread_Vertical.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                      <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/092-SOCT_Vert_2Page_Spread.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                      <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/092-SOCT_2Pg_Spread_Vertical.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                    </td>
                    </tr>
                    <tr>
                      <td>093</td>
                      <td>SCT Full Page<sup>*</sup></td>
                      <td>10.5"w x 10.875"h</td>
                      <td>10.75"w x 11.125"h</td>
                      <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/093-SOCT_Full_Page.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                      <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/093-SOCT_Full_Page.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                      <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/093-SOCT_Full_Page.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                    </td>
                    </tr>
                    <tr>
                      <td>094, 194</td>
                      <td>SCT Half Page</td>
                      <td>10"w x 5"h</td>
                      <td>N/A</td>
                      <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/094-SOCT_Half_Page.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                      <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/094-SOCT_Half_Page.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                      <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/094-SOCT_Half_Page.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                    </td>
                    </tr>
                    <tr>
                      <td>095</td>
                      <td>SCT Cover<sup>*</sup></td>
                      <td>8.625"w x 10.875"h</td>
                      <td>8.875"w x 11.125"h</td>
                      <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/095-SOCT_Cover_Ad.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/095-SOCT_Cover_Ad.psd"><img style="margin:0 4px 0 0;display:inline-block;" title=".PSD for Photoshop" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/095-SOCT_Cover_Ad.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a></td>
                    </tr>
                    <tr>
                      <td>096</td>
                      <td>SCT Back Cover<sup>*</sup></td>
                      <td>10.5"w x 10.875"h</td>
                      <td>10.75"w x 11.125"h</td>
                      <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/096-SOCT_Back_Cover.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/096-SOCT_Back_Cover.psd"><img style="margin:0 4px 0 0;display:inline-block;" title=".PSD for Photoshop" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/096-SOCT_Back_Cover.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a></td>
                    </tr>
                    <tr>
                      <td>097</td>
                      <td>SCT Qtr. Page</td>
                      <td>4.875"w x 5"h</td>
                      <td>N/A</td>
                      <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/097-SOCT_Quarter_Page.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                      <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/097-SOCT_Quarter_Page.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                      <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/097-SOCT_Quarter_Page.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                    </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <p><em><sup>*</sup>Add 1/8" to all sides for bleed area. Keep all text 3/8" in from trim (finished) size.<br/>
              † Include 3/8" center gutter area for all text and images (spine glued).</em></p>
              <hr>
                <h2>Insert Media &amp; DAL Cards</h2>
                <div class="pad_top_20">
                <span class="h4 hblock">SAVE Insert Media - 7.5" x 10.5" Magazine &amp; 10.25&quot; x 10.5&quot; Magazine Oversize Mailers (Detroit &amp; Chicago)</span>
                
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Ad Code</th>
                        <th>Ad Type</th>
                        <th>Finished Size</th>
                        <th>Size w/ Bleed</th>
                        <th>Download Template</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>070</td>
                        <td>Insert Postcard<sup>*</sup></td>
                        <td>5.5"w x 3.5"h</td>
                        <td>5.75"w x 3.75"h</td>
                        <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/070-SOE_Insert_Postcard.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                          <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/070-SOE-MAG_Insert_Postcard.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                          <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/070-SOE-MAG_Insert_Postcard.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                        </td>
                      </tr>
                      <tr>
                        <td>071</td>
                        <td>Insert Booklets (8/12/16pg)<sup>*</sup></td>
                        <td>7"w x 3.5"h</td>
                        <td>7.25"w x 3.75"h</td>
                        <td>
                          <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/071-SOE_Insert_Booklets.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                          <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/071-SOE-MAG_Insert_Booklet.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                          <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/071-SOE_Insert_Booklets.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                        </td>
                      </tr>
                      <tr>
                        <td>072</td>
                        <td>Custom Sized Pieces<sup>*</sup></td>
                        <td>TBD</td>
                        <td>TBD</td>
                        <td>N/A</td>
                      </tr>
                      <tr>
                        <td>073</td>
                        <td>SAVE DAL Cards<sup>*</sup> (Front)<br/>
                            SAVE DAL Cards<sup>*</sup> (Back - ad space only)</td>
                        <td>9"w x 5"h<br/>
                        4.75"w x 5"h</td>
                        <td>9.25"w x 5.25"h<br/>
                        4.875"w x 5.25"h†</td>
                        <td>
                          <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/073-SOE-DAL_Card.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/073-SOE-MAG_DAL_Card_PSD.zip"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/073-SOE-MAG_DAL_Card_AI.zip"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                      </td>
                      </tr>
                      <tr>
                        <td>075</td>
                        <td>Bind-In Piece<sup>*</sup></td>
                        <td>TBD</td>
                        <td>TBD</td>
                        <td>N/A</td>
                      </tr>
                      <tr>
                        <td>080</td>
                        <td>Half Size Insert Card<sup>*</sup></td>
                        <td>8.5"w x 5.25"h</td>
                        <td>8.75"w x 5.5"h</td>
                        <td>
                          <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/080-SOE-MAG_Half_Size_Insert_Card-Flyer.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/081-083-SOE-MAG_Full_Size_Insert_Card-Flyer_PSD.zip"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/080-SOE-Insert_Half_Page_AI.zip"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                      </td>
                      </tr>
                      <tr>
                        <td>081</td>
                        <td>Full Size Insert Card<sup>*</sup> (Detroit only)</td>
                        <td>8.5"w x 10.5"h</td>
                        <td>8.75"w x 10.75"h</td>
                        <td>
                          <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/081-083-SOE-MAG_Full_Size_Insert_Card-Flyer.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/081-083-SOE-MAG_Full_Size_Insert_Card-Flyer_PSD.zip"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/081-083-SOE-Insert_Full_Page_AI.zip"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                      </td>
                      </tr>
                      <tr>
                        <td>083</td>
                        <td>Full Page Flyer<sup>*</sup></td>
                        <td>8.5"w x 10.5"h</td>
                        <td>8.75"w x 10.75"h</td>
                        <td>
                          <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/081-083-SOE-MAG_Full_Size_Insert_Card-Flyer.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/081-083-SOE-MAG_Full_Size_Insert_Card-Flyer_PSD.zip"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/081-083-SOE-Insert_Full_Page_AI.zip"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                      </td>
                      </tr>
                      <tr>
                        <td>084</td>
                        <td>4-Page Circular<sup>*</sup> (Detroit only)</td>
                        <td>17"w x 10.5"h</td>
                        <td>17.25"w x 10.75"h</td>
                        <td>
                          <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/084-SOE-MAG_4-Page_Circular.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a><a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/084-SOE-MAG-OV_4-Page_Circular.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/084-MAG-4Pg_Circular.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                      </td>
                      </tr>
                      <tr>
                        <td>180</td>
                        <td>Oversized Half Size Insert Card* (Chicago only)</td>
                        <td>10&quot;w x 5.25&quot;h</td>
                        <td>10.25&quot;w x 5.5&quot;h</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>082</td>
                        <td>C.A.R.S. Big 4 Page insert* (Chicago only)</td>
                        <td>21&quot;w x 10.875&quot;h</td>
                        <td>21.25&quot;w x 11.125&quot;h</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>183</td>
                        <td>Oversized Flyer* (Chicago only)</td>
                        <td>10&quot;w x 10.5&quot;h</td>
                        <td>10.25&quot;w x 10.75&quot;h</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>184</td>
                        <td>Oversized 4 Page Circular* (Chicago only)</td>
                        <td>20&quot;w x 10.5&quot;h</td>
                        <td>20.25&quot;w x 10.75&quot;h</td>
                        <td>&nbsp;</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <p><em><sup>*</sup>Add 1/8" to all sides for bleed area. Keep all text 1/4" in from trim (finished) size.<br/>
                † No bleed on right side. Left, top & bottom only.</em></p>

                <div class="pad_top_20">
                <span class="h4 hblock">SAVE Insert Media - 10.25" x 10.5" Magazine - Oversized (Twin Cities &amp; Ann Arbor)</span>
                
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Ad Code</th>
                        <th>Ad Type</th>
                        <th>Finished Size</th>
                        <th>Size w/ Bleed</th>
                        <th>Download Template</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>060</td>
                        <td>Standard Postcard<sup>**</sup></td>
                        <td>8.5"w x 4.375"h</td>
                        <td>N/A</td>
                        <td>
                          <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/060_SOE-MAG-OV_Standard_Postcard.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/060_SOE-MAG-OV_Standard_Postcard.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/060-Insert_TAB_Standard_Postcard.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                      </td>
                      </tr>
                      <tr>
                        <td>061</td>
                        <td>Premium Postcard<sup>*</sup></td>
                        <td>11"w x 5.5"h</td>
                        <td>11.5"w x 6"h</td>
                        <td>
                          <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/061-TAB-Insert_Half_Flyer.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/061_SOE-MAG-OV_Half_Flyer.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/061-TAB-Insert_Half_Flyer.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                      </td>
                      </tr>
                      <tr>
                        <td>062</td>
                        <td>Value Flyer<sup>**</sup></td>
                        <td>8.5"w x 11"h</td>
                        <td>N/A</td>
                        <td>
                          <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/062_SOE-MAG-OV_Value_Flyer.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                          <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/062_SOE-MAG-OV_Value_Flyer.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                          <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/062-TAB-Insert_Value_Flyer.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                        </td>
                      </tr>
                      <tr>
                        <td>063</td>
                        <td>Big Flyer<sup>*</sup></td>
                        <td>8.375"w x 12"h</td>
                        <td>8.875"w x 12.5"h</td>
                        <td>
                          <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/063_SOE-MAG-OV_Big_Flyer.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/063_SOE-MAG-OV_Big_Flyer.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/063-TAB-Big_Flyer.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                      </td>
                      </tr>
                      <tr>
                        <td>067</td>
                        <td>Premium Flyer (60# paper stock)<sup>**</sup></td>
                        <td>10"w x 11"h</td>
                        <td>N/A</td>
                        <td>
                          <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/067_TAB+Premium+Flyer.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/067_SOE-MAG-OV_Premium_Flyer.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/067-TAB_Premium_Flyer.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                      </td>
                      </tr>
                      <tr>
                        <td>068</td>
                        <td>Mega Flyer<sup>*</sup></td>
                        <td>10.875"w x 12.25"h</td>
                        <td>11.375"w x 12.75"h</td>
                        <td>
                          <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/068_SOE-MAG-OV_Mega_Flyer.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/068_SOE-MAG-OV_Mega_Flyer.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/068-TAB_Mega_Flyer.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a>
                      </td>
                      </tr>
                      <tr>
                        <td>069</td>
                        <td>Big 4-Pager<sup>**</sup></td>
                        <td>21"w x 10.5"h</td>
                        <td>N/A</td>
                        <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/069_SOE-MAG-OV_Big_4-Pager.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a> <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/069_SOE-MAG-OV_Big_4-Pager.psd"><img style="margin:0 4px 0 0;display:inline-block;" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a> <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/069_SOE-MAG-OV_Big_4-Pager069_SOE-MAG-OV_Big_4-Pager.ai"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a><a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/069_SOE-MAG-OV_Big_4-Pager.idml"></a></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <p><em><sup>*</sup>Add 1/4" to all sides for bleed area. Keep all text 1/4" in from trim (finished) size.</em><br>
                  <em><sup>**</sup>Artwork must include a 1/4" white border on all sides within finished size (no bleed).</em>
                </p>
                <div class="pad_top_20">
                <span class="h4 hblock">Misc. RED PLUM Products</span>
                
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Ad Code</th>
                        <th>Ad Type</th>
                        <th>Finished Size</th>
                        <th>Size w/ Bleed</th>
                        <th>Download Template</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--<tr>
                        <td>64</td>
                        <td>Red Plum DAL w/ Perf** (Front)<br>
                          Red Plum DAL w/ Perf** (Back less address area)</td>
                        <td>8.375"w x 5"h<br>
                          4.4925"w x 5"h</td>
                        <td>8.625"w x 5.25"h<br>
                          4.6175"w x 5.25"h†</td>
                        <td>N/A</td>
                      </tr>-->
                      <tr>
                        <td>065</td>
                        <td>Red Plum DAL<sup>*</sup> (Front)<br/>
                            Red Plum DAL<sup>*</sup> (Back - ad space only)</td>
                        <td>8.375"w x 5"h<br/>
                        4.4925"w x 5"h</td>
                        <td>8.625"w x 5.25"h<br/>
                        4.6175"w x 5.25"h†</td>
                        <td>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/065-Red_Plum_DAL_Card.idml"><img style="margin:0 4px 0 0;display:inline-block;" title=".IDML for CS5 &amp; Below" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_indesign-idml.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/065-Red_Plum_DAL_Card_PSD.zip"><img style="margin:0 4px 0 0;display:inline-block;" title=".PSD for Photoshop" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_photoshop.png"></a>
                        <a href="https://s3.amazonaws.com/saveoneverything_assets/corporate/ad_spec_templates/065-Red_Plum_DAL_Card_AI.zip"><img style="margin:0 4px 0 0;display:inline-block;" title=".ai for Illustrator" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/icons/adobe_illustrator.png"></a></td>
                      </tr>
                      <tr>
                        <td>077</td>
                        <td>Red Plum Wrap Cover Billboard<sup>*</sup></td>
                        <td>2.625"w x 1.625"h</td>
                        <td>N/A</td>
                        <td>N/A</td>
                      </tr>
                      <tr>
                        <td>079</td>
                        <td>Custom Sized RP Pieces<sup>*</sup></td>
                        <td>TBD</td>
                        <td>TBD</td>
                        <td>N/A</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <p><em><sup>*</sup>Add 1/8" to all sides for bleed area. Keep all text 1/4" in from trim (finished) size.<br/>
                    †No bleed on right side. Left, top & bottom only.</em></p>
        </div>
    </div>
</div>
@stop