function embededflash (azonosito,fajlnev,meret_x,meret_y,hatter,megjelenites,valtozok,basedir) {
if (basedir == undefined) basedir = 0;
document.write('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="'+meret_x+'" height="'+meret_y+'" id="'+azonosito+'" align="middle" />');
document.write('<param name="allowScriptAccess" value="sameDomain" />');
document.write('<param name="movie" value="'+fajlnev+'" />');
document.write('<param name="FlashVars" value="'+valtozok+'" />');
document.write('<param name="quality" value="high" />');
document.write('<param name="bgcolor" value="'+hatter+'" />');
document.write('<param name="menu" value="false" />');
document.write('<param name="wmode" value="'+megjelenites+'" />');
if (basedir) document.write('<param name="base" value="'+basedir+'" />');
document.write('<embed src="'+fajlnev+'" FlashVars="'+valtozok+'" width="'+meret_x+'" height="'+meret_y+'" align="middle" quality="high" bgcolor="'+hatter+'" menu="false" wmode="'+megjelenites+'"'+(basedir ? 'base="'+basedir+'"' : '')+' allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />');
document.write('</object>');
}
