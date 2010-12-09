var main = {};

main.currency = {
    "Amazon" : "$",
    "Bookline" : "Ft"
};

main.init = function(){
    main.submitState = 1;
    $("#submit").click(function(){
        if(main.submitState == 1 && $("#bookStores").val() != ""){
            main.submitState = 0;
            main.bookStore = $("#bookStores").val()
            $.post(
                "/search",
                "author=" + $("#author").val() + "&title=" + $("#title").val() + "&bookStores=" + $("#bookStores").val(),
                function(resp){
                    if(resp != null && typeof(resp) == "object" && resp.length > 0){
                        main.table = [];
                        main.table = resp;
                        main.setup();
                    } else {
                        main.submitState = 1;
                        $("#content").html("");
                        alert("nincs talalat, vagy valamilyen hiba tortent ");
                    }
                },
                "json"
            );
        } else {
            main.submitState = 1;
            alert("egy request mar folyamatban van, vagy nincs kivalasztva konyvesbolt");
        }
        return false;
    })
}

main.setup = function(){
    main.table.noImage = "http://g-ecx.images-amazon.com/images/G/01/x-site/icons/no-img-sm._AA75_.gif";
    $("#content").setTemplateElement("template");
    $("#content").processTemplate(main.table);
    $("#results").ariaSorTable({
        rowsToShow: 10,
        pager: false
    });
    main.submitState = 1;
}

main.wiki = function(obj){
    $.post(
        "/searchInWiki",
        "type=" + main.getHashByQueryStr($(obj).attr("rel"))["type"] + "&key=" + unescape(main.getHashByQueryStr($(obj).attr("rel"))["key"]),
        function(resp){
            //console.log(resp);
        },
        "json"
    );
    return false;
}

main.getHashByQueryStr = function(qstr){
    var hash = {};
    var arr = qstr.split("&");
    for(var i in arr){
        hash[arr[i].split("=")[0]] = arr[i].split("=")[1];
    }
    return hash;
}

$(document).ready(main.init);

