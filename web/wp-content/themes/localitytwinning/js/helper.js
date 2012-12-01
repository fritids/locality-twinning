function setCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function removeCookie(name) {
    setCookie(name,"",-1);
}

function switchDevice()
{
    if( getCookie('isMobile') == 'no' )
    {
        setCookie('isMobile', 'yes');
    }else{
        setCookie('isMobile', 'no');
    }

    var elementText  = document.getElementById("mediaSwitcher").innerText;

    if(window._gaq)
    {
        if(elementText == 'View Mobile Version')
        {
            _gaq.push(['_trackEvent', 'Site Switch', 'Mobile']);
        }else if(elementText == 'View Desktop')
        {
            _gaq.push(['_trackEvent', 'Site Switch', 'Desktop']);
        }
    }

    if(elementText == 'View Mobile Version' && !isMobilePage)
    {
        window.location.href = '/';
    }else{
        window.location.href = document.URL;
    }
}

function RemoveMobileCSS()
{
    if( getCookie('isMobile') == 'no' )
    {
        var node = document.getElementById("mediaCSS");
        if (node.parentNode) {
            node.parentNode.removeChild(node);
        }
    }
}

function isMobile()
{
    //var bodyClass = document.getElementsByTagName('body')[0].className; //(bodyClass.indexOf('webkit-mobile') != -1) ||
    var isMobileVar = ( (navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)));
    if(isMobileVar && getCookie('isMobile') != 'no' )
    {
        return true;
    }
    return false;
}

function isMobilePage(val)
{
    isMobilePage = val;
    return val;
}

function updatemediaSwitherText()
{
    if( getCookie('isMobile') == 'no' )
    {
        document.getElementById("mediaSwitcher").innerText = 'View Mobile Version';
        document.getElementById("mediaSwitcher3").innerText = 'View Mobile Version';

        document.getElementById("mediaSwitcher2").style.display = 'block';
        document.getElementById("mediaSwitcher3").style.display = 'block';
    }else{
        document.getElementById("mediaSwitcher2").style.display = 'none';
        document.getElementById("mediaSwitcher3").style.display = 'none';
    }

    if(isMobile)
    {
        if(getCookie('isMobile') == 'no')
        {
            document.getElementById("mediaSwitcher").style.display = 'none';
            document.getElementById("mediaSwitcher2").style.display = 'block';
            document.getElementById("mediaSwitcher3").style.display = 'block';

        }else{
            if(isMobilePage){

            }else{
                document.getElementById("mediaSwitcher").style.display = 'none';
                document.getElementById("mediaSwitcher2").style.display = 'block';
                document.getElementById("mediaSwitcher3").style.display = 'block';
            }
        }
    }

}