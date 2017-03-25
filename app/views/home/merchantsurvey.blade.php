@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))

@section('page-title')
<h1>SaveOn.com: <small>Customer Survey</small></h1>
@stop
@section('body')
<div class="content-bg">
    <div class="row">
        <div class="col-sm-12">
            <h2>Tell us about your experience</h2>
            <p>Your happiness is our number one concern. Please answer a couple of questions below to help us better understand your needs.</p>
        </div>
    </div>
    <hr style="margin-left:-20px; margin-right:-20px;">
    <h3 class="red spaced">Merchant Survey</h3>
    <br>
    <form action="/homeimprovement/get-certified" method="POST">
        <div class="row">
        	<div class="col-xs-12">
            	<h3>Which of SaveOn's emails have been the most helpful?</h3>
                <div class="radio">
				  <label>
				    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
				    Welcome to SaveOn
				  </label>
				</div>
				<div class="radio">
				  <label>
				    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
				    Check out your new microsite!
				  </label>
				</div>
				<div class="radio">
				  <label>
				    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
				    Make the most out of your microsite
				  </label>
				</div>
				<div class="radio">
				  <label>
				    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
				    Utilizing contests
				  </label>
				</div>
				<div class="radio">
				  <label>
				    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
				    Your mobile Microsite
				  </label>
				</div>
			</div>
        </div>
        <br>
        <div class="row">
        	<div class="col-xs-12">
            	<h3>What SaveOn<sup>&reg;</sup> Feature would you like to learn more about?</h3>
                <div class="radio">
				  <label>
				    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
				    Mobile Redemption
				  </label>
				</div>
				<div class="radio">
				  <label>
				    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
				    Coupons
				  </label>
				</div>
				<div class="radio">
				  <label>
				    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
				    Contests
				  </label>
				</div>
				<div class="radio">
				  <label>
				    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
				    Other
				  </label>
				</div>

			</div>
        </div> 
        <div class="row">
        	<div class="col-xs-12">
		        <hr> 
		        <button class="btn btn-green pull-right">Submit!</button>
		    </div>
		</div>
    </form>
</div>


@stop