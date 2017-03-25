<div>
    <div class="form-group">
        <input name="search-query" class="search-query form-control" placeholder="Search For A Contest" type="text" />
    </div>
    <div class="row">
        <div class="col-xs-3">
            <button id="show_expired" type="button" class="btn btn-primary btn-block" data-toggle="buttons-checkbox">
                Show Expired</button>
            <input type="hidden" name="show_expired" value="0" id="show_expired_value" />
        </div>
        <div class="col-xs-4 col-md-3">
            <div class="dropdown">
                <a class="btn btn-default dropdown-toggle btn-block" data-toggle="dropdown" href="#" id="orderbyparent">
                    Order By: <strong>Expiration Date</strong>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" id="orderby">
                    <li><a href="#" class="order-option" value="merchant_name">Merchant Name</a></li>
                    <li><a href="#" class="order-option" value="contests.name">Contest Name</a></li>
                    <li><a href="#" class="order-option" value="expires_at">Expiration Date</a></li>
                    <li><a href="#" class="order-option" value="applicants">Entry Count</a></li>
                </ul>

                <input type="hidden" id="orderbyname" name="orderby[name]" value="expires_at" />
            </div>
        </div>
        <div class="col-xs-4 col-md-3">
            <div class="btn-group btn-block-group" data-toggle="buttons-radio">
                <button name="orderby[dir]" value="asc" type="button" class="btn btn-primary orderbydir">
                    Ascending</button>
                <button name="orderby[dir]" value="desc" type="button" class="btn btn-primary orderbydir">
                    Descending</button>
            </div>
            <input type="hidden" id="orderbydir" name="orderby[dir]" value="desc" />
        </div>
        <div class="clearfix visible-sm margin-bottom-10"></div>
        <div class="col-xs-3">
            <button type="submit" class="btn btn-default btn-block">Submit</button>
        </div>
    </div>
</li>
