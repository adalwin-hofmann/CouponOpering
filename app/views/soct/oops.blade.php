@extends('master.templates.master')
@section('404error')
<div class="content-bg ">
    <p class="spaced"><strong>Oh Fiddle Sticks, We Have Couldn't Find that Car...</strong></p>
    <p>Did you mean to search for one of these?</p>
    <div class="row">
        <div class="col-sm-4">
            <a href="{{URL::abs('/')}}/groceries" target="_blank">
                <img class="img-responsive" src="http://placehold.it/500x350">
                <h2>2014 Dodge Dart</h2>
            </a>
            <br>
        </div>
        <div class="col-sm-4">
            <a href="{{URL::abs('/')}}/homeimprovement" target="_blank">
                <img class="img-responsive" src="http://placehold.it/500x350">
                <h2>2014 Dodge Dart</h2>
            </a>
            <br>
        </div>
        <div class="col-sm-4">
            <a href="{{URL::abs('/cars')}}" target="_blank">
                <img class="img-responsive" src="http://placehold.it/500x350">
                <h2>2014 Dodge Dart</h2>
            </a>
            <br>
        </div>
    </div>
    <hr>
    <p>Not what you were looking for?</p>
    <hr class="dark">
    <p class="spaced"><strong>Try a different search.</strong></p>

	<div class="row">
		<div class="col-sm-6">
			<form role="form" action="/cars/vehicle-search" method="post">
		        <div class="form-group row">
		            <div class="col-xs-6">
		                <div class="radio margin-top-0">
		                    <label>
		                        <input type="radio" name="carType" id="carType" value="new" checked="checked">
		                        New
		                    </label>
		                </div>
		            </div>
		            <div class="col-xs-6">
		                <div class="radio margin-top-0">
		                    <label>
		                        <input type="radio" name="carType" id="carType" value="used">
		                        Used
		                    </label>
		                </div>
		            </div>
		        </div>
		        <div class="form-group">
		            <select class="form-control" class="form-control" id="filterYear" name="filterYear">
		                <option value="all">All Years</option>
		                
		            </select>
		        </div>
		        <div class="form-group">
		            <select class="form-control" class="form-control" id="filterMake" name="filterMake">
		                <option value="all">All Makes</option>
		                
		                
		            </select>
		        </div>
		        <div class="form-group">
		            <select class="form-control" class="form-control" id="filterModel" name="filterModel">
		                <option value="all">All Models</option>
		                <option value=""></option>
		            </select>
		        </div>
		        <div class="form-group">
		            <label for="filterPrice">Price Range</label>
		            <div class="row">
		                <div class="col-xs-5" style="padding-right:0">
		                    <select class="form-control" class="form-control" id="filterPrice" name="filterPrice">
		                        <option value="high">$1,000</option>
		                    </select>
		                </div>
		                <div class="col-xs-2 text-center">
		                    <p style="margin-top:5px"><strong>to</strong></p>
		                </div>
		                <div class="col-xs-5" style="padding-left:0">
		                    <select class="form-control" class="form-control" id="filterPrice" name="filterPrice">
		                        <option value="high">$1,000,000</option>
		                    </select>
		                </div>
		            </div>
		            
		        </div>
		        <div class="form-group">
		            <select class="form-control" class="form-control" id="filterDistance" name="filterDistance">
		                <option value="high">Within 30 Miles</option>
		            </select>
		        </div>
		        <button type="submit" class="btn btn-red btn-block btn-auto-filter">Search</span></button>
		    </form>
		</div>
	</div>



</div>
@stop
@stop