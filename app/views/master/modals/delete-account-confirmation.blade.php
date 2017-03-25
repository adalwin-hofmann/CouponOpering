<div class="modal-dialog">
    <div class="text-center modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="deleteAccountConfirmationModalLabel">Your Account Has Been Deleted</span>
      </div>
      <div class=" modal-body">
        <span class="h1">We're sad to see you go.</span><br>
        <p>Your Save On account has been deleted, but this doesn't have to be goodbye forever. You can still print coupons for your favorite merchants, even without an account.</p>
        
      </div>

      <div class="modal-footer">
        <div class="row">
          <div class="col-sm-6">
            <button class="btn btn-block btn-default" data-dismiss="modal" data-toggle="modal">Close</button>
          </div>
          <div class="col-sm-6">
            <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all" class="btn btn-block btn-blue" data-toggle="modal">Browse Coupons</a>
          </div>
        </div>
      </div>
    </div>
</div>
