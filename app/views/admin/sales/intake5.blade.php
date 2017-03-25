@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.sales.master.templates.sidebar', array())
@stop
@section('body')

<!--BEGIN MAIN CONTENT-->
<div id="main" role="main">
  <div class="block">
    <div class="clearfix"></div>

    <!--page title-->
    <div class="pagetitle">
      <h1>Intake Form</h1> 
      <div class="clearfix"></div>
    </div>
    <!--page title end-->

    <hr>

    <ul class="inline center-block">
      <li><span class="badge">1</span></li>
      <li><span class="badge">2</span></li>
      <li><span class="badge">3</span></li>
      <li><span class="badge">4</span></li>
      <li><span class="badge badge-inverse">5</span></li>
    </ul>

    <div class="center-square">

    <div class="main-content">
      <h2>Offers <a href="#myModal" data-toggle="modal"><span class="badge badge-inverse">?</span></a></h2>
      <form method="post" action="/intakecomplete" class="form-horizontal">

        <ul class="nav nav-tabs" id="myTab">
          <li class="active"><a href="#savetodays">Save Today</a></li>
          <li><a href="#coupons">Coupon</a></li>
          <li><a href="#contests">Contest</a></li>
        </ul>
         
        <div class="tab-content">
          <div class="tab-pane active" id="savetodays">
            <table class="form-table">
              <tr>
                <td>Offer: <a href="#myModal2" data-toggle="modal"><span class="badge badge-inverse">?</span></a></td>
                <td><input type="text" placeholder="" name="offername">
              </tr><tr>
                <td>Disclaimer:</td>
                <td><textarea id="disclaimer" name="disclaimer" class="fill-up" rows="10"></textarea></td>
              </tr><tr>
                <td class="text-right" colspan="2">
                  <label>Expiration Date</label>
                    <div class="inline input-append">
                        <input id="expires_at" name="expires_at" class="fill-up" type="text" placeholder="Expiration Date">
                        <button class="btn" type="button"><i class="icon-calendar"></i></button>
                    </div>
                </td>
              </tr>
            </table>
            <p><a href="#">+ Add Additional Save Todays</a></p>
          </div>
          <div class="tab-pane" id="coupons">
            <p><label class="radio">
                <input type="radio" name="optionsRadios" id="optionsRadios1" value="usePrintAd">
                Use Print Ad
              </label></p>
            <p>&quot;Or&quot;</p>
            <p><label class="radio">
                <input type="radio" name="optionsRadios" id="optionsRadios2" value="useCustom">
                Use Custom Coupons
              </label></p>
            <table class="form-table">

              <tr>
                <td>Offer: <a href="#myModal3" data-toggle="modal"><span class="badge badge-inverse">?</span></a></td>
                <td><input type="text" placeholder="" name="offername">
              </tr><tr>
                <td>Disclaimer:</td>
                <td><textarea id="disclaimer" name="disclaimer" class="fill-up" rows="10"></textarea></td>
              </tr><tr>
                <td class="text-right" colspan="2">
                  <label>Expiration Date</label>
                    <div class="inline input-append">
                        <input id="expires_at" name="expires_at" class="fill-up" type="text" placeholder="Expiration Date">
                        <button class="btn" type="button"><i class="icon-calendar"></i></button>
                    </div>
                </td>
              </tr>
            </table>
            <p><a href="#">+ Add Additional Coupons</a></p>
          </div>
          <div class="tab-pane" id="contests">
            <table class="form-table">
              <tr>
                <td>Name:</td>
                <td><input type="text" placeholder="" name="contestname">
              </tr><tr>
                <td colspan="2">
                  <label>Start Date</label>
                    <div class="inline input-append">
                        <input id="expires_at" name="expires_at" class="input-mini" type="text" placeholder="Start Date">
                        <button class="btn" type="button"><i class="icon-calendar"></i></button>
                    </div>
                  &nbsp;&nbsp;&nbsp;
                  <label>End Date</label>
                    <div class="inline input-append">
                        <input id="expires_at" name="expires_at" class="input-mini" type="text" placeholder="End Date">
                        <button class="btn" type="button"><i class="icon-calendar"></i></button>
                    </div>
                </td>
              </tr><tr>
                <td>Description:</td>
                <td><textarea id="description" name="description" class="fill-up" rows="5"></textarea></td>
              </tr><tr>
                <td>Disclaimer:</td>
                <td><textarea id="disclaimer" name="disclaimer" class="fill-up" rows="5"></textarea></td>
              </tr><tr>
                <td>Follow-up Email:</td>
                <td><input type="email" placeholder="" name="email">
              </tr>
            </table>
          </div>
        </div>

        <p class="text-right">
          <button type="button" class="btn btn-prev">Prev</button>
          <button type="submit" class="btn btn-next">Finish</button>
        </p>
        
        
      </form>
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
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">The Offers Section</h3>
  </div>
  <div class="modal-body">
    <p>In this section, we need you to eneter in the information necessary for your merchant's Save Today's, coupons, or contests. The more detailed the information given, the better the offer will appear on our site.</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>

<!-- Modal -->
<div id="myModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel2">Save Today Information</h3>
  </div>
  <div class="modal-body">
    <p>Offer field: What the user will ultimately be saving. Note: Must be 40% off to truly qualify as a Save Today or Daily Deal</p>
    <p>Disclaimer field: Further details regarding the offer and its terms of use.</p>
    <p>Expiration Date field: The date in which this Save Today will be marked as expired.</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>

<!-- Modal -->
<div id="myModal3" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel3">Coupon Information</h3>
  </div>
  <div class="modal-body">
    <p>You can either request that a content specialist use a print ad to pull coupon details, or enter in any number of custom coupons.</p>
    <p>A custom coupon contains:</p>
    <p>Offer field: What a user will gain by using a coupon.</p>
    <p>Disclaimer field: Further details regarding the offer and its terms of use.</p>
    <p>Expiration Date field: The date in which this coupon will be marked as expired.</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>

<!-- Modal -->
<div id="myModal4" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel4">Contest Information</h3>
  </div>
  <div class="modal-body">
    <p>Prize field: Spot to enter in what the user will be winning.</p>
    <p>Start Date field: The exact date you would like your contest to begin.</p>
    <p>End Date field: The exact date you would like your contest to end.</p>
    <p>Description field: Please include some detailed information regarding your content and more details on what a user will be winning.</p>
    <p>Disclaimer field: Contest details and legalities.</p>
    <p>Follow up Email field: This email should let a user know moreabout a merchant's offers/page or redirect them to other offers on our contest's page. This email will be sent one week after a contest ends to all contest entries.</p>
    
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>

@stop