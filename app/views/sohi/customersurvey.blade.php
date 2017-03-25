@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))

@section('page-title')
<h1>Home Improvement: <small>Customer Survey</small></h1>
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
    <h3 class="red spaced">Customer Survey</h3>
    <br>
    <form action="/homeimprovement/customersurvey" method="POST">
            <div class="row">
                <div class="col-md-4 form-group {{$errors->has('user_name') ? 'has-error' : ''}}">
                    <span class="h3 control-label">Your Name</span>
                    <div class="relative">
                        <input type="text" name="user_name" class="form-control" value="{{Input::old('user_name')}}"/>
                    </div>
                </div>
                <div class="col-md-4 form-group {{$errors->has('business_name') ? 'has-error' : ''}}">
                    <span class="h3 control-label">Name of Home Improvement Company</span>
                    <div class="relative">
                        <input type="text" name="business_name" class="form-control" value="{{Input::old('business_name')}}"/>
                    </div>
                </div>
                <div class="col-md-4 form-group {{$errors->has('expected_completion') ? 'has-error' : ''}}">
                    <span class="h3 control-label">When do you expect your project to be completed?</span>
                    <select name="expected_completion" class="form-control">
                        <option value="this_week" {{Input::old('expected_completion') == 'this_week' ? 'selected="selected"' : ''}}>This Week</option>
                        <option value="next_week" {{Input::old('expected_completion') == 'next_week' ? 'selected="selected"' : ''}}>Next Week</option>
                        <option value="this_month" {{Input::old('expected_completion') == 'this_month' ? 'selected="selected"' : ''}}>This Month</option>
                        <option value="next_3_months" {{Input::old('expected_completion') == 'next_3_months' ? 'selected="selected"' : ''}}>Next 3 Months</option>
                        <option value="other" {{Input::old('expected_completion') == 'other' ? 'selected="selected"' : ''}}>Other</option>
                    </select>
                </div>
                
            </div>
            <hr>
            <div class="row">
            	<div class="col-xs-12 form-group {{$errors->has('rating') ? 'has-error' : ''}}">
	            	<span class="h3">How would you rate your experience with your SaveOn Home Improvement contractor thus far?</span>
	                <div class="radio">
					  <label>
					    <input type="radio" name="rating" value="1" {{!Input::old('rating') || Input::old('rating') == '1' ? 'checked' : ''}}>
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
            	<div class="col-xs-12 form-group {{$errors->has('work_begun') ? 'has-error' : ''}}">
	            	<span class="h3">Has work begun on your project yet?</span>
	                <div class="radio">
					  <label>
					    <input type="radio" name="work_begun" value="yes" {{!Input::old('work_begun') || Input::old('work_begun') == 'yes' ? 'checked' : ''}}>
					    Yes
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="work_begun" value="no" {{Input::old('work_begun') == 'no' ? 'checked' : ''}}>
					    No
					  </label>
					</div>
				</div>
            </div>
            <br>
            <div class="row">
            	<div class="col-xs-12 form-group {{$errors->has('completion_expected') ? 'has-error' : ''}}">
	            	<span class="h3">If yes, do you feel confident that it will be completed by the given project completion date?</span>
	                <div class="radio">
					  <label>
					    <input type="radio" name="completion_expected" value="yes" {{!Input::old('completion_expected') || Input::old('completion_expected') == 'yes' ? 'checked' : ''}}>
					    Yes
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="completion_expected" value="no" {{Input::old('completion_expected') == 'no' ? 'checked' : ''}}>
					    No
					  </label>
					</div>
				</div>
            </div>
            <br>
            <div class="row">
            	<div class="col-md-6 form-group">
	            	<span class="h3">Do you have any additional feedback? If so, please enter it in the textbox below. </span>
	                <textarea class="form-control" name="feedback" rows="4">{{Input::old('feedback')}}</textarea>
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