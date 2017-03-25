@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))
@section('page-title')
<h1>Redemption Chart</h1>
@stop


@section('body')                      
<div class="content-bg">

	<div class="alert alert-success">
		Thank you! Your request has been received. An email should be arriving shortly with a download link. <br>
		<strong>If you do not receive the email, please check your spam folder.</strong>
	</div>

	<div class="alert alert-danger">
		The email you entered does not match any of the emails we have on file. <br>
		<strong>Please enter the email address of the contract-holder</strong>
	</div>
    <div class="row">
        <div class="col-sm-4">
            <div class="row">
                <div class="col-md-3">
                    <img class="img-responsive hidden-sm hidden-xs" src="http://s3.amazonaws.com/saveoneverything_assets/ebrochures/sohi/img/icons/icon_1.png">
                </div>
                <div class="col-md-9">
                    <h2>Request a new chart</h2>
                    <br>
                    <p>Get started by answering a few questions and entering your email address below. You will be sent an email with a link to download your new chart.</p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="row">
                <div class="col-md-3">
                    <img class="img-responsive hidden-sm hidden-xs" src="http://s3.amazonaws.com/saveoneverything_assets/ebrochures/sohi/img/icons/icon_2.png">
                </div>
                <div class="col-md-9">
                    <h2>Unique Coupon Codes</h2>
                    <br>
                    <p>Every coupon from SaveOn<sup>&reg;</sup> has a unique code on it. Compare the unique code on each coupon with the list of codes on your chart.</p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="row">
                <div class="col-md-3">
                    <img class="img-responsive hidden-sm hidden-xs" src="http://s3.amazonaws.com/saveoneverything_assets/ebrochures/sohi/img/icons/icon_3.png">
                </div>
                <div class="col-md-9">
                    <h2>Prevent Fraud</h2>
                    <br>
                    <p>By checking off coupon codes as they come in, you can tell that it has been redeemed and prevent multiple uses.</p>
                </div>
            </div>
        </div>
    </div>
    <hr style="margin-left:-20px; margin-right:-20px;">
    <div class="row">
        <div class="col-sm-6">
        	<span class="h3">Enter Your E-Mail</strong></span>
		    <input type="text" class="form-control" placeholder="johndoe@email.com">
            <br>
            <span class="h3">How many codes do you want?</span>
            <select style="width:40%; margin-left:-1px;" class="form-control">
                <option>10</option>
                <option>25</option>
                <option>50</option>
            </select>  
            <br>     
            <button class="btn btn-green">SUBMIT</button>     
        </div>
    </div>
</div>


@stop