<script>

LtvControl = can.Control(
{
    init: function()
    {

    },
    //Events
    '#first click': function()
    {
        $('#page').val(0);
        $('#searchForm').submit();
    },
    '#prev click': function()
    {
        $('#page').val(Number($('#page').val())-1);
        $('#searchForm').submit();
    },
    '#next click': function()
    {
        $('#page').val(Number($('#page').val())+1);
        $('#searchForm').submit();
    },
    '#last click': function()
    {
        $('#page').val(lastpage);
        $('#searchForm').submit();
    }
    //Methods


});

var ltv_control = new LtvControl($("#main"));

</script>