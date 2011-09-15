WEBAUDIT=function() {
    
  this.WACID=null;
  this.WACIDName="WACID";
  
  
  this.getCookie=function(name)  {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++)
    {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
  }
  
  this.setCookie=function(name,value,topDomain) {
    var date = new Date(2020,12,31,23,59,59);
    var expires = "; expires="+date.toGMTString();
    document.cookie = name+"="+value+expires+"; path=/; domain=" + topDomain;  
  }
  
  this.generateID=function(splitter) {
    var sp=(splitter) ? splitter : 'A';
    var now=new Date();
    return Date.parse(now.toGMTString()) + sp + Math.floor(Math.random()*1000000000);
  }
  
  this.getTopDomain=function(fullDomain) {
    var darabok=fullDomain.split('.');
    return darabok[(darabok.length-2)] + '.' + darabok[(darabok.length-1)];
  }
  
  this.getDomain=function(url) {
    var urlDarabok=url.split('/');
    return urlDarabok[2];
  }
  
  this.WACID=this.getCookie(this.WACIDName);
}

var wa=new WEBAUDIT();
var felbontas = "";
var same =  Math.floor(Math.random()*1000000);

if(wa.WACID==null)
{
  wa.WACID=wa.generateID('A');
  wa.setCookie(wa.WACIDName,wa.WACID,wa.getTopDomain(wa.getDomain(document.URL)));
}

same = same + "@welid=" + wa.WACID;
if(screen) felbontas='@felbontas='+screen.width+'x'+screen.height;
same = same + felbontas;



