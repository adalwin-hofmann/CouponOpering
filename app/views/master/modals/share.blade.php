  <script type="text/ejs" id="template_facebook_account">
  <% list(accounts, function(account)
  { %>
    <option value="<%= account.access_token+'-'+account.id %>"><%= account.name %></option>
  <% }); %>
  </script>

  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="row">
          <div class="col-xs-12">
            <button type="button" class="close pull-right" data-dismiss="modal" aria-hidden="true">Close &times;</button>
            <span class="h1 modal-title fancy pull-left" id="shareModalLabel">Share Coupon</span>
          </div>
        </div>
        
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xs-4">
            <label>Choose where to share:</label>
          </div>
          <div class="col-xs-8">
            <ul class="nav nav-tabs">
              <li class="active"><a id="emailShareTab" href="#emailShare" data-toggle="tab"><img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/social-icon-email.png"></a></li>
              <li><a id="facebookShareTab" href="#facebookShare" data-toggle="tab"><img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/social-icon-facebook.png"></a></li>
              @if(($twitter_share = Feature::findByName('twitter_share')) && !empty($twitter_share) && $twitter_share->value == 1)
              <li><a href="#twitterShare" data-toggle="tab"><img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/social-icon-twitter.png"></a></li>
              @endif
            </ul>
          </div>
        </div>
        <hr style="margin-top:5px;">
        <div class="tab-content">
        <div class="tab-pane active" id="emailShare">
            <span class="h2 hblock">Share via Email</span>
            <br>
            <div class="row">
              <div class="col-sm-3">
                <img class="img-responsive share-image" src="http://s3.amazonaws.com/saveoneverything_assets/images/1371831508-logo-scrambler-maries.jpg">
                <p class="share-name"><a href="#"><strong>50% OFF</strong></a> Breakfast</p>
              </div>
              <div class="col-xs-6">

                <div class="form-group">
                  <label class="sr-only" for="shareUrl">Coupon URL</label>
                  <input type="text" class="form-control" id="shareUrl" name="shareUrl" value="" placeholder="">
              </div>
            </div>
              <div class="col-sm-9">
                  <div class="row">
                      <div class="col-xs-6">
                        <div class="form-group">
                        <label class="sr-only" for="shareName">Your Name</label>
                        <input type="text" class="form-control" id="shareName" name="shareName" placeholder="{{(Auth::check())?Auth::user()->name:'Your Name'}}" value="{{(Auth::check())?Auth::User()->name:''}}">
                    </div>
                  </div>
                      <div class="col-xs-6">
                        <div class="form-group">
                        <label class="sr-only" for="shareEmail">Your Email</label>
                        <input type="text" class="form-control" id="shareEmail" name="shareEmail" placeholder="{{(Auth::check())?Auth::user()->email:'Your Email'}}" value="{{(Auth::check())?Auth::User()->email:''}}">
                    </div>
                      </div>
                    </div>
                    <div class="form-group">
                    <label class="sr-only" for="shareToEmails">Enter email(s)</label>
                    <input type="text" class="form-control" id="shareToEmails" name="shareToEmails" placeholder="Enter email(s)">
                </div>
                    <div class="form-group">
                    <label class="sr-only" for="shareText">Message</label>
                    <textarea class="form-control email-share-text" rows="5" name="shareText" placeholder="Hey, I thought you might like this coupon I found at SaveOn.com"></textarea>
                </div>
                    
                    <div class="form-group">
                      <div class="checkbox">
                      <label>
                        <input class="share-email-terms" type="checkbox" value="" name="rules">
                        I have read and agree to the <a data-toggle="modal" data-target="#termsModal">terms of use</a>.
                      </label>
                  </div>
                </div>
                <span class="pull-left email-share-message"></span>
                <button type="button" class="btn btn-green pull-right btn-email-share" data-dismiss="modal" data-toggle="modal" data-target= "#shareThanksModal" data-loading-text="Sharing...">Submit</button>
                <div class="clearfix"></div>
              </div>
            </div>
        </div>
        <div class="tab-pane" id="facebookShare">
            <span class="h2 hblock">Share on Facebook</span>
            <br>
            <div class="row">
              <div class="col-sm-3">
                <img class="img-responsive share-image" src="http://s3.amazonaws.com/saveoneverything_assets/images/1371831508-logo-scrambler-maries.jpg">
                <p class="share-name"><a href="#"><strong>50% OFF</strong></a> Breakfast</p>
              </div>
              <div class="col-xs-6">

                <div class="form-group">
                  <label class="sr-only" for="shareUrl">Coupon URL</label>
                  <input type="text" class="form-control" id="fbShareUrl" name="fbShareUrl" value="" placeholder="">
              </div>
            </div>
              <div class="col-sm-9">
                    <div class="form-group">
                        <label class="sr-only" for="shareText">Message</label>
                        <textarea class="form-control facebook-share-text" rows="5" name="shareText" placeholder="Hey, I thought you might like this coupon I found at SaveOn.com"></textarea>
                    </div>
                    
                    <div class="form-group">
                      <div class="checkbox">
                      <label>
                        <input class="share-facebook-terms" type="checkbox" value="" name="rules">
                        I have read and agree to the <a data-toggle="modal" data-target="#termsModal">terms of use</a>.
                      </label>
                      </div>
                    </div>

                    <div id="facebookAccountDiv" class="form-group" style="display:none;">
                        <label for="shareAccount">Select Facebook Account</label>
                        <select id="facebookAccountDropdown" class="form-control">

                        </select>
                    </div>
                <span class="pull-left facebook-share-message"></span>
                <button type="button" style="display:none;" class="btn btn-green pull-right btn-facebook-account" data-dismiss="modal" data-toggle="modal" data-target="#shareThanksModal" data-loading-text="Sharing...">Share On Facebook</button>
                <button type="button" class="btn btn-green pull-right btn-facebook-share" data-dismiss="modal" data-toggle="modal" data-target="#shareThanksModal" data-loading-text="Sharing...">Share On Facebook</button>
                <button type="button" style="display:none;" class="btn btn-link pull-right btn-facebook-choose">Share As...</button>
                <div class="clearfix"></div>
              </div>
            </div>
        </div>
        @if(($twitter_share = Feature::findByName('twitter_share')) && !empty($twitter_share) && $twitter_share->value == 1)
        <div class="tab-pane" id="twitterShare">
          <span class="h2 hblock">Share on Twitter</span>
            <br>
            <div class="row">
              <div class="col-sm-3">
                <img class="img-responsive share-image" src="http://s3.amazonaws.com/saveoneverything_assets/images/1371831508-logo-scrambler-maries.jpg">
                <p class="share-name"><a href="#"><strong>50% OFF</strong></a> Breakfast</p>
              </div>
              <div class="col-sm-9">
                <form role="form">
                  
                    
                    <div class="form-group">
                    <label class="sr-only" for="shareText">Message</label>
                    <textarea class="form-control twitter-share-text" rows="5" name="shareText" placeholder="Hey, I thought you might like this coupon I found at SaveOn.com"></textarea>
                </div>
                    
                    <div class="form-group">
                      <div class="checkbox">
                      <label>
                        <input class="share-twitter-terms" type="checkbox" value="" name="rules">
                        I have read and agree to the <a data-toggle="modal" data-target="#termsModal">terms of use</a>.
                      </label>
                  </div>
                </div>
                <span class="pull-left twitter-share-message"></span>
                <button type="submit" class="btn btn-green pull-right">Share On Twitter</button>
                <div class="clearfix"></div>
                </form>
              </div>
            </div>
        </div>
        @endif

        </div>
      </div>
    </div>
  </div>