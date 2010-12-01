/**
*
*/

google.load("gdata", "2.x");
google.setOnLoadCallback(initializer);

function initializer(){
    //utube.auth();
}

var utube = {};
/** auth */
utube.auth = function(){
    if(google.accounts.user.checkLogin(cfg.scope) == "" && cfg.url == cfg.index){
        google.accounts.user.login(cfg.scope);
    } else if(google.accounts.user.checkLogin(cfg.scope) != "" && cfg.url == cfg.index){
        cfg.token = utube.getHashByQuerystring($.cookie("g314-scope-0"))["token"]; alert(cfg.token);
    }
}
/** eloallit egy hasht az atadott querystring alapjan */
utube.getHashByQuerystring = function(str){
    var arr = str.split("&");
    if(arr.length > 0){
		var hash = {};
        for(var i in arr){
            var array = arr[i].split("=");
            hash[array[0]] = array[1];
        }
        return hash;
    }
}

function doCheck(){
    scope = "http://www.google.com/calendar/feeds";
    var token = google.accounts.user.checkLogin(scope);
}



//var initializer = {};
//initializer.init = function(){
//    /*if(cfg.url.indexOf("token") == -1){
//        //window.location.href = "https://www.google.com/accounts/AuthSubRequest?next=" + escape(cfg.pathToTheIndexHtml) + "&scope=http%3A%2F%2Fgdata.youtube.com&session=0&secure=0";
//        scope = "http://gdata.youtube.com";
//        var token = google.accounts.user.login(scope);

//    } else {
//        cfg.token = initializer.getHashByUrl()["token"];
//    }*/
////    scope = "http://gdata.youtube.com";
////    var token = google.accounts.user.login(scope);
////    window.location.href = "https://www.google.com/accounts/ClientLogin?accountType=GOOGLE&Email=robthot&Passwd=traktor01&service=youtube&source=testt"
////alert( jQuery );
////    $.post(
////		"https://www.google.com/accounts/ClientLogin",
////		"Email=robthot&Passwd=traktor01&service=youtube&source=testt",
////		callBack,
////		"text"
////	);
////    var iframe = document.createElement('iframe');
////    iframe.setAttribute("src", "http://uploads.gdata.youtube.com/resumable/feeds/api/users/default/uploads?alt=jsonc&v=2");
////    iframe.setAttribute("id", "utubeContainer");
////    iframe.setAttribute("onload", "initializer.iframeOnload()");
////    iframe.setAttribute("style", "width:1400px;height:600px;");
////    document.getElementsByTagName("body")[0].appendChild(iframe);
////    var script = document.createElement('script');
////    script.setAttribute("src", "http://gdata.youtube.com/feeds/api/standardfeeds/most_popular?v=2&alt=json&&callback=showMyVideos");
////    script.setAttribute("onload", "initializer.scriptOnload()");
////    document.getElementsByTagName("body")[0].appendChild(script);
//}

//var showMyVideos = function(data){
//    var feed = data.feed;
//    var entries = feed.entry || [];
//    var html = ['<ul>'];
//    for (var i = 0; i < entries.length; i++) {
//        var entry = entries[i];
//        var title = entry.title.$t;
//        html.push('<li>', title, '</li>');
//    }
//    html.push('</ul>');
//    document.getElementById('videos').innerHTML = html.join('');
//}

//initializer.scriptOnload = function(){
////    var script = document.createElement('script');
//////    script.data = "alert('fokkkkkk');";
////    var iframe = document.getElementById("utubeContainer");
////    iframe.appendChild(script);
//    //alert( $("#utubeContainer").attr("src") );
//}

//var callBack = function(resp){
//    alert("asdasdasd: " + resp);
//}

