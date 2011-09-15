
// WebTicker by Mioplanet
// www.mioplanet.com

 
HIREK_TICKER_RIGHTTOLEFT = false;
HIREK_TICKER_SPEED = 2;
HIREK_TICKER_STYLE = "font-family:Arial; font-size:10px; color:#333333";
HIREK_TICKER_PAUSED = true;

function HIREK_TICKER_start() {
	var tickerSupported = false;
	HIREK_TICKER_WIDTH = document.getElementById("HIREK_TICKER").style.width;
    HIREK_TICKER_CONTENT = document.getElementById("HIREK_TICKER_content").innerHTML;
    HIREK_TICKER_BUTTON = document.getElementById("HIREK_TICKER_button").innerHTML;

	var img = "";//<img src=gfx/spacer.gif width="+HIREK_TICKER_WIDTH+" height=0>";

	// Firefox
	if (navigator.userAgent.indexOf("Firefox")!=-1 || navigator.userAgent.indexOf("Safari")!=-1) {
		document.getElementById("HIREK_TICKER").innerHTML = HIREK_TICKER_BUTTON + "<TABLE  cellspacing='0' cellpadding='0' width='100%'><TR><TD nowrap='nowrap'>"+img+"<SPAN style='"+HIREK_TICKER_STYLE+"' ID='HIREK_TICKER_BODY' width='100%'>&nbsp;</SPAN>"+img+"</TD></TR></TABLE>";
		tickerSupported = true;
	}
	// IE
	if (navigator.userAgent.indexOf("MSIE")!=-1 && navigator.userAgent.indexOf("Opera")==-1) {
		document.getElementById("HIREK_TICKER").innerHTML = HIREK_TICKER_BUTTON + "<DIV nowrap='nowrap' style='width:100%;'>"+img+"<SPAN style='"+HIREK_TICKER_STYLE+"' ID='HIREK_TICKER_BODY' width='100%'></SPAN>"+img+"</DIV>";
		tickerSupported = true;
	}
	if(!tickerSupported) document.getElementById("HIREK_TICKER").outerHTML = ""; else {
		document.getElementById("HIREK_TICKER").scrollLeft = HIREK_TICKER_RIGHTTOLEFT ? document.getElementById("HIREK_TICKER").scrollWidth - document.getElementById("HIREK_TICKER").offsetWidth : 0;
		document.getElementById("HIREK_TICKER_BODY").innerHTML = HIREK_TICKER_CONTENT;
		document.getElementById("HIREK_TICKER").style.display="block";
        window.setTimeout("HIREK_TICKER_begin_tick()", 5000);
//        HIREK_TICKER_begin_tick();
	}
}
function HIREK_TICKER_begin_tick() {
    HIREK_TICKER_PAUSED=false;
    HIREK_TICKER_tick();
    HIREK_TICKER_setpos();
}

function HIREK_TICKER_tick() {
	if(!HIREK_TICKER_PAUSED) document.getElementById("HIREK_TICKER").scrollLeft += HIREK_TICKER_SPEED * (HIREK_TICKER_RIGHTTOLEFT ? -1 : 1);
	if(HIREK_TICKER_RIGHTTOLEFT && document.getElementById("HIREK_TICKER").scrollLeft <= 0) document.getElementById("HIREK_TICKER").scrollLeft = document.getElementById("HIREK_TICKER").scrollWidth - document.getElementById("HIREK_TICKER").offsetWidth;
	if(!HIREK_TICKER_RIGHTTOLEFT && document.getElementById("HIREK_TICKER").scrollLeft >= document.getElementById("HIREK_TICKER").scrollWidth - document.getElementById("HIREK_TICKER").offsetWidth) document.getElementById("HIREK_TICKER").scrollLeft = 0;
	window.setTimeout("HIREK_TICKER_tick()", 50);
}
function HIREK_TICKER_setpos () {
    var o = document.getElementById('HIREK');
    if (o) {
        var tickerH  = o.offsetHeight; 
        var wb;
        if (window.innerHeight) {
            var scrolly = window.pageYOffset
            wb = window.innerHeight+window.pageYOffset;
        } else {
            var scrolly = document.documentElement.scrollTop?document.documentElement.scrollTop:document.body.scrollTop;
            var oh = document.documentElement.clientHeight?document.documentElement.clientHeight:document.body.clientHeight;
            wb = oh + scrolly;
        }
        o.style.top = parseInt(wb - tickerH-5) + 'px' ;
    }
    setTimeout("HIREK_TICKER_setpos()", 1000);
}
function HIREK_TICKER_on(on) {
    if (on == undefined) on = 1;
    var o = document.getElementById('HIREK_TICKER_BODY');
    if (o) o.style.display = on ? 'inline' : 'none';
}
