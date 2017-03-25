
<iframe id="gnewtonIframe" name="gnewtonIframe" 
            width="100%" height="500px" frameBorder="0" scrolling ="no"  allowTransparency="true"
            src="http://newton.newtonsoftware.com/career/CareerHome.action?clientId=8a699b9842d2671c0142e3cf44380c6e&gnewtonResize=http://www.saveoneverything.com/corporate/GnewtonResize.htm">Sorry, your browser doesn't seem to support Iframes!</iframe>


<script type="text/javascript">
    //
    // This script should be after the Iframe "gnewtonIframe". Othewise we cannot get the Iframe's object.
    //
    
    //parse URL and get parameters from browser' location.
    function getParameters(para, url, arg) {
        var args = '';
        
        var p = url.indexOf('?' + para);
        
        if (p == -1) {
            p = url.indexOf('&' + para);
            
        }
        
        if (p != -1) {
            p += (para.length + 1);
            
            var p2 = url.indexOf('&', p);
            if (p2 == -1) 
                p2 = url.length;
            
            args = arg + url.substring(p, p2);
        }
        
        return args;
    }

    var l = location.href;
    
    var flag = getParameters('gnk=', l, '').toLowerCase();
    
    if (flag == 'job' || flag == 'apply') {
        var args = getParameters('gni=', l, '&id=');
        
        args += getParameters('gns=', l, '&source=');
        
        //navigate to the specified job description.
        var f = document.getElementById('gnewtonIframe');
        if (f) {
            var s = f.src;
            s = s.replace(/&amp;/gi, "&");
            s = s.replace(/&amp/gi, "&");
            
            args += getParameters('clientId=', s, '&clientId=');
            args += getParameters('gnewtonResize=', s, '&gnewtonResize=');
            
            var p = s.indexOf('?');
            if (p != -1 && args.length) {
                p2 = s.lastIndexOf('/', p);
                if (p2 != -1) {
                    var base = s.substring(0, p2 + 1);
                    if (flag == 'apply') {
                        base += 'SubmitResume.action?';
                    }
                    else {
                        base += 'JobIntroduction.action?';
                    }
                    f.src = base + args.substr(1);
                }
            }
        }
    } else {
        var newSource = getParameters('gns', l, '');
        if (newSource) {
            newSource = newSource.substring(1);
            if (l.indexOf('?') != -1) {
                var f = document.getElementById('gnewtonIframe');
                f.src = f.src + "&gns=" + newSource
            } else {
                var f = document.getElementById('gnewtonIframe');
                f.src = f.src + "?gns=" + newSource
            }
        }    
    }

    //The function of resize IFrame
    function resizeFrame(height, scrollToTop) {
        if (scrollToTop){
            window.scrollTo(0, 0);
        }
        
        var oFrame = document.getElementById('gnewtonIframe');
        if (oFrame){
            oFrame.height = height;
        }
    }
    
</script>
<!-- end iFrame and Javascript part -->