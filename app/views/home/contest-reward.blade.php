@extends('master.templates.master')
@section('page-title')
<h1>Claim Contest Prize</h1>
@stop

@section('body')
<div class="content-bg">
    <div class="row">
        <div class="col-md-12">
            Print this gift certificate or redeem it on a mobile device.  We've emailed you a link to this page so you can get back to it in the future.
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-xs-12 redemption-message text-center {{ $winner->redeemed_at == NULL ? '' : 'alert alert-danger' }}">
            {{ $winner->redeemed_at == NULL ? '' : 'Already Redeemed' }}
        </div>
    </div>
    <div class="prize-printable {{ $winner->redeemed_at == NULL ? '' : 'already-redeemed' }}">
        <div class="row">
            <div class="col-xs-8">
                <h1 class="fancy">Gift Certificate</h1>
            </div>
            @if($merchant)
            <div class="col-xs-4">
                <img src="{{$logo->path}}" alt="{{ $merchant->display }}" class="img-responsive">
            </div>
            @endif
        </div>

            
       

        <table class="margin-bottom-20">
            <tr>
                <th>Prize</th>
                <td><strong>{{$date->prize_name}}</strong></td>
            </tr>
            <tr>
                <th>Presented To</th>
                <td><strong>{{ $winner->first_name.' '.$winner->last_name }}</strong></td>
            </tr><tr>
                <th>Redeemable At</th>
                <td>{{ $date->redeemable_at }}</td>
            </tr><tr>
                <th>Presented By</th>
                <td>SaveOn<sup>&reg;</sup></td>
            </tr><tr>
                <th>Valid Through</th>
                <td>{{ date('m/d/Y', strtotime($date->prize_expiration_date)) }}</td>
            </tr><tr>
                <th>Certificate Code:</th>
                <td>{{ $winner->verify_key }}</td>
            </tr>
        </table>

        <p>{{ $date->prize_description }}<br>
            <!--<small class="valid-through text-right">Valid Through {{ date('m/d/Y', strtotime($date->prize_expiration_date)) }}</small>--></p>

        <!--<p class="text-center"><strong>Presented To:</strong> {{ $winner->first_name.' '.$winner->last_name }}<br>
            <strong>Presented By:</strong> SaveOn</p>

        <p>Valid Through {{ date('m/d/Y', strtotime($date->prize_expiration_date)) }}<br>
            Certificate Code: {{ $winner->verify_key }}</p>-->

        


            <div class="row">
                <div class="col-xs-6">
                    <img class="img-responsive" width="166" height="59" alt="Save On" src="/img/logo.png">
                </div>
                <div class="col-xs-6">
                    <p class="text-right">
                        <strong>Authorized By:</strong> {{ $date->prize_authorizer }}<br>
                        <strong>Title:</strong> {{ $date->prize_authorizer_title }}
                    </p>
                </div>
            </div>
            <div class="text-center margin-top-5 text-muted"><small>No cash value. No change will be given for unused portion of gift certificate.</small></div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12 hidden-sm hidden-xs">
            <p class="text-center">
                <button class="btn btn-primary btn-prize-print">Print</button>
            </p>
        </div>
        <div class="col-md-12 hidden-md hidden-lg">
            <p class="text-center">
                <button class="btn btn-primary btn-prize-redeem {{ $winner->redeemed_at == NULL ? '' : 'disabled' }}" data-winner_id="{{ $winner->id }}">Redeem</button>
            </p>
        </div>
    </div>
</div>

<div id="confirmModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Are You Sure?</h4>
      </div>
      <div class="modal-body">
        <p>You can only redeem this once, are you sure?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-prize-redeem-confirm">Redeem Now</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@stop