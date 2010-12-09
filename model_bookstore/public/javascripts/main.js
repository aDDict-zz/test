$(document).ready(init);

function init(){
    $("#submit").click(function(){
        $.post(
            "/search",
            "author=" + $("#author").val() + "&title=" + $("#title").val() + "&bookStores=" + $("#bookStores").val(),
            function(resp){
                if(typeof(resp) == "object"){
                    main.datas = resp;
                    main.setup();
                } else {
                    alert("nincs talalat, vagy valamilyen hiba tortent ");
                }
            },
            "json"
        );
    })
}

var main = {};
main.datas = {};
main.setup = function(){
    for(var i in main.datas){
        var a = document.createElement("a");
        var br = document.createElement("br");
        a.setAttribute("rel", i);
        a.setAttribute("onclick", "main.openWindow(this);");
        a.setAttribute("href", "javascript:return false;");
        a.appendChild(document.createTextNode(main.datas[i]["title"]));
        $("#content").append(a);
        $("#content").append(br);
    }
}
main.openWindow = function(obj){
    var thisObj = main.datas[$(obj).attr("rel")];

    if(thisObj["poduct"] != "" && thisObj["img"] != ""){
        var html = "<a href='" + thisObj["poduct"] + "' target='_blank'><img src='" + thisObj["img"] + "' /></a>";
    } else if(thisObj["poduct"] == "" && thisObj["img"] != ""){
        html += "<img src='" + thisObj["img"] + "' />";
    }

    if(thisObj["price"] != ""){
        html += "<p>price:  <span>" + thisObj["price"] + "</span></p>";
    } else {
        html += "<p>price:  <span> no data </span></p>";
    }

    if(thisObj["ISBN"]["ISBN-10"] != ""){
        html += "<p>ISBN-10:  <span>" + thisObj["ISBN"]["ISBN-10"] + "</span></p>";
    } else {
        html += "<p>ISBN-10:  <span> no data </span></p>";
    }

    if(thisObj["ISBN"]["ISBN-13"] != ""){
        html += "<p>ISBN-13:  <span>" + thisObj["ISBN"]["ISBN-13"] + "</span></p>";
    } else {
        html += "<p>ISBN-13:  <span> no data </span></p>";
    }

    if(thisObj["author"] != ""){
        html += "<p>author:  <span>" + thisObj["author"] + "</span></p>";
    } else {
        html += "<p>author:  <span> no data </span></p>";
    }

    if(thisObj["title"] != ""){
        html += "<p>title:  <span>" + thisObj["title"] + "</span></p>";
    } else {
        html += "<p>title:  <span> no data </span></p>";
    }

    if(thisObj["publisher"] != ""){
        html += "<p>publisher:  <span>" + thisObj["publisher"] + "</span></p>";
    } else {
        html += "<p>publisher:  <span> no data </span></p>";
    }

    if(undefined != thisObj["reviews"]["link"]){
        html += "<a href='" + thisObj["reviews"]["link"] + "' target='_blank'>" + thisObj["reviews"]["content"] + "</a>";
    } else {
        html += "<p>reviews:  <span> no data </span></p>";
    }

    if(thisObj["description"] != ""){
        html += "<p>description:</p><br /><br /><p>" + thisObj["description"] + "</p>";
    } else {
        html += "<p>description:  <span> no data </span></p>";
    }

    Window1 = window.open("", "Window1", "width=800,height=1000,scrollbars=yes");
    Window1.document.writeln(html);
}

