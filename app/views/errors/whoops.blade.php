@extends('master.templates.master')
@section('404error')
    	<div class="container error-page">
    		<h1 class="fancy text-center">Whoops! An error occurred.</h1>
	    	<div class="content-bg">
				<p class="spaced"><strong>Sorry, something went wrong</strong></p>
				<p>Sorry about that! We aren't sure what happened but we are looking into it now. In the meantime though, you can do a search below to find great offers in your area, or <a href="{{URL::abs('/')}}">click here</a> to go back home.</p>
				<hr class="dark">
				<p class="spaced"><strong>Search For Merchants</strong></p>
				<div>
					<form role="form">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<div class="input-group searchbar">
								      	<input type="text" class="form-control inptSearch" placeholder="Search by Keyword">
										<div class="input-group-btn search-type" style="display:{{(Feature::findByName('entity_search')->value == 0)?'none':''}}">
											  	<button class="btn dropdown-toggle btn-default" type="button" id="searchDrop" data-toggle="dropdown" data-value="<?php if(isset($searchType)) { echo $searchType; } else { echo 'merchant'; }?>">
											    	<?php if(isset($searchType)) { echo ucfirst($searchType); } else { echo 'Merchant'; }?>
											  	</button>
												<ul class="dropdown-menu search-dropdown-menu pull-right" role="menu" aria-labelledby="searchDrop">
													<li><a data-value="merchant">Merchant</a></li>
													<li><a data-value="offer">Offer</a></li>
												</ul>
										</div>
									    <div class="input-group-btn">
									        <button class="btn btn-green search" type="button">Go</button>
									    </div>
								    </div>
								</div>
							</div>
							<div class="col-sm-6">
								<a class="btn btn-green" href="{{URL::abs('/')}}">GO HOME</a>
							</div>
						</div>
						
					</form>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
@stop
@stop