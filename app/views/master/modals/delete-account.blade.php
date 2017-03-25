  <div class="modal-dialog">
    <div class="text-center modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="deleteAccountModalLabel">Are You Sure?</span>
      </div>
      <div class=" modal-body">
        <span class="h1">Are you sure you want to delete your Save On account?</span><br>
        <p>Your Save On account helps us find the best coupons and contests, customized for you. You can't get the full Save On experience without it.</p>
        
      </div>

      <div class="modal-footer">
        <form action="/members/delete-account" method="POST">
            <div class="row">
              <div class="col-sm-6">
                <button type="button" class="btn btn-block btn-blue" data-dismiss="modal" data-toggle="modal">No, Take Me Back</button>
              </div>
              <div class="col-sm-6">
                <button type="submit" class="btn btn-block btn-default">Yes, Delete My Account</button>
              </div>
            </div>
        </form>
      </div>
    </div>
  </div>