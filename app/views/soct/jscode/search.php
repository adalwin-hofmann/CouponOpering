<script>
    VehicleModel = can.Model({
        findAll: 'GET /api/v2/vehicle-model/get-by-make'
    },{});
    VehicleYear = can.Model({
        findAll: 'GET /api/v2/vehicle-year/get-new-by-make'
    },{});
    FeaturedStyle = can.Model({
        findOne: 'GET /api/v2/vehicle-style/get-by-make-model'
    },{});

    SearchVehicleControl = can.Control(
    {
        init: function()
        {
            if($('.radioCarType:checked').val() == 'new')
            {
                var years = [];
                for(var i=new_year_end; i>=new_year_start; i--)
                {
                    years.push(i);
                }
                $('#filterYear').html('<option value="all">All Years</option>');
                $('#filterYear').append(can.view('template_search_year',
                {
                    years: years
                }));
                $('#filterDistance').hide();
                $('#filterBodyType').show();
            }
        },
        //Events
        '.radioCarType change': function(element)
        {
            if($('.radioCarType:checked').val() == 'used')
            {
                var years = [];
                for(var i=new_year_end; i>=earliest_year; i--)
                {
                    years.push(i);
                }
                $('#filterYear').html('<option value="all">All Years</option>');
                $('#filterYear').append(can.view('template_search_year',
                {
                    years: years
                }));
                $('#filterDistance').show();
                //$('#filterBodyType').hide();
            }
            else
            {
                if($('#filterYear').val() < new_year_start)
                    $('#filterYear').val('all');
                var years = [];
                for(var i=new_year_end; i>=new_year_start; i--)
                {
                    years.push(i);
                }
                $('#filterYear').html('<option value="all">All Years</option>');
                $('#filterYear').append(can.view('template_search_year',
                {
                    years: years
                }));
                $('#filterDistance').hide();
                $('#filterBodyType').show();
            }
        },
        '.radioCarTypeMobile change': function(element)
        {
            if($('.radioCarTypeMobile:checked').val() == 'used')
            {
                var years = [];
                for(var i=new_year_end; i>=earliest_year; i--)
                {
                    years.push(i);
                }
                $('#filterYearMobile').html('<option value="all">All Years</option>');
                $('#filterYearMobile').append(can.view('template_search_year',
                {
                    years: years
                }));
                $('#filterDistanceMobile').show();
                //$('#filterBodyType').hide();
            }
            else
            {
                if($('#filterYearMobile').val() < new_year_start)
                    $('#filterYearMobile').val('all');
                var years = [];
                for(var i=new_year_end; i>=new_year_start; i--)
                {
                    years.push(i);
                }
                $('#filterYearMobile').html('<option value="all">All Years</option>');
                $('#filterYearMobile').append(can.view('template_search_year',
                {
                    years: years
                }));
                $('#filterDistanceMobile').hide();
                $('#filterBodyTypeMobile').show();
            }
        },
        '#filterMake change': function(element)
        {
            var self = this;
            if(element.val() == 'all')
            {
                $('#filterModel').html('<option value="all">All Models</option>');
                $('#filterModel').prop('disabled', true);
                $('#filterModel').addClass('disabled');
            }
            else
            {
                VehicleModel.findAll({make: element.val()}, function(models)
                {
                    $('#filterModel').prop('disabled', false);
                    $('#filterModel').removeClass('disabled');
                    self.BindModels(models);
                });
            }
        },
        '#filterMakeMobile change': function(element)
        {
            var self = this;
            if(element.val() == 'all')
            {
                $('#filterModelMobile').html('<option value="all">All Models</option>');
                $('#filterModelMobile').prop('disabled', true);
                $('#filterModelMobile').addClass('disabled');
            }
            else
            {
                VehicleModel.findAll({make: element.val()}, function(models)
                {
                    $('#filterModelMobile').prop('disabled', false);
                    $('#filterModelMobile').removeClass('disabled');
                    self.BindModelsMobile(models);
                });
            }
        },
        // Methods
        'BindModels': function(models)
        {
            $('#filterModel').html('<option value="all">All Models</option>');
            $('#filterModel').append(can.view('template_model',
            {
                models: models
            }));
        },
        'BindModelsMobile': function(models)
        {
            $('#filterModelMobile').html('<option value="all">All Models</option>');
            $('#filterModelMobile').append(can.view('template_model',
            {
                models: models
            }));
        }
    });
    QuoteControl = can.Control(
    {
        init: function()
        {
        },
        //Events
        '#quoteMake change': function(element)
        {
            var self = this;
            if(element.val() == 'all')
            {
                $('#quoteModel').html('<option value="all">Choose a Model</option>');
                $('#quoteModel').prop('disabled', true);
                $('#quoteModel').addClass('disabled');
            }
            else
            {
                VehicleYear.findAll({make: element.val()}, function(models)
                {
                    $('#quoteModel').prop('disabled', false);
                    $('#quoteModel').removeClass('disabled');
                    self.BindQuoteModels(models);
                });
            }
        },
        '#quoteModel change': function(element)
        {
            this.LaunchNewQuoteModalSidebar();
        },
        '.btn-sidebar-quote click': function(element)
        {
            this.LaunchNewQuoteModalSidebar();
        },
        //Methods
        'LaunchNewQuoteModalSidebar': function()
        {
            var self = this;
            var makeSlug = $('#quoteMake').val();
            var modelSlug = $('#quoteModel').val();
            $('#quoteMake').parent().removeClass('has-error');
            $('#quoteModel').parent().removeClass('has-error');
            if (makeSlug == 'all')
            {
                $('#quoteMake').parent().addClass('has-error');
                return;
            }
            if (modelSlug == 'all')
            {
                $('#quoteModel').parent().addClass('has-error');
                return;
            }
            FeaturedStyle.findOne({make: makeSlug, model: modelSlug}, function(style)
            {                
                var style = style;
                var style_id = style.id;
                master_control.LaunchNewQuoteModal(style_id);
            });
        },
        'BindQuoteModels': function(models)
        {
            $('#quoteModel').html('<option value="all">Choose a Model</option>');
            $('#quoteModel').append(can.view('template_quote_model',
            {
                models: models
            }));
        }
    });

    search_vehicle_control = new SearchVehicleControl('body');
    quote_control = new QuoteControl('.sidebar-quote');
</script>