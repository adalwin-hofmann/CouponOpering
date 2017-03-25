<script>

var FormsBox = can.Control({
    init: function(element, options)
    {
        var self = this;
    },
    //Events
    'button click': function(element, options)
    {
        $(window).resize();
        //Hide the button
        element.hide();

        //Hide all formDivs
        var allFormBoxes = $("#formSelection").children('div');
        for(var i=1; i<=allFormBoxes.length; i++)
        {
            var formBox = $("#formSelection div.box:nth-child("+i+")").slideUp(500);
        }

        //Show selected formDiv
        var parent = element.parent();
        var wufooDiv = parent.find('[id^=wufoo]');
        var wufooID = wufooDiv.attr('id');
        var pieces = wufooID.split('-');
        var wufooNum = pieces[1];
        element.parent().parent().slideDown(500, this.ResizeIpad(wufooNum));

        wufooDiv.find('iframe').attr('height', frameHeights[wufooNum]);
        var formDiv = parent.children('div:first');

        formDiv.slideDown(500);

        //Show selected tab header
        element.parent().parent().children('.tab-header').slideDown(500);
    },
    //Methods
    'ResizeIpad': function(wufooNum) 
    {
        $('meta[name="viewport"]').attr('content', 'height='+frameHeights[wufooNum]+',width=device-width,initial-scale=1.0,maximum-scale=1.0');
        $(window).resize();
    }
});


new FormsBox( $('#formSelection') );

</script>

<script type="text/javascript">
    frameHeights.m7w9w9 = '3321';
    var m7w9w9;(function(d, t) {
    var s = d.createElement(t), options = {
    'userName':'saveoneverything', 
    'formHash':'m7w9w9', 
    'autoResize':true,
    'height':'3321',
    'async':true,
    'header':'show', 
    'ssl':true};
    s.src = ('https:' == d.location.protocol ? 'https://' : 'http://') + 'wufoo.com/scripts/embed/form.js';
    s.onload = s.onreadystatechange = function() {
    var rs = this.readyState; if (rs) if (rs != 'complete') if (rs != 'loaded') return;
    try { m7w9w9 = new WufooForm();m7w9w9.initialize(options);m7w9w9.display(); } catch (e) {}};
    var scr = d.getElementsByTagName(t)[0], par = scr.parentNode; par.insertBefore(s, scr);
    })(document, 'script');
</script>

<script type="text/javascript">
frameHeights.z7p7m5 = '1502';
    var z7p7m5;(function(d, t) {
    var s = d.createElement(t), options = {
    'userName':'saveoneverything', 
    'formHash':'z7p7m5', 
    'autoResize':true,
    'height':'1512',
    'async':true,
    'header':'show', 
    'ssl':true};
    s.src = ('https:' == d.location.protocol ? 'https://' : 'http://') + 'wufoo.com/scripts/embed/form.js';
    s.onload = s.onreadystatechange = function() {
    var rs = this.readyState; if (rs) if (rs != 'complete') if (rs != 'loaded') return;
    try { z7p7m5 = new WufooForm();z7p7m5.initialize(options);z7p7m5.display(); } catch (e) {}};
    var scr = d.getElementsByTagName(t)[0], par = scr.parentNode; par.insertBefore(s, scr);
    })(document, 'script');
</script>

<script type="text/javascript">
frameHeights.k7p9r1 = '1069';
    var k7p9r1;(function(d, t) {
    var s = d.createElement(t), options = {
    'userName':'saveoneverything', 
    'formHash':'k7p9r1', 
    'autoResize':true,
    'height':'1069',
    'async':true,
    'header':'show', 
    'ssl':true};
    s.src = ('https:' == d.location.protocol ? 'https://' : 'http://') + 'wufoo.com/scripts/embed/form.js';
    s.onload = s.onreadystatechange = function() {
    var rs = this.readyState; if (rs) if (rs != 'complete') if (rs != 'loaded') return;
    try { k7p9r1 = new WufooForm();k7p9r1.initialize(options);k7p9r1.display(); } catch (e) {}};
    var scr = d.getElementsByTagName(t)[0], par = scr.parentNode; par.insertBefore(s, scr);
    })(document, 'script');
</script>

<script type="text/javascript">
    frameHeights.xxae44b1gyj3j4 = '724';
    var xxae44b1gyj3j4;(function(d, t) {
    var s = d.createElement(t), options = {
    'userName':'saveoneverything', 
    'formHash':'xxae44b1gyj3j4', 
    'autoResize':true,
    'height':'724',
    'async':true,
    'host':'wufoo.com',
    'header':'show', 
    'ssl':true};
    s.src = ('https:' == d.location.protocol ? 'https://' : 'http://') + 'wufoo.com/scripts/embed/form.js';
    s.onload = s.onreadystatechange = function() {
    var rs = this.readyState; if (rs) if (rs != 'complete') if (rs != 'loaded') return;
    try { xxae44b1gyj3j4 = new WufooForm();xxae44b1gyj3j4.initialize(options);xxae44b1gyj3j4.display(); } catch (e) {}};
    var scr = d.getElementsByTagName(t)[0], par = scr.parentNode; par.insertBefore(s, scr);
    })(document, 'script');
</script>

<script type="text/javascript">
    frameHeights.r1ipwbyn0n53r39 = '801';
    var r1ipwbyn0n53r39;(function(d, t) {
    var s = d.createElement(t), options = {
    'userName':'saveoneverything', 
    'formHash':'r1ipwbyn0n53r39', 
    'autoResize':true,
    'height':'801',
    'async':true,
    'host':'wufoo.com',
    'header':'show', 
    'ssl':true};
    s.src = ('https:' == d.location.protocol ? 'https://' : 'http://') + 'wufoo.com/scripts/embed/form.js';
    s.onload = s.onreadystatechange = function() {
    var rs = this.readyState; if (rs) if (rs != 'complete') if (rs != 'loaded') return;
    try { r1ipwbyn0n53r39 = new WufooForm();r1ipwbyn0n53r39.initialize(options);r1ipwbyn0n53r39.display(); } catch (e) {}};
    var scr = d.getElementsByTagName(t)[0], par = scr.parentNode; par.insertBefore(s, scr);
    })(document, 'script');
</script>

<script type="text/javascript">
    frameHeights.revwika1ui8uuz = '1103';
    var revwika1ui8uuz;(function(d, t) {
    var s = d.createElement(t), options = {
    'userName':'saveoneverything',
    'formHash':'revwika1ui8uuz',
    'autoResize':true,
    'height':'1103',
    'async':true,
    'host':'wufoo.com',
    'header':'show',
    'ssl':true};
    s.src = ('https:' == d.location.protocol ? 'https://' : 'http://') + 'wufoo.com/scripts/embed/form.js';
    s.onload = s.onreadystatechange = function() {
    var rs = this.readyState; if (rs) if (rs != 'complete') if (rs != 'loaded') return;
    try { revwika1ui8uuz = new WufooForm();revwika1ui8uuz.initialize(options);revwika1ui8uuz.display(); } catch (e) {}};
    var scr = d.getElementsByTagName(t)[0], par = scr.parentNode; par.insertBefore(s, scr);
    })(document, 'script');
</script>

