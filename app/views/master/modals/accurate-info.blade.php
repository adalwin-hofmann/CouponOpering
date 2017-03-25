  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="accurateInfoModalLabel">Is This Info Accurate?</span>
      </div>
      <div class="modal-body">
        <span class="h2 hblock">Edit the incorrect information</span><br>
        <form role="form">
          <div class="form-group">
            <label for="exampleInputEmail1">Merchant Name</label>
            <input class="form-control" id="exampleInputEmail1" placeholder="Lorem Ipsum Biz">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Address</label>
            <input class="form-control" id="exampleInputPassword1" placeholder="123 Anywhere Lane, Smalltown, USA 48123">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Phone Number</label>
            <input class="form-control" id="exampleInputPassword1" placeholder="800-555-5555">
          </div>
          <hr>
          <span class="h2 hblock">Select from the options below</span>
          <div class="checkbox">
            <label>
              <input type="checkbox"> Location is permanently closed.
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox"> This is a duplicate.
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox"> This location does not exist.
            </label>
          </div>
          <hr>
          <label>Leave a comment about this change</label>
          <textarea class="form-control" rows="3"></textarea>
        </form>
      </div>

      <div class="modal-footer">
        <button data-toggle="modal" data-target="#accurateInfoThanksModal" data-dismiss="modal" type="submit" class="btn btn-black">Submit</button>
      </div>
    </div>
  </div>