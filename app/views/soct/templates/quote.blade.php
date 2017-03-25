<?php
$vehicle_makes = SOE\DB\VehicleMake::where('is_active','=','1')->orderBy('name')->where('edmunds_id','!=','0')->get(array('id', 'name', 'slug'));
?>

<script type="text/ejs" id="template_quote_model">
<% list(models, function(model){ %>
    <option value="<%= model.model_slug %>"><%= model.model_name %></option>
<% }); %>
</script>

<div class="content-bg margin-bottom-20 callout-box callout-box-green sidebar-quote">
    <h1 class="fancy">Get a quote</h1>
    <hr>
    <p>Let us help you find the car of your dreams, in your price range!</p>
    <form>
        <div class="form-group">
            <select class="form-control" id="quoteMake">
                <option value="all">Choose a Make</option>
                <?php foreach ($vehicle_makes as $vehicle_make) { ?>
                    <option value="{{$vehicle_make->slug}}">{{$vehicle_make->name}}</option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <select class="form-control disabled" disabled="disabled" id="quoteModel">
                <option value="all">Choose a Model</option>
            </select>
        </div>
    </form>
    <!--<div class="row">
        <a class="btn-sidebar-quote">
            Get Started <span class="glyphicon glyphicon-chevron-right pull-right"></span>
        </a>
    </div>-->
</div>