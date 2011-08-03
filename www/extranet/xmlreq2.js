function find_object(id) {
    if (document.getElementById) { if (!(document.getElementById(id))) { return false; } return document.getElementById(id); } else return false;
}

function xmlhttp_request (url, e, fparms, id) {
    this.target = document;
    this.url = url;
    this.id = id;
    this.fparms = fparms;
    this.event = e;
    this.xmlhttp = false;
    this.method = "GET";
    this.doRequest();
}
xmlhttp_request.prototype.init_xmlhttp = function () {
    try {
        this.xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            this.xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            this.xmlhttp = false;
        }
    }
    if (!this.xmlhttp && typeof XMLHttpRequest!='undefined') {
        this.xmlhttp = new XMLHttpRequest();
    }
}
xmlhttp_request.prototype.doRequest = function () {
    if (!this.xmlhttp) {
        this.init_xmlhttp();
    }
    if (this.xmlhttp) {
        var o = this;
        o.xmlhttp.open(this.method, this.url, true);
        o.xmlhttp.onreadystatechange= function() {
            if (o.xmlhttp.readyState==4) {
                 o.response = o.xmlhttp.responseText;
                 o.event(o.id,o.fparms);
            }
        }
        o.xmlhttp.send(null);
    }
}

v_request=new Array();
v_request_c=0;
function xmlreq(url,fresp,fparms,r_c) {
    if (r_c==-1) {r_c=v_request_c++;}
    v_request[r_c] = new xmlhttp_request (url, fresp, fparms, r_c);
}

function item_send (group_id,xmlreq_id) {
    xmlreq('xmlreq.php?group_id='+group_id+'&xmlreq_id='+xmlreq_id,item_resp,xmlreq_id,-1);
}
function item_resend (rid) {
    xmlreq(v_request[rid].url,item_resp,v_request[rid].fparms,rid);
}
function item_resp (rid) {
    var msg; var re=v_request[rid].response.split('|'); var lg='';
    if (re[0]!='*') { msg='Script error'; }
    else {
        eval ('msg=lrsc_'+re[1]);
        if (re[1]=='processing') { msg+=' '+re[2]+'%'; }
        if (re[4].length) { lg=" <a href='safelog.php?xmlreq_id="+re[4]+"'>logfile</a>"; }
        if (re[3].length) { if (ob=find_object('reqerr'+v_request[rid].fparms)) { ob.innerHTML='<span class=szoveg>'+re[3]+lg+'</span>'; } }
    }
    if (ob=find_object('reqdiv'+v_request[rid].fparms)) { ob.innerHTML='<span class=szoveg>'+msg+'</span>'; }
    if (re[1]=='processing' || re[1]=='queued') { setTimeout("item_resend("+rid+")",3000); }
}


function log_item_send (group,message_id,xmlreq_id) {
    xmlreq('xmllog.php?message_id='+message_id+'&group='+group+'&xmlreq_id='+xmlreq_id,log_item_resp,xmlreq_id,-1);
}
function log_item_resend (rid) {
    xmlreq(v_request[rid].url,log_item_resp,v_request[rid].fparms,rid);
}
function log_item_resp (rid) {
    var msg=''; var re=v_request[rid].response.split('|'); var lg='';
    if (re[0]!='*') { msg='Script error'; }
    else { msg=re[2]; }
    if (ob=find_object('reqdiv'+v_request[rid].fparms)) { ob.innerHTML='<span class=szoveg>'+msg+'</span>'; }
    if (re[1]) { setTimeout("log_item_resend("+rid+")",60000); }
}

