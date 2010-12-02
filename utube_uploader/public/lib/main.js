$(document).ready(function(){
    GlobalEventHandler.init();
});

var GlobalEventHandler = {};
/** beallitja a nevtereket, elinditja az initializalast */
GlobalEventHandler.init = function(){
    onYouTubePlayerReady = Flash.onYouTubePlayerReady;
    onPlayerStateChange = Flash.onPlayerStateChange;
    // primitiv megoldas, mindegyik lib betoltodeskor megnoveli az erteket 1-el, aztan ellenorzom a vegosszeget
    GlobalEventHandler.listener = 0;
    GlobalEventHandler.loadDatas();
}
/** ajax post a server fele az adatokert */
GlobalEventHandler.loadDatas = function(){
    $.post(
        "/datas",
        "id=" + cfg.videoId,
        GlobalEventHandler.onLoadDatas,
        "text"
    );
}
/** az ajax onLoadja */
GlobalEventHandler.onLoadDatas = function(resp){
    GlobalEventHandler.datas = eval("(" + resp + ")");
    if(typeof(GlobalEventHandler.datas) == "object"){
        // setinterval a GlobalEventHandler.loader-re
        timer.addToDEPO({"function" : GlobalEventHandler.loader, "interval" : 2});
        // initializalja a lulonbozo widgeteket
        Gmap.init();
        Flash.init();
        Tline.init();
    }
}
/** ellenorzi, hogy megvan e minden, siker eseten lelovi a setintervalt, valojaban nincs ra szukseg..... */
GlobalEventHandler.loader = function(){
    if(GlobalEventHandler.listener == 2){
        timer.deleteFromDEPO(GlobalEventHandler.loader);
        //GlobalEventHandler.setup();
    }
}
GlobalEventHandler.setup = function(){
}

var Flash = {};
/** swfobject init */
Flash.init = function(){
    Flash.currentEventIndex = -1;
    var flashvars = {};
    var params = {};
    params.allowScriptAccess = "always";
    params.wmode = "transparent";
    var attributes = {};
    attributes.id = "utubeVideo";
    attributes.name = "utubeVideo";
    swfobject.embedSWF(Flash.getSrcForEmbedSWF(cfg.videoId), "flash", "425", "356", "8", null, null, params, attributes);
}
Flash.getSrcForEmbedSWF = function(videoId){
    return "http://www.youtube.com/v/" + videoId + "?enablejsapi=1"
}
/**  az swf betoltodesekor fut le, beemeli a nevterbe a player referenciat, beallitja a player eventjeit */
Flash.onYouTubePlayerReady = function(){
    Flash.player = $("#utubeVideo").get(0);
    GlobalEventHandler.listener ++;
    Flash.player.addEventListener("onStateChange", "onPlayerStateChange");
    //Flash.player.playVideo();
}
/** ezt hivja a timer periodikusan, ha lejatszik a player, elkeri a playertol hogy hol tart */
Flash.currentTime = function(){
    var arrayIndex = Flash.getApproximateVal(Math.floor(Flash.player.getCurrentTime()));
    if(arrayIndex != -1){
        if(Flash.currentEventIndex != arrayIndex){
            //beallitja a lejatszaskor hogy milyen globalis eventnel tart a player eppen
            Flash.currentEventIndex = arrayIndex;
            // ha eppen valtozott a global event, akkor a flash hivja a tobbi widgetet
            Flash.doAction();
        }
    }
}
Flash.doAction = function(){
    // atrendezi a gmapet a flash aktualis eventje alapjan
    if(typeof(GlobalEventHandler.datas[Flash.currentEventIndex]["location"]) == "object"){
        Gmap.renderNewLocation(GlobalEventHandler.datas[Flash.currentEventIndex]["location"][1]);
    }
    // atrendezi a timeline-t a flash aktualis eventje alapjan
    Tline.init(Flash.currentEventIndex);
}
/**  a widgetek hivjak, atallitja a lejatszasi poziciot */
Flash.playerSeek = function(position){
    Flash.player.stopVideo();
    Flash.player.seekTo(position);
}
/** megallapitja hogy melyik event ido tartomanyahoz tartozik az aktualis player pozicio */
Flash.getApproximateVal = function(currentTime){
    var thisLength = GlobalEventHandler.datas.length;
    // gyorsitani szerettem volna azzal, hogy nem az egesz intervallumon iteralok, hanem elfelezem, nem ellenoriztem, hogy van e tenyleges gyorsulas
    var mid = Math.ceil(thisLength / 2);
    if(currentTime < GlobalEventHandler.datas[mid]["seek_from"]){
        for(var i = mid;i >= 0; i--){
            if(currentTime > GlobalEventHandler.datas[i]["seek_from"])
                return i;
        }
    } else if(currentTime > GlobalEventHandler.datas[mid]["seek_from"]){
        for(var i = thisLength - 1;i > mid; i--){
            if(currentTime > GlobalEventHandler.datas[i]["seek_from"])
                return i;
        }
    } else {
        return mid;
    }
    return -1;
}
/**  a player eventjere hookolas */
Flash.onPlayerStateChange = function(state){
    switch(state){
        case 1:
            Flash.addListener();
        break;
        case 2:
            Flash.removeListener();
        break;
    }
}
Flash.addListener = function(){
    timer.addToDEPO({"function" : Flash.currentTime, "interval" : 5});
}
Flash.removeListener = function(){
    if(timer.DEPO.length > 0)
        timer.deleteFromDEPO(Flash.currentTime);
}

var Gmap = {};
Gmap.init = function(){
    if(GBrowserIsCompatible()){
        Gmap.map = new GMap2(document.getElementById("map"));
        Gmap.map.addControl(new GLargeMapControl());
        Gmap.map.setCenter(new GLatLng(47.50, 19.06), 4);
        Gmap.setMarkers();
    }
}
Gmap.renderNewLocation = function(arr){
    Gmap.map.setCenter(new GLatLng(arr[0], arr[1]), arr[2]);
}
/** ezek nem pont setterek es getterek, csak majdnem */
Gmap.setMarkers = function(){
    Gmap.mgr = new MarkerManager(Gmap.map);
    Gmap.getMarkers();
    Gmap.mgr.addMarkers(Gmap.markers, 3);
    GlobalEventHandler.listener ++;
    Gmap.mgr.refresh();
}
Gmap.getMarkers = function(){
    Gmap.markers = [];
    var marker = {};
    for(var i in GlobalEventHandler.datas){
        if(typeof(GlobalEventHandler.datas[i]["location"]) == "object"){
            var latlng = new GLatLng(GlobalEventHandler.datas[i]["location"][1][0], GlobalEventHandler.datas[i]["location"][1][1]);
            marker[i] = new GMarker(latlng);
            marker[i].location = GlobalEventHandler.datas[i]["location"][0];
            marker[i].latlng = GlobalEventHandler.datas[i]["location"][1];
            marker[i].index = i;
            marker[i].seek = GlobalEventHandler.datas[i]["seek_from"];
            GEvent.bind(marker[i], "click", marker[i], function(){ Gmap.doAction(this); });
            Gmap.markers.push(marker[i]);
        }
    }
}
/**  a map hivja a widgeteket */
Gmap.doAction = function(obj){
    Flash.playerSeek(obj.seek);
    Tline.init(obj.index);
}

var Tline = {};
/** ez a kezdeti init, illetve, ha valtozik a global event, akkor is ez hivodik, gyakorlatilag ujrarendelem folyamatosan a timeline-t */
Tline.init = function(index){

    if(index == undefined)
        index = 0;

    var eventSource = new Timeline.DefaultEventSource();
    var d = Timeline.DateTime.parseGregorianDateTime(GlobalEventHandler.datas[index]["start"]);
    Tline.resizeTimerID = null;
    var bandInfos = [
        Timeline.createBandInfo({
            eventSource:    eventSource,
            width:          "100%",
            intervalUnit:   Timeline.DateTime.DAY,
            date:           d,
            intervalPixels: 100
        })
    ];
    tl = Timeline.create(document.getElementById("timeline"), bandInfos);
    // az eredeti loadJSON method felulirasa az attracskolt eredetivel
    eventSource.loadJSON = Tline.loadJSON;
    eventSource.loadJSON(Tline.getEventObj());

    if (Tline.resizeTimerID == null) {
        Tline.resizeTimerID = window.setTimeout(function() {
            Tline.resizeTimerID = null;
            tl.layout();
        }, 500);
    }
}

/** ez a simile timeline forraskodja kicsit megtracskolva */
Tline.loadJSON = function (H/*, B*/) {
    //var D = this._getBaseURL(B);
    var J = false;
    if (H && H.events) {
        var I = "wikiURL" in H ? H.wikiURL : null;
        var K = "wikiSection" in H ? H.wikiSection : null;
        var F = "dateTimeFormat" in H ? H.dateTimeFormat : null;
        var E = this._events.getUnit().getParser(F);
        for (var G = 0; G < H.events.length; G++) {
            var A = H.events[G];
            var C = A.isDuration || A.durationEvent != null && !A.durationEvent;
            var L = new(Timeline.DefaultEventSource.Event)({
                id: "id" in A ? A.id : undefined,
                start: E(A.start),
                end: E(A.end),
                latestStart: E(A.latestStart),
                earliestEnd: E(A.earliestEnd),
                instant: C,
                text: A.title,
                description: A.description,
                //image: this._resolveRelativeURL(A.image, D),
                link: A.link,
                //icon: this._resolveRelativeURL(A.icon, D),
                color: A.color,
                textColor: A.textColor,
                hoverText: A.hoverText,
                classname: A.classname,
                tapeImage: A.tapeImage,
                tapeRepeat: A.tapeRepeat,
                caption: A.caption,
                eventID: A.eventID,
                trackNum: A.trackNum
            });
            L._obj = A;
            L.getProperty = function (M) {
                return this._obj[M];
            };
            L.setWikiInfo(I, K);
            this._events.add(L);
            J = true;
        }
    }
    if (J) {
        this._fire("onAddMany", []);
    }
}
/** beallitja a timeline idopontjait */
Tline.getEventObj = function(){
    var obj = {};
    obj.events = [];
    for(var i in GlobalEventHandler.datas){
        obj.events[i] = {};
        var arr = GlobalEventHandler.datas[i]["text"].split(" - ");
        obj.events[i].start = GlobalEventHandler.datas[i]["start"];
        obj.events[i].end = GlobalEventHandler.datas[i]["end"];
        obj.events[i].title = arr[0];
        obj.events[i].description = arr[1];
        obj.events[i].link = "javascript:Tline.doAction(" + i + ");";
    }
    return obj;
}
/**  a timeline hivja a widgeteket */
Tline.doAction = function(index){
    Flash.playerSeek(GlobalEventHandler.datas[index]["seek_from"]);
    if(typeof(GlobalEventHandler.datas[index]["location"]) == "object"){
        Gmap.renderNewLocation(GlobalEventHandler.datas[index]["location"][1]);
    }
}

/** singleton, egyszerre tobb timingot illetve fuggvenyt kepes kezelni */
var timer = new function(){
	this.constructor = null;
	this.DEPO = [];
	this.CASH = 0;
	this.addToDEPO = _addToDEPO;
	this.counter = _counter;
	this.listener = _listener;
	this.deleteFromDEPO = _deleteFromDEPO;
	this.stopListener = _stopListener;
	this.intervalId;
	function _counter(){
		timer.intervalId = setInterval(this.listener, 100);
	}
	function _listener(){
		if(timer.CASH == 9999){
			timer.CASH = 0;
		} else {
			timer.CASH++;
		}
		var thisRemainder;
		var thisFunction;
		var thisObject;
		var objLength = timer.DEPO.length;
		if(objLength > 0){
		    for(var i = 0; i < objLength; i++){
			    thisRemainder = timer.CASH % timer.DEPO[i]["interval"];
			    if(thisRemainder == 0){
				    timer.DEPO[i]["function"]();
			    }
		    }
		}

	}
	function _stopListener(){
		clearInterval(timer.intervalId);
	}
	function _addToDEPO(thisArray){
		if(timer.DEPO.length == 0){
			this.counter();
		}
		var listener = 0;
		var objLength = timer.DEPO.length;
		for(var i = 0; i < objLength; i++){
			if(timer.DEPO[i]["function"] == thisArray["function"]){
				listener++; break;
			}
		}
		if(listener == 0){
			this.DEPO[this.DEPO.length] = {
				"function" : thisArray['function'],
				"interval" : thisArray['interval']
			};
		}
	}
	function _deleteFromDEPO( thisFunction ){
		var objLength = timer.DEPO.length - 1;
		for(var i = objLength; i >= 0; i--){
			if(timer.DEPO[i]["function"] == thisFunction){
				timer.DEPO.splice(i, 1);
			}
		}
		if(timer.DEPO.length == 0){
			timer.stopListener();
		}
	}
}

