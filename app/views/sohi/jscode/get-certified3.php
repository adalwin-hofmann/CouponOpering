<script>

MainControl = can.Control(
{
    init: function()
    {

    },
    // Events
    'input[name=isLicensed] change': function(element)
    {
        if(element.val() == 1)
        {
            $('input[name=license_number]').prop('disabled', false);
        }
        else
        {
            $('input[name=license_number]').prop('disabled', true);
            $('input[name=license_number]').val('');   
        }
    },
    'input[name=isBonded] change': function(element)
    {
        if(element.val() == 1)
        {
            $('input[name=bond_number]').prop('disabled', false);
        }
        else
        {
            $('input[name=bond_number]').prop('disabled', true);
            $('input[name=bond_number]').val('');   
        }
    },
    'input[name=isInsured] change': function(element)
    {
        if(element.val() == 1)
        {
            $('input[name=insurance_company]').prop('disabled', false);
            $('input[name=policy_number]').prop('disabled', false);
            $('input[name=agent]').prop('disabled', false);
            $('input[name=agent_phone]').prop('disabled', false);
        }
        else
        {
            $('input[name=insurance_company]').prop('disabled', true);
            $('input[name=policy_number]').prop('disabled', true);
            $('input[name=agent]').prop('disabled', true);
            $('input[name=agent_phone]').prop('disabled', true);
            $('input[name=insurance_company]').val('');   
            $('input[name=policy_number]').val('');   
            $('input[name=agent]').val('');   
            $('input[name=agent_phone]').val('');   
        }
    },
    'input[name=has_outside_labor] change': function(element)
    {
        if(element.val() == 1)
        {
            $('#divOutsideInsured').fadeIn(500);
        }
        else
        {
            $('#divOutsideInsured').fadeOut(500);
            $('input[name=is_outside_insured][value=0]').prop('checked', true);
        }
    },
    'input[name=does_background_checks] change': function(element)
    {
        if(element.val() == 0)
        {
            $('#divExplaination').fadeIn(500);
        }
        else
        {
            $('#divExplaination').fadeOut(500);
            $('input[name=background_explaination]').val('');
        }
    }
    // Methods

});

main_control = new MainControl($('.main-content'));

</script>