<script>
Merchant = can.Model({
    findOne: 'GET /wizard-merchant',
    findAll: 'GET /wizard-search',
    create: 'POST /wizard-update-merchant/{franchise_id}'
},{});

Subcategory = can.Model({
    findAll: 'GET /api/category/get-by-parent-id?category_id={category_id}'
},{});

Media = can.Model({
    findAll: 'GET /media/{merchant_id}'
},{});

FranchiseTag = can.Model({
    findAll: 'GET /api/v2/project-tag/get-franchise-tags?franchise_id={franchise_id}',
    create: 'POST /wizard-tags/{franchise_id}'
},{});

NewDealerBrand = can.Model({
    create: 'POST /wizard-brands/{merchant_id}'
},{});

Note = can.Model({
    findAll: 'GET /api/v2/franchise/get-notes',
    create: 'GET /api/v2/note/update?id={note_id}'
},{});

NewNote = can.Model({
    create: 'GET /api/v2/note/create'
},{});

DeleteNote = can.Model({
    create: 'GET /api/v2/note/delete?id={note_id}'
},{});

DealerOrder = can.Model({
    findOne: 'GET /api/v2/dealer-order/delete',
    findAll: 'GET /api/v2/dealer-order/get-by-franchise',
    create: 'GET /api/v2/dealer-order/create'
},{});

LeadsConfirm = can.Model({
    create: 'POST /wizard-leads-confirmed'
},{});

Reactivation = can.Model({
    create: 'POST /wizard-reactivation-notice'
},{});

BlockYipit = can.Model({
    findOne: 'GET /wizard-get-yipit-status',
    create: 'POST /wizard-block-yipit'
},{});

UnblockYipit = can.Model({
    create: 'POST /wizard-unblock-yipit'
},{});

$( "#trialStart" ).datepicker();
$( "#trialEnd" ).datepicker();
$('#orderStartsAt').datepicker();
$('#orderEndsAt').datepicker();
$('#contract_start').datepicker();
$('#contract_end').datepicker();

MerchantControl = can.Control({
    init: function()
    {
        $('#findExisting').tooltip({'title': 'Search For Merchants', 'placement': 'top'});
        $('#btnUpload').tooltip({'title': 'Upload Selected File', 'placement': 'right'});
        CKEDITOR.replace('catchphrase', {height:"155"});
        if(selectedFranchise != '0')
        {
            $('#addNew').prop('disabled', false);
            $('#addNew').tooltip({'title': 'Create New Merchant', 'placement': 'top'});
            this.Search();
        }
        else
        {
            $('#logoUpload').show();
        }
    },
    //Events
    '#dealerReactivatedNotify click': function(element)
    {
        element.button('loading');
        var myReactive = new Reactivation({franchise_id: selectedFranchise});
        myReactive.save(function(){
            element.button('reset');
        });

    },
    '#testLeadsCheck change': function(element)
    {
        var myConfirm = new LeadsConfirm({confirmed: element.prop('checked') ? 1 : 0, franchise_id: selectedFranchise});
        myConfirm.save();
    },
    '#addNew click': function(element)
    {
        window.location = "/wizard";
    },
    '#myModal shown.bs.modal': function()
    {
        $('#modQuery').focus();
    },
    '#step1Done click': function(element)
    {
        element.button('loading');
        this.Validate();
    },
    '#stepNotesDone click': function(element)
    {
        $('#stepNotes').fadeOut(400, function()
        {
            $('#step2').fadeIn(400);
        });
    },
    '#stepNotesBack click': function(element)
    {
        $('#stepNotes').fadeOut(400, function()
        {
            $('#step1').fadeIn(400);
        });
    },
    '.btn-add-note click': function()
    {
        var allow = true;
        $('#notesArea textarea').each(function(i)
        {
            if($(this).val() == '')
                allow = false;
        });
        if(!allow)
            return;
        var now = new Date();
        $('#notesArea').append(can.view('template_note',
        {
            notes: [{title: '', content: '', updated_at: this.GetTimeStamp(now.getDate()+'/'+(Number(now.getMonth())+1)+'/'+now.getFullYear()), id: 0}]
        }));
    },
    '.btn-save-note click': function(element)
    {
        var self = this;
        var title = element.parent().parent().find('.note-title').val();
        var content = element.parent().parent().find('.note-content').val();
        if(title == '' || content == '')
            return;
        if(element.data('note_id') == 0)
        {
            var myNote = new NewNote({title: title, content: content, notable_type: 'SOE\\DB\\Franchise', notable_id: selectedFranchise});
        }
        else
        {
            var myNote = new Note({note_id: element.data('note_id'), title: title, content: content});
        }
        myNote.save(function(json)
        {
            element.parent().parent().find('.note-message').hide();
            element.parent().parent().find('.note-message').html('Note Saved!');
            element.parent().parent().find('.note-message').fadeIn(400, function()
            {
                element.parent().parent().find('.note-message').fadeOut(10000);
            });
            element.data('note_id', json.id);
            element.parent().find('.btn-delete-note').data('note_id', json.id);
            element.parent().parent().find('.updated-text').html(self.GetDate(json.updated_at));
        });
    },
    '.btn-delete-note click': function(element)
    {
        if(element.data('note_id') == 0)
        {
            element.parent().parent().parent().remove();
        }
        else
        {
            var myDeleteNote = new DeleteNote({note_id: element.data('note_id')});
            myDeleteNote.save(function(json)
            {
                element.parent().parent().parent().remove();
            });
        }
    },
    '#step2Done click': function(element)
    {
        $('#step2').fadeOut(400, function()
        {
            $('#step3').fadeIn(400);
        });
    },
    '#step2Back click': function(element)
    {
        $('#step2').fadeOut(400, function()
        {
            $('#stepNotes').fadeIn(400);
        });
    },
    '#step3Done click': function(element)
    {
        element.button('loading');
        var MerchObject = new Object();
        MerchObject.franchise_id = selectedFranchise;
        MerchObject.merchant_id = selectedMerchant;
        MerchObject.catchphrase = CKEDITOR.instances.catchphrase.getData();
        MerchObject.facebook = $('#facebook').val();
        MerchObject.twitter = $('#twitter').val();
        MerchObject.mobile_redemption = $('#redemptions').val();
        MerchObject.service_radius = $('#service_radius').val() * 1607;
        MerchObject.entity_search_parse = $('#entity_search_parse').val();
        MerchObject.page_version = $('#page_version').val();
        MerchObject.coupon_tab_type = $('#coupon_tab_type').val();
        MerchObject.is_offer_notifications = $('#is_offer_notifications').val();
        MerchObject.sponsor_level = $('#sponsor_level').val();
        var districts = [];
        $('#sponsor_districts :selected').each(function(i)
        {
            districts[i] = $(this)[0].value;
        });
        MerchObject.districts = districts;
        var myMerchant = new Merchant(MerchObject);
        myMerchant.save(function(json)
        {
            if($('#parentcategory_id').val() == 231)
            {
                FranchiseTag.findAll({franchise_id: selectedFranchise}, function(tags)
                {
                    $('#step4 input:checkbox').prop('checked', false);
                    for(var i=0; i<tags.length; i++)
                    {
                        $('#step4 input:checkbox[value="'+tags[i].id+'"]').prop('checked', true);
                    };
                    $("#step3").fadeOut(400, function()
                    {
                        element.button('reset');
                        $("#step4").fadeIn(400);
                    });
                });
            }
            else if($('#category_id').val() == 120)
            {
                DealerBrand.findAll({merchant_id: selectedMerchant}, function(brands)
                {
                    $('#dealer-step4 input:checkbox').prop('checked', false);
                    for(var i=0; i<brands.length; i++)
                    {
                        $('#dealer-step4 input:checkbox[value="'+brands[i].make_id+'"]').prop('checked', true);
                    };
                    $("#step3").fadeOut(400, function()
                    {
                        element.button('reset');
                        $("#dealer-step4").fadeIn(400);
                    });
                });
            }
            else
            {
                window.location = "/location?viewing="+selectedFranchise;
            }
        });
    },
    '#step3Back click': function(element)
    {
        $('#step3').fadeOut(400, function()
        {
            $('#step2').fadeIn(400);
        });
    },
    '#step4Done click': function(element)
    {
        element.button('loading');
        var checked = '';
        $('#step4 input:checked').each(function()
        {
            checked += $(this).val()+',';
        });
        var myTag = new FranchiseTag({franchise_id: selectedFranchise, tags: checked})
        myTag.save(function(json)
        {
            element.button('reset');
            window.location = "/location?viewing="+selectedFranchise;
        });
    },
    '#step4Back click': function(element)
    {
        $('#step4').fadeOut(400, function()
        {
            $('#step3').fadeIn(400);
        });
    },
    '#dealer-step4Back click': function(element)
    {
        $('#dealer-step4').fadeOut(400, function()
        {
            $('#step3').fadeIn(400);
        });
    },
    '#dealer-step4Done click': function(element)
    {
        element.button('loading');
        var checked = '';
        $('#dealer-step4 input:checked').each(function()
        {
            checked += $(this).val()+',';
        });
        var myBrand = new NewDealerBrand({merchant_id: selectedMerchant, brands: checked, franchise_id: selectedFranchise})
        myBrand.save(function(json)
        {
            element.button('reset');
            if($('#category_id').val() == 120)
            {
                DealerOrder.findAll({franchise_id: selectedFranchise}, function(orders)
                {
                    $('#existingOrdersArea').html(can.view('template_dealer_order', {
                        orders: orders
                    }));
                    $("#dealer-step4").fadeOut(400, function()
                    {
                        element.button('reset');
                        $("#dealer-step5").fadeIn(400);
                    });
                });
            }
            else
            {
                window.location = "/location?viewing="+selectedFranchise;
            }
        });
    },
    '#dealer-step5Back click': function(element)
    {
        element.button('loading');
        DealerBrand.findAll({merchant_id: selectedMerchant}, function(brands)
        {
            $('#dealer-step4 input:checkbox').prop('checked', false);
            for(var i=0; i<brands.length; i++)
            {
                $('#dealer-step4 input:checkbox[value="'+brands[i].make_id+'"]').prop('checked', true);
            };
            $("#dealer-step5").fadeOut(400, function()
            {
                element.button('reset');
                $("#dealer-step4").fadeIn(400);
            });
        });
    },
    '#dealer-step5Done click': function(element)
    {
        window.location = "/location?viewing="+selectedFranchise;
    },
    '#orderSaveNew click': function(element)
    {
        element.button('loading');
        var self = this;
        if(
            $('#orderMake').val() != '' &&
            $('#orderZip').val() != '' &&
            $('#orderRadius').val() != '' &&
            $('#orderBudget').val() != '' &&
            $('#orderStartsAt').val() != '' &&
            $('#orderEndsAt').val() != ''
        )
        {
            var myOrder = new DealerOrder({
                franchise_id: selectedFranchise,
                make_id: $('#orderMake').val(),
                zipcode: $('#orderZip').val(),
                radius: $('#orderRadius').val() * 1607,
                budget: $('#orderBudget').val(),
                starts_at: this.GetTimeStamp($('#orderStartsAt').val()),
                ends_at: this.GetTimeStamp($('#orderEndsAt').val())
            });

            myOrder.save(function(order)
            {
                DealerOrder.findAll({franchise_id: selectedFranchise}, function(orders)
                {
                    self.BindOrders(orders);
                })
                element.button('reset');
                $('#orderMake').val('');
                $('#orderZip').val('');
                $('#orderRadius').val('');
                $('#orderBudget').val('');
                $('#orderStartsAt').val('');
                $('#orderEndsAt').val('');
            });
        }
        else
        {
            element.button('reset');
        }
    },
    '.btn-order-save click': function(element)
    {
        var parent = element.parent().parent().parent();
        if(
            parent.find('.orderMake').val() != '' &&
            parent.find('.orderZip').val() != '' &&
            parent.find('.orderRadius').val() != '' &&
            parent.find('.orderStartsAt').val() != '' &&
            parent.find('.orderEndsAt').val() != ''
        )
        {
            var myOrder = new DealerOrder({
                order_id: element.data('order_id'),
                make_id: parent.find('.orderMake').val(),
                zipcode: parent.find('.orderZip').val(),
                radius: parent.find('.orderRadius').val() * 1607,
                budget: parent.find('.orderBudget').val(),
                starts_at: this.GetTimeStamp(parent.find('.orderStartsAt').val()),
                ends_at: this.GetTimeStamp(parent.find('.orderEndsAt').val())
            });

            myOrder.save(function(order)
            {
                element.button('reset');
            });
        }
        else
            element.button('reset');
    },
    '.btn-order-delete click': function(element)
    {
        var self = this;
        DealerOrder.findOne({order_id: element.data('order_id')}, function(order)
        {
            DealerOrder.findAll({franchise_id: selectedFranchise}, function(orders)
            {
                self.BindOrders(orders);
            })
        });
    },
    '#parentcategory_id change': function(element)
    {
        if($('#merchant_type').val() == 'PPL')
        {
            if($('#parentcategory_id :selected').html() == 'Home Improvement')
            {
                $('#pplCertifiedDiv').show();
            }
            else
            {
                $('#pplCertifiedDiv').hide();
                $('input[name=is_certified]:radio').prop('checked', false);
                this.PPLClear();
            }

            if($('#parentcategory_id :selected').html() == 'Auto &amp; Transportation' && $('#category_id :selected').html() == 'Auto Dealers')
            {
                $('#pplLeadAllowancesDiv').show();
                $('#pplDivider').show();
                $('#pplArea').show();
                $('#divFeaturedDealer').show();
            }
            else
            {
                $('#divFeaturedDealer').hide();
                $('#featuredDealer').prop('checked', false);
                $('#pplLeadAllowancesDiv').hide();
                $('#allowGeneric').prop('checked', true);
                $('#allowDirected').prop('checked', true);
                this.PPLClear();
            }
        }
        Subcategory.findAll({category_id: $('#parentcategory_id').val(), limit: 100}, function(subcategories)
        {
            $('#category_id').html('<option value="">----</option>');
            $('#category_id').append(can.view('template_subcategory',
            {
                subcategories: subcategories
            }));
        });
    },
    '#category_id change': function()
    {
        if($('#parentcategory_id :selected').html() == 'Auto &amp; Transportation' && $('#category_id :selected').html() == 'Auto Dealers')
        {
            $('#pplLeadAllowancesDiv').show();
            $('#pplDivider').show();
            $('#pplArea').show();
            $('#divFeaturedDealer').show();
        }
        else
        {
            $('#pplLeadAllowancesDiv').hide();
            $('#allowGeneric').prop('checked', true);
            $('#allowDirected').prop('checked', true);
            $('#divFeaturedDealer').hide();
            $('#featuredDealer').prop('checked', false);
            this.PPLClear();
        }
    },
    '#ieDoneButton click': function()
    {
        this.Search();
    },
    '#ieSponsorBannerDoneButton click': function()
    {
        this.Search();
    },
    '#allowGeneric change': function(element)
    {
        if(!element.prop('checked') && !$('#allowDirected').prop('checked'))
        {
            $('#pplDivider').hide();
            $('#pplArea').hide();
        }
        else
        {
            $('#pplDivider').show();
            $('#pplArea').show();
        }
    },
    '#allowDirected change': function(element)
    {
        if(!element.prop('checked') && !$('#allowGeneric').prop('checked'))
        {
            $('#pplDivider').hide();
            $('#pplArea').hide();
        }
        else
        {
            $('#pplDivider').show();
            $('#pplArea').show();
        }
    },
    '#merchant_type change': function(element)
    {
        if(element.val() == 'PROSPECT')
        {
            $('#divNational').show();
        }
        else
        {
            $('#divNational').hide();
            $('#chkNational').prop('checked', false);
        }

        if(element.val() == 'PPL' && $('#parentcategory_id :selected').html() == 'Home Improvement')
        {
            $('#pplCertifiedDiv').show();
        }
        else
        {
            $('#pplCertifiedDiv').hide();
            $('input[name=is_certified]:radio').prop('checked', false);
            this.PPLClear();
        }

        if(element.val() == 'PPL' && $('#parentcategory_id :selected').html() == 'Auto &amp; Transportation' && $('#category_id :selected').html() == 'Auto Dealers')
        {
            $('#pplLeadAllowancesDiv').show();
            $('#pplDivider').show();
            $('#pplArea').show();
        }
        else
        {
            $('#pplLeadAllowancesDiv').hide();
            $('#allowGeneric').prop('checked', true);
            $('#allowDirected').prop('checked', true);
            this.PPLClear();
        }
    },
    'input[name=is_certified]:radio change': function(element)
    {
        if($('#is_certified_yes').prop('checked'))
        {
            $('#pplLeadAllowancesDiv').show();
            $('#pplDivider').show();
            $('#pplArea').show();
        }
        else
        {
            $('#pplLeadAllowancesDiv').hide();
            this.PPLClear();
        }
    },
    '.btn-add-email click': function()
    {
        var allow = true;
        $('.lead-email').each(function()
        {
            if($(this).find('input').val() == '')
            {
                allow = false;
            }
        });
        if(allow)
        {
            $('#leadEmailArea').append(can.view('template_blank_lead_email',{}));
        }
    },
    '.btn-delete-email click': function(element)
    {
        var emails = $('.lead-email').length;
        if(emails > 1)
        {
            element.parent().parent().parent().parent().remove();
        }
    },
    'input[name=service_plan] change': function(element)
    {
        if(element.val() == 'trial')
        {
            $('#trialDiv').show();
            $('#trialStart').val('');
            $('#trialEnd').val('');
            $('#trialLeadCap').val(5);
        }
        else
        {
            $('#trialDiv').hide();
        }
    },
    '#sponsor_level change': function(element)
    {
        if(element.val() == '')
        {
            $('#sponsor_districts').parent().parent().hide();
            $('#sponsor_districts option').prop('selected', false);
            $('#sponsorBannerRow').hide();
        }
        else
        {
            $('#sponsor_districts').parent().parent().show();
            $('#sponsorBannerRow').show();
        }
    },
    '#btnBlockYipits click': function(element)
    {
        if(selectedMerchant == 0)
            return;

        var newBlockYipit = new BlockYipit({merchant_id: selectedMerchant});
        newBlockYipit.save(function()
        {
            $('#btnUnblockYipits').show();
            $('#btnBlockYipits').hide();
        });

    },
    '#btnUnblockYipits click': function(element)
    {
        if(selectedMerchant == 0)
            return;

        var newUnblockYipit = new UnblockYipit({merchant_id: selectedMerchant});
        newUnblockYipit.save(function()
        {
            $('#btnUnblockYipits').hide();
            $('#btnBlockYipits').show();
        });
    },
    //Methods
    'BindOrders': function(orders)
    {
        $('#existingOrdersArea').html(can.view('template_dealer_order', {
            orders: orders
        }));
        $('#existingOrdersArea .orderStartsAt').datepicker();
        $('#existingOrdersArea .orderEndsAt').datepicker();
    },
    'PPLClear': function()
    {
        $('#pplDivider').hide();
        $('#pplArea').hide();
        $('.pplTextInputs input').val('');
        $('#leadEmailArea').html(can.view('template_blank_lead_email'));
    },
    'Search': function()
    {
        var self = this;
        Merchant.findOne({franchise_id: selectedFranchise}, function(json)
        {
            self.BindOne(json);
        });
    },
    'SearchMerchant': function()
    {
        var self = this;
        Merchant.findOne({merchant_id: selectedMerchant}, function(json)
        {
            self.BindOneMerchant(json);
        });
    },
    'BindOne': function(data)
    {
        var self = this;
        if(data.yipitbusiness_id != 0)
        {
            BlockYipit.findOne({merchant_id: data.id}, function(json)
            {
                if(json.data == true)
                {
                    $('#btnUnblockYipits').show();
                } else {
                    $('#btnBlockYipits').show();
                }
            });
        } else {
            $('#btnBlockYipits').hide();
        }
        $('#sales').html('<option value="">----</option>');
        $('#sales').append(can.view('template_assignment',
        {
           users: data.sales_users.objects
        }));

        $('#sales').val(data.assigned_sales_users.objects.length == 0 ? '' : data.assigned_sales_users.objects[0].attributes.id);
        $('#parentcategory_id').val(data.category_id);
        var sub_loaded = false;
        Subcategory.findAll({category_id: data.category_id, limit: 100}, function(json)
        {
            var element = $('#category_id');
            element.html(can.view('template_subcategory',
            {
                subcategories: json
            }));
            element.val(data.subcategory_id);
            if($('#category_id :selected').html() == 'Auto Dealers')
            {
                $('#pplLeadAllowancesDiv').show();
                $('#divFeaturedDealer').show();
                if(data.franchise && (data.franchise.allow_generic_leads || data.franchise.allow_directed_leads))
                {
                    $('#pplDivider').show();
                    $('#pplArea').show();
                }
                else
                {
                    $('#pplDivider').hide();
                    $('#pplArea').hide();
                }
            }
            else 
            {
                $('#divFeaturedDealer').hide();
                if(data.franchise && !data.franchise.is_certified)
                {
                    $('#pplLeadAllowancesDiv').hide();
                    $('#pplDivider').hide();
                    $('#pplArea').hide();
                }
            }
            sub_loaded = true;
        });
        $('#featuredDealer').prop('checked', (data.is_featured == 1 ? true : false));
        $('#display').val(data.display);
        $('#merchant_type').val(data.type);
        $('#magazinemanager_id').val(data.magazinemanager_id);
        $('#is_deleted').val(data.franchise_deleted);
        $('#primary_contact').val(data.primary_contact);
        $('#is_permanent').prop('checked', (data.is_permanent==1?true:false));
        $('#contract_start').val(data.contract_start ? self.GetDate(data.contract_start) : '');
        $('#contract_end').val(data.contract_end ? self.GetDate(data.contract_end) : '');
        if(data.type == 'PROSPECT')
        {
            $('#divNational').show();
        }
        $('#allowGeneric').prop('checked', data.franchise && data.franchise.allow_generic_leads);
        $('#allowDirected').prop('checked', data.franchise && data.franchise.allow_directed_leads);
        $('#company_id').val(data.franchise.company_id);
        if(data.type == 'PPL')
        {
            if($('#parentcategory_id :selected').html() == 'Home Improvement')
                $('#pplCertifiedDiv').show();
            if(data.franchise && data.franchise.is_certified)
            {
                $('input[name=is_certified]').val(['1']);
                $('#pplLeadAllowancesDiv').show();
            }
            if(!sub_loaded)
            {
                $('#pplLeadAllowancesDiv').show();
            }
            if((data.franchise && data.franchise.is_certified) || !sub_loaded)
            {
                $('#pplDivider').show();
                $('#pplArea').show();
                $('#leadEmailArea').html(can.view('template_lead_email', 
                {
                    emails: data.lead_emails
                }));
                $('input[name=service_plan]').val([data.franchise.service_plan]);
                if(data.franchise.service_plan == 'trial')
                {
                    $('#trialDiv').show();
                }
                else
                {
                    $('#trialDiv').hide();
                }
                $('#trialStart').val(data.franchise.trial_starts_at ? self.GetDate(data.franchise.trial_starts_at) : '');
                $('#trialEnd').val(data.franchise.trial_ends_at ? self.GetDate(data.franchise.trial_ends_at) : '');
                $('#trialLeadCap').val(data.franchise.trial_lead_cap);
                if(data.franchise.is_dealer == 0)
                {
                    $('#contractorZipcode').val(data.franchise.zipcode);
                    $('#contractorRadius').val(Math.ceil(data.franchise.radius / 1609));
                    $('#contractorZipcode').parent().parent().show();
                    $('#contractorRadius').parent().parent().show();
                }
                else
                {
                    $('#contractorZipcode').parent().parent().hide();
                    $('#contractorRadius').parent().parent().hide();
                }
                $('#contractorBudget').val(data.franchise.monthly_budget);
                $('#contactPhone').val(data.franchise.contact_phone);
            }
            else
            {
                $('input[name=is_certified]').val(['0']);
                $('#pplDivider').hide();
                $('#pplArea').hide();
            }
        }
        else
        {
            this.PPLClear();
            $('#pplCertifiedDiv').hide();
            $('#pplLeadAllowancesDiv').hide();
            $('input[name=is_certified]:radio').prop('checked', false);
        }

        var checked = data.is_national == 1 ? true : false;
        $('#chkNational').prop('checked', checked);
        $('#market_id').val(data.market_id);
        CKEDITOR.instances.catchphrase.setData(data.catchphrase);
        $('#facebook').val(data.facebook);
        $('#twitter').val(data.twitter);
        $('#redemptions').val(data.mobile_redemption);
        $('#service_radius').val(data.service_radius ? Math.floor(data.service_radius / 1607) : 0);
        $('#entity_search_parse').val(data.entity_search_parse);
        $('#page_version').val(data.page_version);
        if(data.coupon_tab_type == 'Coupons')
        {
            $('#coupon_tab_type').val('Offers');
        } else {
            $('#coupon_tab_type').val(data.coupon_tab_type);
        }
        $('#is_offer_notifications').val(data.is_offer_notifications);
        $('#sponsor_level').val(data.franchise.sponsor_level);
        if(data.franchise.sponsor_level != '' && data.franchise.sponsor_level != null)
        {
            $('#sponsor_districts').parent().parent().show();
            $('#sponsor_districts option').prop('selected', false);
            for(var i = 0; i < data.districts.length; i++)
            {
                $('#sponsor_districts option[value="'+data.districts[i]+'"]').prop('selected', true);
            }
            if(data.franchise.sponsor_banner == '')
            {
                $('#sponsorBanner').attr('src', 'http://placehold.it/975X195');
            }
            else
            {
                $('#sponsorBanner').attr('src', data.franchise.sponsor_banner);
            }
        }
        else
        {
            $('#sponsor_districts option').prop('selected', false);
            $('#sponsor_districts').parent().parent().hide();
        }
        Media.findAll({merchant_id: selectedMerchant}, function(media)
        {
            media = media[0];
            if(typeof media.logo === 'undefined' || media.logo.path == '')
            {
                $('#imgLogo').attr('src', '/img/placeholder-media.jpg');
            }
            else
            {
                var myPath = media.logo.path;
                $('#imgLogo').attr('src', myPath);
            }
            if(typeof media.banner === 'undefined' || media.banner.path == '')
            {
                $('#imgBanner').attr('src', 'http://placehold.it/988X350');
            }
            else
            {
                var myPath = media.banner.path;
                $('#imgBanner').attr('src', myPath);
            }
        });
        $('#status').val(data.franchise_active);
        $('.wizard-step').removeClass('wizard-disabled');
        $('.wizard-step').each(function(index)
        {
            if($(this).attr('id') != 'wizardFranchise')
            {
                var link = $(this).find('a');
                if(link.attr('href').indexOf('viewing') == -1)
                {
                    link.attr('href', link.attr('href')+'?viewing='+selectedFranchise);
                    link.attr('onclick', '');
                }
            }
        });
        $('#btnSearchMerchants').hide();
    },
    'BindOneMerchant': function(data)
    {
        var self = this;
        if(data.yipitbusiness_id != 0)
        {
            if(json.data == true)
            {
                $('#btnUnblockYipits').show();
            } else {
                $('#btnBlockYipits').show();
            }
        } else {
            $('#btnBlockYipits').hide();
            $('#btnUnblockYipits').hide();
        }
        $('#sales').html('<option value="">----</option>');
        $('#sales').append(can.view('template_assignment',
        {
           users: data.sales_users.objects
        }));
        $('#parentcategory_id').val(data.category_id);
        Subcategory.findAll({category_id: data.category_id, limit: 100}, function(json)
        {
            var element = $('#category_id');
            element.html(can.view('template_subcategory',
            {
                subcategories: json
            }));
            element.val(data.subcategory_id);
        });
        $('#display').val(data.display);
        $('#merchant_type').val(data.type);
        $('#magazinemanager_id').val(data.magazinemanager_id);
        if(data.type == 'PROSPECT')
        {
            $('#divNational').show();
        }
        var checked = data.is_national == 1 ? true : false;
        $('#chkNational').prop('checked', checked);
        CKEDITOR.instances.catchphrase.setData(data.catchphrase);
        $('#facebook').val(data.facebook);
        $('#twitter').val(data.twitter);
        $('#redemptions').val(data.mobile_redemption);
        $('#service_radius').val(data.service_radius ? Math.floor(data.service_radius / 1607) : 0);
        $('#entity_search_parse').val(data.entity_search_parse);
        $('#page_version').val(data.page_version);
        if(data.coupon_tab_type == 'Coupons')
        {
            $('#coupon_tab_type').val('Offers');
        } else {
            $('#coupon_tab_type').val(data.coupon_tab_type);
        }
        $('#is_offer_notifications').val(data.is_offer_notifications);
        $('#is_permanent').prop('checked', (data.is_permanent==1?true:false));
        $('#contract_start').val(data.contract_start ? self.GetDate(data.contract_start) : '');
        $('#contract_end').val(data.contract_end ? self.GetDate(data.contract_end) : '');
        Media.findAll({merchant_id: selectedMerchant}, function(media)
        {
            media = media[0];
            if(typeof media.logo.attributes === 'undefined' || media.logo.attributes.path == '')
            {
                $('#imgLogo').attr('src', '/img/placeholder-media.jpg');
            }
            else
            {
                var myPath = media.logo.attributes.path;
                $('#imgLogo').attr('src', myPath);
            }
            if(typeof media.banner.attributes === 'undefined' || media.banner.attributes.path == '')
            {
                $('#imgBanner').attr('src', 'http://placehold.it/988X350');
            }
            else
            {
                var myPath = media.banner.attributes.path;
                $('#imgBanner').attr('src', myPath);
            }
        });
    },
    'BindSubcategories': function(data)
    {
        var element = $('#Subcategory');
        element.html('<option value="">----</option>');
        element.append(can.view('template_subcategory',
        {
            subcategories: data
        }))
    },
    'Validate': function()
    {
        var MerchObject = new Object();
        MerchObject.sales = $('#sales').val();
        MerchObject.market_id = $('#market_id').val();
        MerchObject.parentcategory_id = $('#parentcategory_id').val();
        MerchObject.category_id = $('#category_id').val();
        MerchObject.display = $('#display').val();
        MerchObject.merchant_type = $('#merchant_type').val();
        MerchObject.magazinemanager_id = $('#magazinemanager_id').val();

        $('.form-group').removeClass('has-error');
        var valid = true;
        for(var input in MerchObject)
        {
            if(MerchObject[input] == '')
            {
                $('#'+input).parent().parent().addClass('has-error');
                valid = false;
            }
        }
        if($('#merchant_type').val() == 'PPL')
        {
            if($('#parentcategory_id :selected').html() == 'Home Improvement')
            {
                var cert_check = false;
                $('input[name=is_certified]:radio').each(function()
                {
                    if($(this).prop('checked'))
                        cert_check = true;
                });
                if(!cert_check)
                {
                    valid = false;
                    $('input[name=is_certified]:radio').parent().addClass('has-error');
                }
            }

            if($('#is_certified_yes').prop('checked') || ($('#category_id :selected').html() == 'Auto Dealers' && ($('#allowGeneric').prop('checked') || $('#allowDirected').prop('checked'))))
            {
                // Make sure at least one lead email is filled out
                var lead_email_found = false
                $('.lead-email').each(function()
                {
                    if($(this).find('input').val() != '')
                    {
                        lead_email_found = true;
                    }
                });
                if(!lead_email_found)
                {
                    $('.lead-email').parent().parent().addClass('has-error');
                }
                var plan_check = false;
                $('input[name=service_plan]:radio').each(function()
                {
                    if($(this).prop('checked'))
                        plan_check = true;
                });
                if(!plan_check)
                {
                    valid = false;
                    $('input[name=service_plan]:radio').parent().addClass('has-error');
                }
                if(plan_check && $('input[name=service_plan]').val() == 'trial')
                {
                    if($('#trialStart').val() == '')
                    {
                        valid = false;
                        $('#trialStart').parent().parent().addClass('has-error');
                    }
                    if($('#trialEnd').val() == '')
                    {
                        valid = false;
                        $('#trialEnd').parent().parent().addClass('has-error');
                    }
                    if($('#trialLeadCap').val() == '')
                    {
                        valid = false;
                        $('#trialLeadCap').parent().parent().addClass('has-error');
                    }
                }
                $('.pplTextInputs input').each(function()
                {
                    if($(this).val() == '' && $('#category_id :selected').html() != 'Auto Dealers')
                    {
                        $(this).parent().addClass('has-error');
                        valid = false;
                    }
                });
            }
        }
        if(valid)
        {
            MerchObject.is_featured = $('#featuredDealer').prop('checked') ? 1 : 0;
            MerchObject.merchant_id = selectedMerchant;
            MerchObject.franchise_id = selectedFranchise;
            MerchObject.status = $('#status').val();
            MerchObject.is_deleted = $('#is_deleted').val();
            MerchObject.primary_contact = $('#primary_contact').val();
            MerchObject.company_id = $("#company_id").val();
            MerchObject.is_permanent = $('#is_permanent').prop('checked') ? 1 : 0;
            MerchObject.contract_start = this.GetTimeStamp($("#contract_start").val());
            MerchObject.contract_end = this.GetTimeStamp($("#contract_end").val());
            if(MerchObject.merchant_type == 'PPL' && $('#parentcategory_id :selected').html() == 'Home Improvement')
                MerchObject.is_certified = $('#is_certified_yes').prop('checked');
            if(MerchObject.merchant_type == 'PPL' && MerchObject.is_certified || ($('#category_id :selected').html() == 'Auto Dealers' && ($('#allowGeneric').prop('checked') || $('#allowDirected').prop('checked'))))
            {
                var lead_emails = '';
                $('.lead-email').each(function()
                {
                    lead_emails += $(this).find('input').val() != '' ? $(this).find('input').val()+';'+($(this).find("select").val())+',' : '';
                });
                lead_emails = lead_emails.slice(0,-1);
                MerchObject.lead_emails = lead_emails;
                MerchObject.service_plan = $('input[name=service_plan]:checked').val();
                if($('input[name=service_plan]:checked').val() == 'trial')
                {
                    MerchObject.trial_starts_at = this.GetTimeStamp($("#trialStart").val());
                    MerchObject.trial_ends_at = this.GetTimeStamp($("#trialEnd").val());
                    MerchObject.trial_lead_cap = $("#trialLeadCap").val();
                }
                else
                {
                    MerchObject.trial_starts_at = null;
                    MerchObject.trial_ends_at = null;
                    MerchObject.trial_lead_cap = 0;
                }
                MerchObject.lead_zipcode = $('#contractorZipcode').val();
                MerchObject.lead_radius = ($('#contractorRadius').val() * 1609); // Convert to meters
                MerchObject.lead_budget = $('#contractorBudget').val();
                MerchObject.contact_phone = $('#contactPhone').val();
                MerchObject.allow_generic_leads = $('#allowGeneric').prop('checked') ? 1 : 0;
                MerchObject.allow_directed_leads = $('#allowDirected').prop('checked') ? 1 : 0;
            }
            if($('#category_id :selected').html() == 'Auto Dealers')
            {
                MerchObject.allow_generic_leads = $('#allowGeneric').prop('checked') ? 1 : 0;
                MerchObject.allow_directed_leads = $('#allowDirected').prop('checked') ? 1 : 0;
            }
            if(selectedMerchant == 0)
            {
                MerchObject.catchphrase = '';
                MerchObject.max_prints = 1;
                MerchObject.page_title = '';
                MerchObject.keywords = '';
                MerchObject.about = '';
                MerchObject.demo = 1;
                MerchObject.facebook = '';
                MerchObject.twitter = '';
                MerchObject.website = '';
                MerchObject.hours = '';
                MerchObject.phone = '';
                MerchObject.partial = 1;
            }
            var self = this;
            var myMerchant = new Merchant(MerchObject);
            myMerchant.save(function(json)
            {
                $('#step1Done').button('reset');
                selectedFranchise = selectedFranchise == 0 ? json.franchise_id : selectedFranchise;
                selectedMerchant = selectedMerchant == 0 ? json.id : selectedMerchant;
                $('#imgMerchant_id').val(selectedMerchant);
                $('#bannerMerchant_id').val(selectedMerchant);
                $('#sponsorBannerFranchise_id').val(selectedFranchise);
                Merchant.findOne({franchise_id: selectedFranchise}, function(merchant)
                {
                    if(merchant.franchise.is_certified == 0 || ((merchant.franchise.is_certified == 1 || merchant.franchise.is_dealer == 1) && merchant.franchise.netlms_id != 0))
                    {
                        Note.findAll({franchise_id: selectedFranchise}, function(notes)
                        {
                            self.BindNotes(notes);
                            $('#step1').fadeOut(400, function()
                            {
                                $('#stepNotes').fadeIn(400);
                            });
                        });
                    }
                    else
                    {
                        $('#step1Done').button('reset');
                        $('#messages1').hide();
                        $('#messages1').css('color', 'red');
                        $('#messages1').html('Error creating merchant in NETLMS! Please notify system admin.');
                        $('#messages1').fadeIn(400);
                    }
                });
            }); 
        }
        else
        {
            $('#step1Done').button('reset');
            $('#messages1').hide();
            $('#messages1').css('color', 'red');
            $('#messages1').html('Please fill out all required fields!');
            $('#messages1').fadeIn(400);
        }
    },
    'BindNotes': function(notes)
    {
        $('#notesArea').html(can.view('template_note', 
        {
            notes: notes
        }));
    },
    'GetDate': function(time)
    {
        if(typeof time === 'object')
        {
            time = time.date
        }
        var c = time.split(/[- :]/);
        time = new Date(c[0], c[1]-1, c[2], c[3], c[4], c[5]);
        var myDate = this.Pad(Number((time.getMonth())+1),2)+'/'+this.Pad(time.getDate(),2)+'/'+time.getFullYear();

        return myDate;
    },
    'GetTimeStamp': function(date)
    {
        var aPieces = date.split('/');
        var timestamp = aPieces[2]+'-'+this.Pad(aPieces[0], 2)+'-'+this.Pad(aPieces[1],2)+' 00:00:00';
        return timestamp;
    },
    'Pad': function(number, length) 
    {
        var str = '' + number;
        while (str.length < length) {
            str = '0' + str;
        }
        return str;
    }
});

var ModalControl = can.Control({
    init: function(element, options)
    {
        var self = this;
        current_page = 0;
    },
    //Events
    '#modQuery keydown': function(element, event)
    {
        if(event.which != 13 && element.val().length > 2)
        {
            clearTimeout(typingTimer);
        }
    },
    '#modQuery keyup': function( element, event ) 
    {       
        var self = this; 
        if(element.val().length > 2 && event.which!=13)
        {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(modal_control.TriggerSearch, doneTypingInterval);
        }
        else if(event.which==13)
        {
            clearTimeout(typingTimer);
            current_page = 0;
            self.search();
        }
    },
    '#showInactive change': function(element)
    {
        this.search();
    },
    '.btn-edit click': function(element)
    {
        selectedFranchise = element.data('franchise_id');
        selectedMerchant = element.data('merchant_id');
        merch_control.Search();
        $('#addNew').prop('disabled', false);
        $('#addNew').tooltip({'title': 'Create New Franchise', 'placement': 'top'});
    },
    //Methods
    'TriggerSearch': function()
    {
        current_page = 0;
        modal_control.search();
    },
    'search': function()
    {
        var self = this;
        var SearchObject = new Object();
        SearchObject.page = current_page;
        SearchObject.name = $('#modQuery').val();
        SearchObject.limit = 5;
        if(!$('#showInactive').prop('checked'))
        {
            SearchObject.status = 1;
        }

        Merchant.findAll(SearchObject, function(json)
        {
            self.BindResults(json);
            self.BindPagination(json);
        });
    },
    'BindResults': function(data)
    {
        var element = $('#resultsArea');
        element.html(can.view('template_result',
        {
            results: data
        })).find('.btn-edit').tooltip();
    },
    'BindPagination': function(data)
    {
        var first = $('#first');
        var prev = $('#prev');
        var next = $('#next');
        var last = $('#last');
        var current = $('#lblCurrentPage');

        var lastpage = Math.floor((data.stats.total / data.stats.take)) == 0 ? 0 : Math.floor(data.stats.total / data.stats.take);

        if(data.stats.page == 0)
        {
            first.parent().addClass('disabled');
            prev.parent().addClass('disabled');
        }
        else
        {
            first.parent().removeClass('disabled');
            prev.parent().removeClass('disabled');
            prev.data('page', Number(data.stats.page)-1);
            first.data('page', 0);
        }
        if(data.stats.page == lastpage)
        {
            last.parent().addClass('disabled');
            next.parent().addClass('disabled');
        }
        else
        {
            last.parent().removeClass('disabled');
            next.parent().removeClass('disabled');
            next.data('page', Number(data.stats.page)+1);
            last.data('page', lastpage);
        }
        current.html((Number(data.stats.page)+1)+" of "+(Number(lastpage)+1));
    }
});

var MerchantModalControl = can.Control({
    init: function(element, options)
    {
        var self = this;
        current_page = 0;
    },
    //Events
    '#mQuery keypress': function( element, event ) 
    {       
        var self = this; 
        if(element.val().length > 2)
        {
            merchant_current_page = 0;
            self.search();
        }
        else if(event.which==13)
        {
            merchant_current_page = 0;
            self.search();
        }
    },
    '.btn-choose click': function(element)
    {
        selectedMerchant = element.data('merchant_id');
        merch_control.SearchMerchant();
    },
    //Methods
    'search': function()
    {
        var self = this;
        var SearchObject = new Object();
        SearchObject.page = merchant_current_page;
        SearchObject.name = $('#mQuery').val();
        SearchObject.limit = 5;
        SearchObject.merchant_search = 1;

        Merchant.findAll(SearchObject, function(json)
        {
            self.BindResults(json);
            self.BindPagination(json);
        });
    },
    'BindResults': function(data)
    {
        var element = $('#merchantResultsArea');
        element.html(can.view('template_merchant_result',
        {
            results: data
        })).find('.btn-choose').tooltip();
    },
    'BindPagination': function(data)
    {
        var first = $('#merchantFirst');
        var prev = $('#merchantPrev');
        var next = $('#merchantNext');
        var last = $('#merchantLast');
        var current = $('#merchantLblCurrentPage');

        var lastpage = Math.floor((data.stats.total / data.stats.take)) == 0 ? 0 : Math.floor(data.stats.total / data.stats.take);

        if(data.stats.page == 0)
        {
            first.parent().addClass('disabled');
            prev.parent().addClass('disabled');
        }
        else
        {
            first.parent().removeClass('disabled');
            prev.parent().removeClass('disabled');
            prev.data('page', Number(data.stats.page)-1);
            first.data('page', 0);
        }
        if(data.stats.page == lastpage)
        {
            last.parent().addClass('disabled');
            next.parent().addClass('disabled');
        }
        else
        {
            last.parent().removeClass('disabled');
            next.parent().removeClass('disabled');
            next.data('page', Number(data.stats.page)+1);
            last.data('page', lastpage);
        }
        current.html((Number(data.stats.page)+1)+" of "+(Number(lastpage)+1));
    }
});

var PaginationControl = can.Control({
    init: function(element, options)
    {
        var self = this;
    },
    //Events
    'a click': function(element, options)
    {
        current_page = element.data('page');
        modal_control.search();
    }
});

var MerchantPaginationControl = can.Control({
    init: function(element, options)
    {
        var self = this;
    },
    //Events
    'a click': function(element, options)
    {
        merchant_current_page = element.data('page');
        merchant_modal_control.search();
    }
});


merch_control = new MerchantControl($('#main'));
modal_control = new ModalControl($('#myModal'));
merchant_modal_control = new MerchantModalControl($('#merchantModal'));
page_control = new PaginationControl($('#paginationBottom'));
merchant_page_control = new MerchantPaginationControl($('#merchantPaginationBottom'));

</script>
