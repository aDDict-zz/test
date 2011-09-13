<?
$color=$_GET["color"];
$variable=$_GET["variable"];
?>
<HTML><HEAD><TITLE>Colour picker</TITLE><SCRIPT language="javascript">
var color='#000000';
function r(hval)
{
//					document.bgColor=hval;
		var e = document.getElementById("colorblock");
		e.style.backgroundColor = '#'+hval;
		document.f.c.value=hval;
		color = hval;
//	}
}

function c()
{
    var e = document.getElementById("colorblock");
    e.style.backgroundColor = "#<?=$color?>";
    document.f.c.value = "<?=$color?>";
    color = "<?=$color?>";
}


function l()
{
	window.opener.document.cssform.<?=$variable?>.value=color;
    var e = window.opener.document.getElementById("td_<?=$variable?>");
    e.style.backgroundColor = '#'+color;
	window.close();
}
</SCRIPT></HEAD><BODY onLoad='c();'>
<center>
<FORM name="f">
<table border=0 name="z"><tr>
<td>#<INPUT type="text" name="c" size=7></td>
<td id="colorblock" width=120></td>
</table>
</FORM>
<table border=0>
<tr>
        <td bgcolor="#000000"><a href="JavaScript:l()"
onmouseover="r('000000'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#000033"><a href="JavaScript:l()"
onmouseover="r('000033'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#000066"><a href="JavaScript:l()"
onmouseover="r('000066'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#000099"><a href="JavaScript:l()"
onmouseover="r('000099'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#0000cc"><a href="JavaScript:l()"
onmouseover="r('0000cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#0000ff"><a href="JavaScript:l()"
onmouseover="r('0000ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#006600"><a href="JavaScript:l()"
onmouseover="r('006600'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#006633"><a href="JavaScript:l()"
onmouseover="r('006633'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#006666"><a href="JavaScript:l()"
onmouseover="r('006666'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#006699"><a href="JavaScript:l()"
onmouseover="r('006699'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#0066cc"><a href="JavaScript:l()"
onmouseover="r('0066cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#0066ff"><a href="JavaScript:l()"
onmouseover="r('0066ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#00cc00"><a href="JavaScript:l()"
onmouseover="r('00cc00'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#00cc33"><a href="JavaScript:l()"
onmouseover="r('00cc33'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#00cc66"><a href="JavaScript:l()"
onmouseover="r('00cc66'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#00cc99"><a href="JavaScript:l()"
onmouseover="r('00cc99'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#00cccc"><a href="JavaScript:l()"
onmouseover="r('00cccc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#00ccff"><a href="JavaScript:l()"
onmouseover="r('00ccff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
</tr>
<tr>
        <td bgcolor="#003300"><a href="JavaScript:l()"
onmouseover="r('003300'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#003333"><a href="JavaScript:l()"
onmouseover="r('003333'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#003366"><a href="JavaScript:l()"
onmouseover="r('003366'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#003399"><a href="JavaScript:l()"
onmouseover="r('003399'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#0033cc"><a href="JavaScript:l()"
onmouseover="r('0033cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#0033ff"><a href="JavaScript:l()"
onmouseover="r('0033ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#009900"><a href="JavaScript:l()"
onmouseover="r('009900'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#009933"><a href="JavaScript:l()"
onmouseover="r('009933'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#009966"><a href="JavaScript:l()"
onmouseover="r('009966'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#009999"><a href="JavaScript:l()"
onmouseover="r('009999'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#0099cc"><a href="JavaScript:l()"
onmouseover="r('0099cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#0099ff"><a href="JavaScript:l()"
onmouseover="r('0099ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#00ff00"><a href="JavaScript:l()"
onmouseover="r('00ff00'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#00ff33"><a href="JavaScript:l()"
onmouseover="r('00ff33'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#00ff66"><a href="JavaScript:l()"
onmouseover="r('00ff66'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#00ff99"><a href="JavaScript:l()"
onmouseover="r('00ff99'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#00ffcc"><a href="JavaScript:l()"
onmouseover="r('00ffcc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#00ffff"><a href="JavaScript:l()"
onmouseover="r('00ffff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
</tr>
<tr>
        <td bgcolor="#330000"><a href="JavaScript:l()"
onmouseover="r('330000'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#330033"><a href="JavaScript:l()"
onmouseover="r('330033'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#330066"><a href="JavaScript:l()"
onmouseover="r('330066'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#330099"><a href="JavaScript:l()"
onmouseover="r('330099'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#3300cc"><a href="JavaScript:l()"
onmouseover="r('3300cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#3300ff"><a href="JavaScript:l()"
onmouseover="r('3300ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#336600"><a href="JavaScript:l()"
onmouseover="r('336600'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#336633"><a href="JavaScript:l()"
onmouseover="r('336633'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#336666"><a href="JavaScript:l()"
onmouseover="r('336666'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="$_MX_var->main_table_border_color"><a href="JavaScript:l()"
onmouseover="r('336699'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#3366cc"><a href="JavaScript:l()"
onmouseover="r('3366cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#3366ff"><a href="JavaScript:l()"
onmouseover="r('3366ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#33cc00"><a href="JavaScript:l()"
onmouseover="r('33cc00'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#33cc33"><a href="JavaScript:l()"
onmouseover="r('33cc33'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#33cc66"><a href="JavaScript:l()"
onmouseover="r('33cc66'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#33cc99"><a href="JavaScript:l()"
onmouseover="r('33cc99'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#33cccc"><a href="JavaScript:l()"
onmouseover="r('33cccc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#33ccff"><a href="JavaScript:l()"
onmouseover="r('33ccff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
</tr>
<tr>
        <td bgcolor="#333300"><a href="JavaScript:l()"
onmouseover="r('333300'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#333333"><a href="JavaScript:l()"
onmouseover="r('333333'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#333366"><a href="JavaScript:l()"
onmouseover="r('333366'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#333399"><a href="JavaScript:l()"
onmouseover="r('333399'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#3333cc"><a href="JavaScript:l()"
onmouseover="r('3333cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#3333ff"><a href="JavaScript:l()"
onmouseover="r('3333ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#339900"><a href="JavaScript:l()"
onmouseover="r('339900'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#339933"><a href="JavaScript:l()"
onmouseover="r('339933'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#339966"><a href="JavaScript:l()"
onmouseover="r('339966'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#339999"><a href="JavaScript:l()"
onmouseover="r('339999'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#3399cc"><a href="JavaScript:l()"
onmouseover="r('3399cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#3399ff"><a href="JavaScript:l()"
onmouseover="r('3399ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#33ff00"><a href="JavaScript:l()"
onmouseover="r('33ff00'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#33ff33"><a href="JavaScript:l()"
onmouseover="r('33ff33'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#33ff66"><a href="JavaScript:l()"
onmouseover="r('33ff66'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#33ff99"><a href="JavaScript:l()"
onmouseover="r('33ff99'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#33ffcc"><a href="JavaScript:l()"
onmouseover="r('33ffcc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#33ffff"><a href="JavaScript:l()"
onmouseover="r('33ffff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
</tr>

<tr>
        <td bgcolor="#660000"><a href="JavaScript:l()"
onmouseover="r('660000'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#660033"><a href="JavaScript:l()"
onmouseover="r('660033'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#660066"><a href="JavaScript:l()"
onmouseover="r('660066'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#660099"><a href="JavaScript:l()"
onmouseover="r('660099'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#6600cc"><a href="JavaScript:l()"
onmouseover="r('6600cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#6600ff"><a href="JavaScript:l()"
onmouseover="r('6600ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#666600"><a href="JavaScript:l()"
onmouseover="r('666600'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#666633"><a href="JavaScript:l()"
onmouseover="r('666633'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#666666"><a href="JavaScript:l()"
onmouseover="r('666666'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#666699"><a href="JavaScript:l()"
onmouseover="r('666699'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#6666cc"><a href="JavaScript:l()"
onmouseover="r('6666cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#6666ff"><a href="JavaScript:l()"
onmouseover="r('6666ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#66cc00"><a href="JavaScript:l()"
onmouseover="r('66cc00'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#66cc33"><a href="JavaScript:l()"
onmouseover="r('66cc33'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#66cc66"><a href="JavaScript:l()"
onmouseover="r('66cc66'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#66cc99"><a href="JavaScript:l()"
onmouseover="r('66cc99'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#66cccc"><a href="JavaScript:l()"
onmouseover="r('66cccc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#66ccff"><a href="JavaScript:l()"
onmouseover="r('66ccff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
</tr>
<tr>
        <td bgcolor="#663300"><a href="JavaScript:l()"
onmouseover="r('663300'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#663333"><a href="JavaScript:l()"
onmouseover="r('663333'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#663366"><a href="JavaScript:l()"
onmouseover="r('663366'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#663399"><a href="JavaScript:l()"
onmouseover="r('663399'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#6633cc"><a href="JavaScript:l()"
onmouseover="r('6633cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#6633ff"><a href="JavaScript:l()"
onmouseover="r('6633ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#669900"><a href="JavaScript:l()"
onmouseover="r('669900'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#669933"><a href="JavaScript:l()"
onmouseover="r('669933'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#669966"><a href="JavaScript:l()"
onmouseover="r('669966'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#669999"><a href="JavaScript:l()"
onmouseover="r('669999'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#6699cc"><a href="JavaScript:l()"
onmouseover="r('6699cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#6699ff"><a href="JavaScript:l()"
onmouseover="r('6699ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#66ff00"><a href="JavaScript:l()"
onmouseover="r('66ff00'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#66ff33"><a href="JavaScript:l()"
onmouseover="r('66ff33'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#66ff66"><a href="JavaScript:l()"
onmouseover="r('66ff66'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#66ff99"><a href="JavaScript:l()"
onmouseover="r('66ff99'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#66ffcc"><a href="JavaScript:l()"
onmouseover="r('66ffcc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#66ffff"><a href="JavaScript:l()"
onmouseover="r('66ffff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

</tr>
<tr>
        <td bgcolor="#990000"><a href="JavaScript:l()"
onmouseover="r('990000'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#990033"><a href="JavaScript:l()"
onmouseover="r('990033'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#990066"><a href="JavaScript:l()"
onmouseover="r('990066'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#990099"><a href="JavaScript:l()"
onmouseover="r('990099'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#9900cc"><a href="JavaScript:l()"
onmouseover="r('9900cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#9900ff"><a href="JavaScript:l()"
onmouseover="r('9900ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#996600"><a href="JavaScript:l()"
onmouseover="r('996600'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#996633"><a href="JavaScript:l()"
onmouseover="r('996633'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#996666"><a href="JavaScript:l()"
onmouseover="r('996666'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#996699"><a href="JavaScript:l()"
onmouseover="r('996699'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#9966cc"><a href="JavaScript:l()"
onmouseover="r('9966cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#9966ff"><a href="JavaScript:l()"
onmouseover="r('9966ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#99cc00"><a href="JavaScript:l()"
onmouseover="r('99cc00'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#99cc33"><a href="JavaScript:l()"
onmouseover="r('99cc33'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#99cc66"><a href="JavaScript:l()"
onmouseover="r('99cc66'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#99cc99"><a href="JavaScript:l()"
onmouseover="r('99cc99'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#99cccc"><a href="JavaScript:l()"
onmouseover="r('99cccc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#99ccff"><a href="JavaScript:l()"
onmouseover="r('99ccff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

</tr>
<tr>
        <td bgcolor="#993300"><a href="JavaScript:l()"
onmouseover="r('993300'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#993333"><a href="JavaScript:l()"
onmouseover="r('993333'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#993366"><a href="JavaScript:l()"
onmouseover="r('993366'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#993399"><a href="JavaScript:l()"
onmouseover="r('993399'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#9933cc"><a href="JavaScript:l()"
onmouseover="r('9933cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#9933ff"><a href="JavaScript:l()"
onmouseover="r('9933ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#999900"><a href="JavaScript:l()"
onmouseover="r('999900'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#999933"><a href="JavaScript:l()"
onmouseover="r('999933'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#999966"><a href="JavaScript:l()"
onmouseover="r('999966'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#999999"><a href="JavaScript:l()"
onmouseover="r('999999'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#9999cc"><a href="JavaScript:l()"
onmouseover="r('9999cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#9999ff"><a href="JavaScript:l()"
onmouseover="r('9999ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#99ff00"><a href="JavaScript:l()"
onmouseover="r('99ff00'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#99ff33"><a href="JavaScript:l()"
onmouseover="r('99ff33'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#99ff66"><a href="JavaScript:l()"
onmouseover="r('99ff66'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#99ff99"><a href="JavaScript:l()"
onmouseover="r('99ff99'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#99ffcc"><a href="JavaScript:l()"
onmouseover="r('99ffcc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#99ffff"><a href="JavaScript:l()"
onmouseover="r('99ffff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

</tr>
<tr>
        <td bgcolor="#cc0000"><a href="JavaScript:l()"
onmouseover="r('cc0000'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#cc0033"><a href="JavaScript:l()"
onmouseover="r('cc0033'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cc0066"><a href="JavaScript:l()"
onmouseover="r('cc0066'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cc0099"><a href="JavaScript:l()"
onmouseover="r('cc0099'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cc00cc"><a href="JavaScript:l()"
onmouseover="r('cc00cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cc00ff"><a href="JavaScript:l()"
onmouseover="r('cc00ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cc6600"><a href="JavaScript:l()"
onmouseover="r('cc6600'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cc6633"><a href="JavaScript:l()"
onmouseover="r('cc6633'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cc6666"><a href="JavaScript:l()"
onmouseover="r('cc6666'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cc6699"><a href="JavaScript:l()"
onmouseover="r('cc6699'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#cc66cc"><a href="JavaScript:l()"
onmouseover="r('cc66cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cc66ff"><a href="JavaScript:l()"
onmouseover="r('cc66ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cccc00"><a href="JavaScript:l()"
onmouseover="r('cccc00'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cccc33"><a href="JavaScript:l()"
onmouseover="r('cccc33'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cccc66"><a href="JavaScript:l()"
onmouseover="r('cccc66'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cccc99"><a href="JavaScript:l()"
onmouseover="r('cccc99'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cccccc"><a href="JavaScript:l()"
onmouseover="r('cccccc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ccccff"><a href="JavaScript:l()"
onmouseover="r('ccccff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

</tr>
<tr>
        <td bgcolor="#cc3300"><a href="JavaScript:l()"
onmouseover="r('cc3300'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cc3333"><a href="JavaScript:l()"
onmouseover="r('cc3333'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cc3366"><a href="JavaScript:l()"
onmouseover="r('cc3366'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cc3399"><a href="JavaScript:l()"
onmouseover="r('cc3399'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cc33cc"><a href="JavaScript:l()"
onmouseover="r('cc33cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cc33ff"><a href="JavaScript:l()"
onmouseover="r('cc33ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cc9900"><a href="JavaScript:l()"
onmouseover="r('cc9900'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cc9933"><a href="JavaScript:l()"
onmouseover="r('cc9933'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#cc9966"><a href="JavaScript:l()"
onmouseover="r('cc9966'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cc9999"><a href="JavaScript:l()"
onmouseover="r('cc9999'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cc99cc"><a href="JavaScript:l()"
onmouseover="r('cc99cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#cc99ff"><a href="JavaScript:l()"
onmouseover="r('cc99ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ccff00"><a href="JavaScript:l()"
onmouseover="r('ccff00'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ccff33"><a href="JavaScript:l()"
onmouseover="r('ccff33'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ccff66"><a href="JavaScript:l()"
onmouseover="r('ccff66'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ccff99"><a href="JavaScript:l()"
onmouseover="r('ccff99'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ccffcc"><a href="JavaScript:l()"
onmouseover="r('ccffcc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#ccffff"><a href="JavaScript:l()"
onmouseover="r('ccffff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

</tr>


<tr>
        <td bgcolor="#ff0000"><a href="JavaScript:l()"
onmouseover="r('ff0000'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ff0033"><a href="JavaScript:l()"
onmouseover="r('ff0033'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ff0066"><a href="JavaScript:l()"
onmouseover="r('ff0066'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ff0099"><a href="JavaScript:l()"
onmouseover="r('ff0099'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ff00cc"><a href="JavaScript:l()"
onmouseover="r('ff00cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#ff00ff"><a href="JavaScript:l()"
onmouseover="r('ff00ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ff6600"><a href="JavaScript:l()"
onmouseover="r('ff6600'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ff6633"><a href="JavaScript:l()"
onmouseover="r('ff6633'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ff6666"><a href="JavaScript:l()"
onmouseover="r('ff6666'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ff6699"><a href="JavaScript:l()"
onmouseover="r('ff6699'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ff66cc"><a href="JavaScript:l()"
onmouseover="r('ff66cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ff66ff"><a href="JavaScript:l()"
onmouseover="r('ff66ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ffcc00"><a href="JavaScript:l()"
onmouseover="r('ffcc00'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ffcc33"><a href="JavaScript:l()"
onmouseover="r('ffcc33'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#ffcc66"><a href="JavaScript:l()"
onmouseover="r('ffcc66'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ffcc99"><a href="JavaScript:l()"
onmouseover="r('ffcc99'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ffcccc"><a href="JavaScript:l()"
onmouseover="r('ffcccc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ffccff"><a href="JavaScript:l()"
onmouseover="r('ffccff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

</tr>
<tr>
        <td bgcolor="#ff3300"><a href="JavaScript:l()"
onmouseover="r('ff3300'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ff3333"><a href="JavaScript:l()"
onmouseover="r('ff3333'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ff3366"><a href="JavaScript:l()"
onmouseover="r('ff3366'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#ff3399"><a href="JavaScript:l()"
onmouseover="r('ff3399'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ff33cc"><a href="JavaScript:l()"
onmouseover="r('ff33cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ff33ff"><a href="JavaScript:l()"
onmouseover="r('ff33ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ff9900"><a href="JavaScript:l()"
onmouseover="r('ff9900'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ff9933"><a href="JavaScript:l()"
onmouseover="r('ff9933'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ff9966"><a href="JavaScript:l()"
onmouseover="r('ff9966'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ff9999"><a href="JavaScript:l()"
onmouseover="r('ff9999'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ff99cc"><a href="JavaScript:l()"
onmouseover="r('ff99cc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ff99ff"><a href="JavaScript:l()"
onmouseover="r('ff99ff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>

        <td bgcolor="#ffff00"><a href="JavaScript:l()"
onmouseover="r('ffff00'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ffff33"><a href="JavaScript:l()"
onmouseover="r('ffff33'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ffff66"><a href="JavaScript:l()"
onmouseover="r('ffff66'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ffff99"><a href="JavaScript:l()"
onmouseover="r('ffff99'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ffffcc"><a href="JavaScript:l()"
onmouseover="r('ffffcc'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
        <td bgcolor="#ffffff"><a href="JavaScript:l()"
onmouseover="r('ffffff'); return true"><img src="w.gif" height=12
width=12 border=0></a></td>
</tr>
</table></BODY></HTML>
