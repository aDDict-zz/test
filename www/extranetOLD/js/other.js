function change_id() {
    var d;
    for (var i = 0, n = fe.length; i < n; i++) {
        temp=fe[i].split("_");
        --temp[0];
        d=document.getElementById(temp[0]);
        if (d!=null)d.id=temp[0]+"_"+temp[1];
    }
}

function checkEnter(e){
var characterCode;

if(e && e.which){
e = e
characterCode = e.which 
}
else{
e = event
characterCode = e.keyCode
}

if(characterCode == 13){ 
document.forms[0].submit()
return false
}
else{
return true
}

}