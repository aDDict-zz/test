function IsNumeric(sText){
	var ValidChars = "0123456789.";
	var IsNumber=true;
	var Char;
	
	for (i = 0; i < sText.length && IsNumber == true; i++){
		Char = sText.charAt(i);
		if (ValidChars.indexOf(Char) == -1){
			IsNumber = false;
		}
	}
	return IsNumber;
}

var Ajax = new Object();
Ajax.send = function(url, method, parameters, callBack, data){	
	var HttpRequest = "";
	try{
		HttpRequest = new ActiveXObject("Msxml2.XMLHTTP");
	}catch(e){
		try{
			HttpRequest = new ActiveXObject("Microsoft.XMLHTTP");
		}catch(e){
			try{
				HttpRequest = new XMLHttpRequest();
			}catch(e){
				alert("XMLHttpRequest not suported!");
				return false;
			}
		}
	} 
	HttpRequest.onreadystatechange = function() {
		if (HttpRequest.readyState == 4) {// only if req shows "loaded"
			if (HttpRequest.status < 400) {// only if "OK"				
				if(callBack!=null){
					if(data==null)	callBack(HttpRequest);
						else callBack(HttpRequest, data);
				}
			} else {
				alert("There was a problem loading data :\n" + HttpRequest.status+ "/" + HttpRequest.statusText);
			}
		}

	}
	if (method=="POST") {
		HttpRequest.open("POST", url, true);
		HttpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		HttpRequest.send(parameters);
	} else {		
		HttpRequest.open("GET", url + "?" + parameters, true);
		HttpRequest.send(null);

	}
}