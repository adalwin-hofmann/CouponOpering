@extends('master.templates.master')
@section('page-title')
<h1>Claim Contest Prize</h1>
@stop

@section('body')
<div class="content-bg">
    <div class="row">
        <div class="col-md-12">
            Print this and send it to the winner.
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
                <h1 class="fancy">You've Won!</h1>
            </div>
            @if($merchant)
            <div class="col-xs-4">
                <img src="{{$logo->path}}" alt="{{ $merchant->display }}" class="img-responsive">
            </div>
            @endif
        </div>

            
       

        <!--<table class="margin-bottom-20">
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
        </table>-->

        <p>Dear {{$winner->first_name.' '.$winner->last_name }},</p>

        <p>Congratulations! It is my pleasure to announce that you are the winner of the {{$date->prize_name}}, hosted by SaveOn.</p>

        <p>Enclosed is your prize redeemable at {{ $date->redeemable_at }}. SaveOn appreciates your participation in the contest.


        <p>Don't forget to visit SaveOn.com to find hundreds of FREE coupons to use on all your favorite businesses. It would be greatly appreciated if you could share on Facebook and Twitter that you were selected as a winner!</p>

        <p>Again, congratulations and thank you!</p>

        Sincerely,<br>
        SaveOn<br>
        1000 W. Maple, Suite 200<br>
        Troy, MI 48084<br>
        Direct Line: 248-244-2158<br>
        SaveOn.com<br>

            <div class="row">
                <div class="col-xs-6">
                    <img class="img-responsive" width="166" height="59" alt="Save On" src="/img/logo.png">
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