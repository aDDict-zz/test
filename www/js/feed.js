/*  Feeds begin */
function hideFeaturedFeeds(obj){
	obj.onclick = function(){showFeaturedFeeds(obj);return false;};	
	obj.parentNode.childNodes[0].src = 'i/ha.gif';
	obj.parentNode.removeChild(document.getElementById('featuredFeeds'));
}

function showFeaturedFeeds(obj){
	obj.onclick = function(){hideFeaturedFeeds(obj);return false;};
	obj.parentNode.childNodes[0].src = 'i/va.gif';
	var show = function(r){
		var ul = document.createElement('ul');
		obj.parentNode.appendChild(ul);
		ul.id = "featuredFeeds";
		ul.style.marginLeft = "15px";
		ul.innerHTML = r.responseText;	
	}
	var pars = "action=get_featured_feeds";
	var url = "ajax/feed.php";
	Ajax.send(url, "POST", pars, show, null);	
}

function hideMyFeeds(obj){
	obj.onclick = function(){showMyFeeds(obj);return false;};	
	obj.parentNode.childNodes[0].src = 'i/ha.gif';
	obj.parentNode.removeChild(document.getElementById('myFeeds'));
}

function showMyFeeds(obj){
    if (obj == undefined) obj = document.getElementById('show_my_feeds');
    if (obj) {
        obj.onclick = function(){hideMyFeeds(obj);return false;};
        obj.parentNode.childNodes[0].src = 'i/va.gif';
        var o;
        if (o = document.getElementById('myFeeds')) {
            obj.parentNode.removeChild(o);
        }
        var show = function(r){
            var ul = document.createElement('ul');
            obj.parentNode.appendChild(ul);
            ul.id = "myFeeds";
            ul.style.marginLeft = "15px";
            ul.innerHTML = r.responseText;	
        }
        var pars = "action=get_my_feeds";
        var url = "ajax/feed.php";
        Ajax.send(url, "POST", pars, show, null);		
    }
}



function showFeedCategories(r){
	var feedCategories = document.getElementById('feedDirectory');	
	feedCategories.style.display = "block";
	feedCategories.innerHTML = r.responseText;
	
}
closeFeedCategories = function(categoriesLink){
	var li = categoriesLink.parentNode;
	var img = li.childNodes[0];		
	img.src = 'i/ha.gif';	
	var ul = document.getElementById('feedDirectory');
	ul.style.display = "none";
	
	categoriesLink.onclick = function(){getFeedCategories(this)};
}
function getFeedCategories(categoriesLink){
	var li = categoriesLink.parentNode;
	var img = li.childNodes[0];
	img.src = 'i/va.gif';
	categoriesLink.onclick = function(){closeFeedCategories(this)};	
	
	var pars = "action=get_feed_categories";
	var url = "ajax/feed.php";
	Ajax.send(url, "POST", pars, showFeedCategories, null);	
}

function showFeedsByCat(r, li){
	li = li.parentNode;
	if(li.childNodes.length<4){
		var ul = document.createElement('ul');
		li.appendChild(ul);
	}else{	
		var ul = li.childNodes[1];		
	}
	ul.style.marginLeft = "15px";
	ul.innerHTML = r.responseText;	
}
closeFeedByCats = function(feedCategoryLink, catId){
	var li = feedCategoryLink.parentNode;
	var img = li.childNodes[0];
	img.src = 'i/ha.gif';
	if(IE) var ul = li.childNodes[3];
		else var ul = li.childNodes[3];
	li.removeChild(ul);
	
	feedCategoryLink.onclick = function(){getFeedsByCat(feedCategoryLink, catId)};
}

function getFeedsByCat(feedCategoryLink, catId){
	var li = feedCategoryLink.parentNode;
	var img = li.childNodes[0];
	img.src = 'i/va.gif';
	feedCategoryLink.onclick = function(){closeFeedByCats(feedCategoryLink, catId)};
	
	var pars = "action=get_feeds_by_cat&feed_cat_id=" + catId;
	var url = "ajax/feed.php";
	Ajax.send(url, "POST", pars, showFeedsByCat, feedCategoryLink);	
}

function showNewFeedBox(r, data){	
	if(activeDefaultTab!=null)	var mainContent = document.getElementById('dfp_' + activeDefaultTab.id.substring(3, activeDefaultTab.id.length));		
			else var mainContent = document.getElementById('usp_' + activeTab.id.substring(3, activeTab.id.length));
    function findColDiv(row, col) {
        var cn = mainContent.rows[row].cells[col].firstChild;
        while(cn && (!cn.id || !cn.id.match(/^\d+_\d+$/))) cn = cn.nextSibling;
        return cn;

    }
	if(IE){
		mainContent = mainContent.childNodes[0];
        var cell_idx = 0;
            var firstCol = mainContent.rows[0].cells[cell_idx].childNodes[0];
		var before = firstCol.childNodes[0];
	}else{	
		mainContent = mainContent.childNodes[1];
        var cell_idx = 0;
        var cc = 1000;
        var firstCol = null
        for (var i=0;i<3;i++) {
            var cn = findColDiv(0, i);
            if (cn && (cn.childNodes.length < cc)) {
                cc = cn.childNodes.length;
                cell_idx = i;
                firstCol = cn;
            }
        }
        if (firstCol) {
            firstCol.childNodes.firstChild;
            before = firstCol.firstChild;
            while (before && (before.nodeName != 'DIV')) before = before.nextSibling;
        } else {//regi kod
            if(mainContent.rows[0].cells[cell_idx].childNodes.length==1){
                var firstCol = mainContent.rows[0].cells[cell_idx].childNodes[0];						
                var before = null;
            }else{
                var firstCol = mainContent.rows[0].cells[cell_idx].childNodes[1];
                var before = firstCol.childNodes[1];
            }
        }
	}
	var box = document.createElement('div');
	box.className = 'box';	
	box.innerHTML = r.responseText;
	box.setAttribute('alt', data['maxBoxId']);
	
	if(before)	firstCol.insertBefore(box, before);
		else firstCol.appendChild(box);	
		
	if(activeDefaultTab!=null) var pageId = activeDefaultTab.id.substring(3, activeDefaultTab.id.length);	
				else var pageId = activeTab.id.substring(3, activeTab.id.length);	
	var objBox = new Box(box, data['feedName'], encodeURIComponent(data['feedUrl']), 0, 10, 1, 2, 1, 1, 1, pageId)
    objBox.dragEnd(pageId);
}

function addNewFeedBox( feedName, feedUrl, feedLink, feedId, general){
	maxBoxId++;
	var data = {
		'feedName' : feedName,
		'feedUrl'  : feedUrl,
		'feedLink' : feedLink, 		
		'maxBoxId' : maxBoxId
	};
	if(activeTab)	var pars = "action=add_new_rss_box" + "&feed_url=" + encodeURIComponent(feedUrl) + "&feed_name=" + encodeURIComponent(feedName) + "&feed_link=" + encodeURIComponent(feedLink) + '&feed_id=' + feedId + '&general=' + general + '&page_id=' + activeTab.id.substring(3, activeTab.id.length);	
		else var pars = "action=add_new_rss_box" + "&feed_url=" + encodeURIComponent(feedUrl) + "&feed_name=" + encodeURIComponent(feedName) + "&feed_link=" + encodeURIComponent(feedLink) + '&feed_id=' + feedId + '&general=' + general + '&page_id=' + activeDefaultTab.id.substring(3, activeDefaultTab.id.length) + '&default=1';
	var url = "ajax/page.php";
	Ajax.send(url, "POST", pars, showNewFeedBox, data);		
}
var newFeedBoxOpen = false;
function showAddNewFeedBox(l){
	if(!newFeedBoxOpen){
		newFeedBoxOpen  = true;
		
		var show = function(r){			
			var div = document.createElement('div');
			div.id = "newFeedBox";
			div.style.width = "353px";
			div.style.position = "absolute";
			div.style.zIndex = 100;
			div.style.top = '5px';
			div.style.left = '220px';
			document.body.appendChild(div);
			div.innerHTML = r.responseText;			
		}
		var pars = "action=show_add_new_feed_box";
		var url = "ajax/feed.php";
		Ajax.send(url, "POST", pars, show, null);
	}
}

function removeNewFeedBox(l){
	var newFeedBox = document.getElementById('newFeedBox');
	document.body.removeChild(newFeedBox);
	newFeedBoxOpen = false;
}

function addNewFeedBoxByFeed(feedURL){
	if(feedURL!=""){
		var feedVerifyProgress = document.getElementById('feedVerifyProgress');
		feedVerifyProgress.innerHTML = lang['checkingFeed'];
		
		var show = function(r){				
			if(r.responseText!=""){
				feedVerifyProgress.innerHTML = '';
				var data = new Array();			
				eval(r.responseText);			
				addNewFeedBox(data['feedName'], data['feedUrl'], data['link'], data['feedId'], 0);				
				feedVerifyProgress.innerHTML = lang['feedAdded'];
                showMyFeeds();
			}else feedVerifyProgress.innerHTML = lang['emptyFeedURL'];
		}
		
		
		var pars = "action=get_feed_header&feed=" + encodeURIComponent(feedURL);
		var url = "ajax/read_rss.php";
		Ajax.send(url, "POST", pars, show, null);
	
	}else{
		alert(lang['emptyFeedURL']);
	}
}
function showManager(){
	var manager = document.getElementById('manager');
	manager.style.display = 'block';
}

function hideManager(){
	var manager = document.getElementById('manager');
	manager.style.display = 'none';	
	if(newFeedBoxOpen){
		var newFeedBox = document.getElementById('newFeedBox');
		document.body.removeChild(newFeedBox);
		newFeedBoxOpen = false;
	}
}
var feedOverId = 0;
var prevFeedOverId = 0;
var feedAction = 0;
function myFeedOver(id, out) {
    if (out == undefined) out = 0;
    if (prevFeedOverId != feedOverId) feedAction = 0;
    prevFeedOverId = id;
    feedOverId = out ? 0 : id;
    if (out) {
        setTimeout('myFeedOut()', 300);
    } else {
        var f = document.getElementById('myfeed_'+id);
        var fa = document.getElementById('myfeedlink_'+id);
        var m = document.getElementById('manager');
        var a = document.getElementById('feed_actions');
        var al = document.getElementById('feed_action_links');
        var inp = document.getElementById('renameFeedField');
        if (f && m && a && al && inp) {
            a.style.display = 'block';
            var x = findPosX(m) + 180;
            var y = findPosY(f) - 3;
            setPosition(a, x, y);
            setPosition(al, x+10,y);
            inp.value = fa.innerHTML;
        }
    }
}
function myFeedOut() {
    if (!feedOverId) {
        prevFeedOverId = 0;
        var a = document.getElementById('feed_actions');
        var al = document.getElementById('feed_action_links');
        if (a && al) {
            a.style.display = 'none';
            al.style.display = 'none';
            feedAction = 0;
        }
    }
}

function myFeedActionOver(idx) {

    if (prevFeedOverId && !feedOverId) feedOverId = prevFeedOverId
    if (feedOverId) {
        var a = document.getElementById('feed_actions');
        if (a) a.style.display = idx ? 'none' : 'block';
        var al = document.getElementById('feed_action_links');
        if (al) al.style.display = 'block';
        var r = document.getElementById('feed_action_rename');
        if (r) r.style.display= feedAction == 2 ? 'block' : 'none';
        var d = document.getElementById('feed_action_delete');
        if (d) d.style.display= feedAction == 3 ? 'block' : 'none';
        var r = document.getElementById('feed_action_response');
        if (r) r.style.display= 'none';
    }

}
function myFeedActionOut() {
    if (feedOverId) {
        myFeedOver(feedOverId, 1);
    }
}
function showFeedRename() {
    var r = document.getElementById('feed_action_rename');
    if (r) r.style.display='block';
    var d = document.getElementById('feed_action_delete');
    if (d) d.style.display='none';
    feedAction = 2;
}
function showFeedDelete() {
    var r = document.getElementById('feed_action_rename');
    if (r) r.style.display='none';
    var d = document.getElementById('feed_action_delete');
    if (d) d.style.display='block';
    feedAction = 3;
}
function renameFeed(name) {
    if (feedOverId) {
        var show = function(r){
            feedAction = 0;
            var a = r.responseText.split('__'); 
            var d = document.getElementById('feed_action_response');
            if (d) {
                d.innerHTML = a[1] == 'ok' ? 'az átnevezés megtörtént': a[1];
                d.style.display= 'block';
                setTimeout('hideDiv("feed_action_response")', 3000);
            }
            if (a[1] == 'ok') {
                var f = document.getElementById('myfeedlink_'+a[0]);
                if (f) {
                    f.innerHTML = a[2];
                    var oncl = f.getAttribute('onclick');
                    oncl = oncl.replace(/addNewFeedBox\('[^']+'/i, "addNewFeedBox('"+a[2]+"'");
                    f.setAttribute('onclick', oncl);
                }
            }
        }
    	var pars = 'action=rename_feed&feed_id='+feedOverId+'&name='+name;
	    var url = "ajax/feed.php";
    	Ajax.send(url, "POST", pars, show, null);	
    }
    
}

function hideDiv(id) {
    var d = document.getElementById(id);
    if (d) d.style.display= 'none';
}


function deleteFeed() {
    if (feedOverId) {
        var show = function(r){
            feedAction = 0;
            var f = document.getElementById('myfeed_'+r.responseText);
            if (f) {
                feedOverId = 0;
                prevFeedOverId = 0;
                var el = document.getElementById('feed_action_links');
                if (el) el.style.display = 'none';
                el = document.getElementById('feed_action_delete');
                if (el) el.style.display = 'none';
                el = document.getElementById('feed_action_response');
                if (el) el.style.display= 'none';
                var p = f.parentNode; 
                p.removeChild(f);
            }
        }
    	var pars = 'action=delete_feed&feed_id='+feedOverId;
	    var url = "ajax/feed.php";
    	Ajax.send(url, "POST", pars, show, null);	
    }
    
}

/*  Feeds end */
