ToolMan._cookieOven = {

	set : function(name, value, expirationInDays) {
		if (expirationInDays) {
			var date = new Date()
			date.setTime(date.getTime() + (expirationInDays * 24 * 60 * 60 * 1000))
			var expires = "; expires=" + date.toGMTString()
		} else {
			var expires = ""
		}
		var group_id = gup( 'group_id' );
        var form_id = gup( 'form_id' );
		document.cookie = name + "=" + value + expires + "; path=/";
        var w=document.getElementById("pnum");
        if (w)
		    var url='set_list_order.php?itemsid='+value+'&pagenum='+ document.getElementById("pnum").value+'&perpage='+document.getElementById("ppage").value+'&group_id='+group_id;
        else
		    var url='fe_set_list_order.php?itemsid='+value+'&form_id='+form_id+'&group_id='+group_id;
		setlistorder_request = new xmlhttp_request(url, setlistorder_response);
	},

	get : function(name) {
		var namePattern = name + "="
		var cookies = document.cookie.split(';')
		for(var i = 0, n = cookies.length; i < n; i++) {
			var c = cookies[i]
			while (c.charAt(0) == ' ') c = c.substring(1, c.length)
			if (c.indexOf(namePattern) == 0)
				return c.substring(namePattern.length, c.length)			
		}
		return null
	},

	eraseCookie : function(name) {
		createCookie(name, "", -1)
	}
}

function setlistorder_response() {
	var resp = decodeURIComponent(setlistorder_request.response);
	if (resp>"") {
		tmp=resp.split(",");
		for (i = 0; i < tmp.length-1; i++) {	
			t=tmp[i].split("-");
			var o=document.getElementById(t[0]);
			o.id=t[1];
		}
		items = document.getElementsByName("pagenum");	
		for (i = 0; i < items.length; i++) {	
			items[i].innerHTML=i+1;
		}
	}
}

function gup( name )
{
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var tmpURL = window.location.href;
  var results = regex.exec( tmpURL );
  if( results == null )
    return "";
  else
    return results[1];
}
