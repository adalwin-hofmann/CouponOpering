@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))

@section('page-title')
<h1>Home Improvement: <small>Merchant Review</small></h1>
@stop
@section('body')
<div class="content-bg">
    <div class="row">
        <div class="col-sm-12">
            <h2>Tell us about your experience</h2>
            <p>Your happiness is our number one concern. If your experience with one of our Save Certified merchants was not great, we will remove them from our program.</p>
        </div>
    </div>
    <hr style="margin-left:-20px; margin-right:-20px;">
    <h3 class="red spaced">Merchant Review</h3>
    <br>
    <form action="/homeimprovement/merchantreview" method="POST">
            <div class="row">
                <div class="col-md-6 {{$errors->has('user_name') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Your Name</span>
                    <div class="relative">
                        <input type="text" name="user_name" class="form-control" value="{{Input::old('user_name')}}"/>
                    </div>
                </div>
                <div class="col-md-6 {{$errors->has('business_name') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Name of Home Improvement Company</span>
                    <div class="relative">
                        <input type="text" name="business_name" class="form-control" value="{{Input::old('business_name')}}"/>
                    </div>
                </div>
                
            </div>
            <hr>
            <div class="row">
            	<div class="col-xs-12">
	            	<span class="h3 hblock">How would you rate your experience with your SaveOn Home Improvement contractor?</span>
	                <div class="radio">
					  <label>
					    <input type="radio" name="rating" value="1" {{Input::old('rating') == '1' || !Input::old('rating') ? 'checked' : ''}}>
					    Poor
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="rating" value="2" {{Input::old('rating') == '2' ? 'checked' : ''}}>
					    Fair
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="rating" value="3" {{Input::old('rating') == '3' ? 'checked' : ''}}>
					    Average
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="rating" value="4" {{Input::old('rating') == '4' ? 'checked' : ''}}>
					    Good
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="rating" value="5" {{Input::old('rating') == '5' ? 'checked' : ''}}>
					    Great
					  </label>
					</div>
				</div>
            </div>
            <br>
            <div class="row">
            	<div class="col-xs-12">
	            	<span class="h3 hblock">Was your project completed on time?</span>
	                <div class="radio">
					  <label>
					    <input type="radio" name="completed_on_time" value="yes" {{Input::old('completed_on_time') == 'yes' || !Input::old('completed_on_time') ? 'checked' : ''}}>
					    Yes
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="completed_on_time" value="no" {{Input::old('completed_on_time') == 'no' ? 'checked' : ''}}>
					    No
					  </label>
					</div>
				</div>
            </div>
            <br>
            <div class="row">
            	<div class="col-xs-12">
	            	<span class="h3 hblock">Would you recommend this contractor to a friend?</span>
	                <div class="radio">
					  <label>
					    <input type="radio" name="would_recommend" value="yes" {{Input::old('would_recommend') == 'yes' || !Input::old('would_recommend') ? 'checked' : ''}}>
					    Yes
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="would_recommend" value="no" {{Input::old('would_recommend') == 'no' ? 'checked' : ''}}>
					    No
					  </label>
					</div>
				</div>
            </div>
            <br>
            <div class="row">
            	<div class="col-md-6">
	            	<span class="h3 hblock">Do you have any additional feedback? If so, please enter it in the textbox below. </span>
	                <textarea name="feedback" class="form-control" rows="4">{{Input::old('feedback')}}</textarea>
				</div>
            </div>    
        <div class="row margin-top-20">
            <div class="col-xs-6">
            </div>
            <div class="col-xs-6">
                <button class="btn btn-green pull-right" type="submit">SUBMIT <span class="glyphicon glyphicon-chevron-right"></span></button>
            </div>
        </div>
    </form>
</div>


@stop