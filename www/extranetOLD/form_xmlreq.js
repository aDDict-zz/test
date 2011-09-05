// xmlreq ------------------------------------------------------------
mx_request=new Array();
mx_request_c=0;
function xmlreq(url,fresp, obj) { 
    r_c=mx_request_c++; 
    mx_request[r_c] = new xmlhttp_request (url, fresp, r_c, obj); 
    return r_c; 
}
function xmlhttp_request (url, e, id, obj) {
    this.target = document; 
    var upr=url.split('?');
    this.url = upr[0]; 
    this.parameters = upr[1]; 
    this.id = id;
    this.obj=obj==undefined?this:obj;
    this.obj.event=e;
    this.xmlhttp = false; 
    this.method = "POST"; 
    this.doRequest(); 
}
xmlhttp_request.prototype.init_xmlhttp = function () {
    try { 
        this.xmlhttp = new ActiveXObject("Msxml2.XMLHTTP"); 
    } 
    catch (e) { 
        try { 
            this.xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); 
        } 
        catch (E) { 
            this.xmlhttp = false; 
        } 
    } 
    if (!this.xmlhttp && typeof XMLHttpRequest!='undefined') { 
        this.xmlhttp = new XMLHttpRequest(); 
    }
}
xmlhttp_request.prototype.doRequest = function () {
    if (!this.xmlhttp) { this.init_xmlhttp(); }
    var xworks=0;
    if (this.xmlhttp) {
        var o = this;
        xworks=1;
        try { o.xmlhttp.open(this.method, this.url, true); } catch(e) { xworks=0; }
        if (xworks==1) {
          o.xmlhttp.onreadystatechange = function() {
            if (o.xmlhttp.readyState==4) { 
              if (o.xmlhttp.status == 200) {
                o.response = o.xmlhttp.responseText;                 
                if (o.response) { o.obj.event(o.id,o); }
                } } }
            o.xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            o.xmlhttp.send(this.parameters);
        }
    }
}
