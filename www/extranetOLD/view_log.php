<?
include "auth.php";
include "decode.php";
$weare=34;
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/form.lang";
include "./lang/$language/sender.lang";
include "./lang/$language/dategen.lang";
$timer_id=htmlspecialchars($_REQUEST["timer_id"]);
$group_id=htmlspecialchars($_REQUEST["group_id"]);

if ($timer_id == NULL && $group_id == NULL) exit;

printhead();

$query="select sl.timer_id,sl.chtype,sl.chtype,gr.title, u.name as username, st.name as stname, sl.date_mod, sl.log_desc from sender_log sl left join sender_timer st on sl.timer_id=st.id inner join user u on sl.user_id=u.id inner join groups gr on sl.group_id = gr.id where sl.group_id='$group_id'";
if ($timer_id != NULL) $query.=" and timer_id=$timer_id";
if ($chtype != NULL and $chtype != "osszes") $query.=" and chtype='$chtype'";

$query.=" and sl.date_mod>='$datefrom' and sl.date_mod<='$dateto' order by date_mod desc limit $limstart, $perpage";
$res=mysql_query($query);
$k=mysql_fetch_array($res);

echo "<tr>
<td colspan='4' bgcolor='white'><span class='szoveg'>$errorlist&nbsp;</span></td>
</tr>\n";

echo 	"<tr><td class='bgkiemelt2' align=left width=25%><span class=szovegvastag>$word[st_sdate]</span></td>
		<td class='bgkiemelt2' align=left width=35%><span class=szovegvastag>$word[st_timername]</span></td>
		<td class='bgkiemelt2' align=left width=40%><span class=szovegvastag>$word[st_username]</span></td>
        </tr>\n";
$index=0;
$res=mysql_query($query);
while ($logs=mysql_fetch_array($res)) 
{
    /*
    print '<pre>';
    var_dump($logs);
    print '</pre>';
    */
	if ($index%2)
		$bgrnd="class=bgvilagos2";
	else
		$bgrnd="bgcolor=white";

	echo 	"<tr><td $bgrnd style= 'word-wrap:break-word;' valign=top align=left width=22%>
				<span class=szoveg>$logs[date_mod]</span></td>
              	<td $bgrnd valign=top align=left width=18%>
				<span class=szoveg>$logs[stname]</span></td>
              	<td $bgrnd valign=top align=left width=25%>
				<span class=szoveg>$logs[username]</span></td>
			</tr>
			<tr>
				<td $bgrnd valign=middle align=left>".$word[$logs[chtype]]."</td>
              	<td $bgrnd valign=top align=left colspan=2>
				<span class=szoveg>";
	$logs['log_desc']=str_replace(",","",$logs['log_desc']);
	$changes=explode("*#*",$logs[log_desc]);
	$oldstype="";
	$newstype="";
    $base_id=0;
	foreach ($changes as $key => $element) 
	{ 
		$ch=explode(":: ",$element);		
		foreach ($ch as $key => $el) 
		{ 
			if (strpos($el,"->") != NULL)
			{
				$c=explode(" -> ",$el);
				echo ("<b>".$word["st_".$field]."</b>: ");
				$c[0]=trim($c[0]);
				$c[1]=trim($c[1]);
				switch ($field) 
				{
					case "name":
					{
						echo ($c[0]." -> ".$c[1]."<br>");
						break;
					}
                    case "group_id":
                    case "mod_group_id":
                    {
                        echo $k["title"] . "<br>";
                        break;
                    }
					case "base_id":
					{
						$q="select id,name from sender_base where id='$c[0]' or id='$c[1]'";
						$basename=mysql_query($q);
                    	while ($bnrow=mysql_fetch_array($basename)) {
							if ($bnrow[id]==$c[0]) $str=$bnrow['name']; else $tmp=" -> ".$bnrow['name'];
                            $base_id = $bnrow["id"];
                        }
						echo $str.$tmp."<br>";
						$str=$tmp="";
						break;
					}
                    case "subject":
                    {
						$q="select id,subject from sender_base where id='$base_id'";
						$basename=mysql_query($q);
                    	while ($bnrow=mysql_fetch_array($basename)) 
							if ($bnrow[id]==$c[0]) $str=$bnrow['subject']; else $tmp=" -> ".$bnrow['subject'];
						echo $str.$tmp."<br>";
						$str=$tmp="";
                        break;
                    }
					case "sender_id":
					{
						$q="select * from user where id='$c[0]' or id='$c[1]'";
						$usersname=mysql_query($q);
                    	while ($bnrow=mysql_fetch_array($usersname)) 
							if ($bnrow[id]==$c[0]) $str=$bnrow['name']; else $tmp=" -> ".$bnrow['name'];
						echo $str.$tmp."<br>";
						$str=$tmp="";
						break;
					}
					case "filter_id":
					{
						$q="select id,name from filter where id='$c[0]' or id='$c[1]'";
						$filtername=mysql_query($q);
                    	while ($bnrow=mysql_fetch_array($filtername)) 
							if ($bnrow[id]==$c[0]) $str=$bnrow['name']; else $tmp=" -> ".$bnrow['name'];
						echo $str.$tmp."<br>";
						$str=$tmp="";
						break;
					}
					case "stype":
					{
						echo ($c[0]." -> ".$c[1]."<br>");
						$oldstype=$c[0];
						$newstype=$c[1];
						break;
					}
					case "stime":
					{
						echo ($c[0]." -> ".$c[1]."<br>");
						break;
					}
					case "notice":
					{
						echo ($c[0]." -> ".$c[1]."<br>");
						break;
					}
					case "test_email":
					{
						echo ($c[0]." -> ".$c[1]."<br>");
						break;
					}
					case "sdate":
					{
						echo ($c[0]." -> ".$c[1]."<br>");
						break;
					}
					case "smonthday":
					{
						echo ($c[0]." -> ".$c[1]."<br>");
						break;
					}
					case "smod":
					{
						$fr="";
						if ($oldstype!=NULL)
						{							
							if ($oldstype=="2hetente") $mimax=14; elseif ($oldstype="3hetente") $mimax=21;
							for ($mi=0;$mi<$mimax;$mi++) 
							{
								$sr=mysql_query("select from_days(to_days(now())+$mi),mod(to_days(now())+$mi,$mimax)");
								if ($sr && mysql_num_rows($sr)) 
								{
								$optname=mysql_result($sr,0,0);
								$optval=mysql_result($sr,0,1);
								if ($c[0]==$optval) $fr= $optname;
								}
							}
						}
						$query="select stype from sender_timer where id=".$logs["timer_id"];
						$r=mysql_query($query);
						$sty=mysql_fetch_array($r);
						if ($newstype!=NULL) $sty["stype"]=$newstype;
						if ($sty["stype"]=="2hetente") $mimax=14; elseif ($sty["stype"]="3hetente") $mimax=21;
						for ($mi=0;$mi<$mimax;$mi++) 
						{
                    		$sr=mysql_query("select from_days(to_days(now())+$mi),mod(to_days(now())+$mi,$mimax)");
                    		if ($sr && mysql_num_rows($sr)) 
							{
                        		$optname=mysql_result($sr,0,0);
                        		$optval=mysql_result($sr,0,1);
								if ($fr==NULL) 
									if ($c[0]==$optval) $fr= $optname;
								if ($c[1]==$optval) $to= $optname;
							}
						}
						echo ($fr." -> ".$to."<br>");
						break;
					}
					case "sdays":
					{
						$fr=$to="";
						for ($mi=0;$mi<7;$mi++) 
						{
						if (substr($c[0],$mi,1)=="X") $fr.=$word["day".$mi].", ";
						if (substr($c[1],$mi,1)=="X") $to.=$word["day".$mi].", ";
						}
						echo ($fr." -> ".$to."<br>");
						break;
					}
					case "active":
					{
						echo ($c[0]." -> ".$c[1]."<br>");
						break;
					}
				}				
			}
			else
			{
				$field=$el;	
			}
		}
	}
//print_r($logs[log_desc]);
	echo 	"</span></td>
			</tr>";
	$index++;
}

echo "</form>";
printfoot();
include "footer.php";


function printhead() {

    global $_MX_var,$stat_text,$k,$userlist_yn,$filt_demog_options,$group_id,$pagenum,$word, $formdata, $form_id, $group_id,$limstart,$perpage,$datefrom,$dateto, $language;


$_MX_popup = 1;
include "menugen.php";


$chtype=$_REQUEST["chtype"];
$datefrom=$_REQUEST["datefrom"];
$dateto=$_REQUEST["dateto"];
if ($datefrom==NULL) $datefrom=date("Y-m-d",mktime(0, 0, 0, date("m")-3 , date("d"), date("Y")));
if ($dateto==NULL) $dateto=date("Y-m-d",mktime(24, 0, 0, date("m")  , date("d"), date("Y")));
$timer_id=htmlspecialchars($_REQUEST["timer_id"]);

$query="select gr.title from sender_log sl left join sender_timer st on sl.timer_id=st.id inner join user u on sl.user_id=u.id inner join groups gr on sl.group_id = gr.id where sl.group_id='$group_id'";
if ($timer_id != NULL) $query.=" and timer_id=$timer_id";
if ($chtype != NULL and $chtype != "osszes") $query.=" and chtype='$chtype'";
$query.=" and sl.date_mod>='$datefrom' and sl.date_mod<='$dateto'";

$res=mysql_query($query);
$k=mysql_fetch_array($res);
$count=mysql_num_rows($res);

$off=$_REQUEST["off"];
if ($_REQUEST["perpage"]==NULL) $perpage=10; else $perpage=$_REQUEST["perpage"];
$pages_total=floor($count/$perpage);
if ($count % $perpage >0) $pages_total++;
if ($pagenum) $off=$pagenum-1;
if ($off>$pages_total-1) $off=$pages_total-1; 
if ($off<0) $off=0;
$limstart=$off*$perpage;
$prevpage=$off-1;
$pagenum=$off+1;
$lastpage=$pages_total-1;
$sortsel="<select name='sort' onchange='window.opener.location=\"view_log.php?group_id=$group_id&form_id=$form_id\";'>";
$dgroupsel="<select name='dgroup' style='background-color:#fcc;' onchange='window.opener.location=\"view_log.php?group_id=$group_id&form_id=$form_id\";'>\n";
echo "
<body bgcolor='#FFFFFF' onload='focus()'>
<TABLE cellSpacing=5 cellPadding=0 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
        <TR>
        <TD>
        <TABLE cellSpacing=2 cellPadding=5 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
        <tr>
		<td class=bgkiemelt2 align=left colspan=4><span class=szovegvastag>".$word[st_csoportnev].": ".$k["title"]."&nbsp;&nbsp;&nbsp;&nbsp;".$word[st_talalatoksz]." ".$count."</span></td>
		</tr>\n";

   print "<tr><td colspan=3><table border='0' cellspacing='0' cellpadding='0' width='100%'>
           <tr>
           <td>
           <table border='0' cellspacing='0' cellpadding='0' width='100%'>
           <tr>
    	   <td class='formmezo' align='left' width='33%'>
	       <table border='0' cellspacing='0' cellpadding='0'>

	       <tr>";
    if ($off>0) {
        print "<td nowrap align='right'>
               <a href='view_log.php?form_id=$form_id&group_id=$group_id&off=0&perpage=$perpage&datefrom=$datefrom&dateto=$dateto&timer_id=$timer_id'><img src='$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
               <a href='view_log.php?form_id=$form_id&group_id=$group_id&off=$prevpage&perpage=$perpage&datefrom=$datefrom&dateto=$dateto&timer_id=$timer_id'><img src='$_MX_var->application_instance/gfx/down1.gif' width='20' height='14' border='0'></a>
               </td>";
    }
    else {
        print "<td nowrap align='right'>&nbsp;&nbsp;
               <img src='$_MX_var->application_instance/gfx/down2_dead.gif' width='20' height='14' border='0'>
               <img src='$_MX_var->application_instance/gfx/down1_dead.gif' width='20' height='14' border='0'>
               </td>";
    }
    print "<form action='view_log.php'><input type='hidden' name='form_id' value='$form_id'>
	<input type='hidden' name='group_id' value='$group_id'><input type='hidden' name='dateto' value='$dateto'><input type='hidden' name='perpage' value='$perpage'><input type='hidden' name='timer_id' value='$timer_id'><input type='hidden' name='datefrom' value='$datefrom'><td nowrap align='right'><input type='text' name='pagenum' size='3' maxlength='3' value='$pagenum'></td></form>
           <td nowrap class='formmezo'>&nbsp;/ $pages_total</td>";
    if ($off<$pages_total-1) {
        print "<td nowrap align='right'>
               <a href='view_log.php?form_id=$form_id&group_id=$group_id&off=$pagenum&perpage=$perpage&datefrom=$datefrom&dateto=$dateto&timer_id=$timer_id'><img src='$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
               <a href='view_log.php?form_id=$form_id&group_id=$group_id&off=$lastpage&perpage=$perpage&datefrom=$datefrom&dateto=$dateto&timer_id=$timer_id'><img src='$_MX_var->application_instance/gfx/up2.gif' width='20' height='14' border='0'></a>
               </td>";
    }
    else {
        print "<td nowrap align='right'>&nbsp;&nbsp;
               <img src='$_MX_var->application_instance/gfx/up1_dead.gif' width='20' height='14' border='0'>
               <img src='$_MX_var->application_instance/gfx/up2_dead.gif' width='20' height='14' border='0'>
               </td>";
    }

    print "</tr>
           </table>
           </td>
           </tr>
           </table>
           </td>
    	   <td class='formmezo' align='center' width='33%'>$hnoperp
           <table border='0' cellspacing='0' cellpadding='0'>
           <tr> 
           <td nowrap class='formmezo' align='center'>$word[view]:</td>
           <form action='view_log.php'><input type='hidden' name='form_id' value='$form_id'><td nowrap align='center'>
			<input type='hidden' name='group_id' value='$group_id'><input type='hidden' name='pagenum' value='$pagenum'><input type='hidden' name='timer_id' value='$timer_id'><input type='text' name='perpage' value='$perpage' size='3' maxlength='3'></td></form>
           <td nowrap class='formmezo' align='center'> $word[demog_perpage]</td>
           </tr>
           </table>
           </td>
    	   <td class='formmezo' align='right' width='33%'>
           <table border='0' cellspacing='0' cellpadding='0'>$hnosort
           <tr> 
           <td nowrap class='formmezo'></td>";
           		print "<form action='view_log.php'><td nowrap><input type='hidden' name='form_id' value='$form_id'>
				<input type='hidden' name='group_id' value='$group_id'><input type='hidden' name='dateto' value='$dateto'><input type='hidden' name='timer_id' value='$timer_id'><input type='hidden' name='chtype' value='$chtype'><input type='hidden' name='perpage' value='$perpage'><input type='hidden' name='pagenum' value='$pagenum'><input type='text' name='datefrom' value='$datefrom' size='10'></td></form>";

           		print "<form action='view_log.php'><td nowrap>&nbsp;&nbsp;&nbsp;<input type='hidden' name='form_id' value='$form_id'><input type='hidden' name='datefrom' value='$datefrom'><input type='hidden' name='timer_id' value='$timer_id'><input type='hidden' name='chtype' value='$chtype'><input type='hidden' name='group_id' value='$group_id'> <input type='hidden' name='perpage' value='$perpage'><input type='hidden' name='pagenum' value='$pagenum'><input type='text' name='dateto' value='$dateto' size='10'></td></form>";

			if ($chtype!=NULL)
           		print "<form action='view_log.php'><td nowrap>&nbsp;&nbsp;&nbsp;<input type='hidden' name='form_id' value='$form_id'><input type='hidden' name='datefrom' value='$datefrom'><input type='hidden' name='dateto' value='$dateto'><input type='hidden' name='timer_id' value='$timer_id'>
				<input type='hidden' name='group_id' value='$group_id'><input type='hidden' name='perpage' value='$perpage'><input type='hidden' name='pagenum' value='$pagenum'><select style='BACKGROUND-COLOR: rgb(255, 204, 204);' name='chtype' value='$chtype' onchange='this.form.submit();'>"; 
			else
				print "<form action='view_log.php'><td nowrap>&nbsp;&nbsp;&nbsp;<input type='hidden' name='form_id' value='$form_id'><input type='hidden' name='datefrom' value='$datefrom'><input type='hidden' name='dateto' value='$dateto'><input type='hidden' name='timer_id' value='$timer_id'>
				<input type='hidden' name='group_id' value='$group_id'><input type='hidden' name='perpage' value='$perpage'><input type='hidden' name='pagenum' value='$pagenum'><select name='chtype' value='$chtype' onchange='this.form.submit();'>";
			if ($chtype=='osszes') echo "<option value='osszes' selected>$word[st_allgroups]</option>"; else echo "<option value='osszes'>$word[st_allgroups]</option>";
			if ($chtype=='st_letrehozva') echo "<option value='st_letrehozva' selected>$word[st_letrehozva]</option>"; else echo "<option value='st_letrehozva'>$word[st_letrehozva]</option>";
			if ($chtype=='st_modositva') echo "<option value='st_modositva'selected>$word[st_modositva]</option>"; else echo "<option value='st_modositva'>$word[st_modositva]</option>";
			if ($chtype=='st_torolve') echo "<option value='st_torolve' selected>$word[st_torolve]</option>"; else echo "<option value='st_torolve'>$word[st_torolve]</option>";

    print "</tr>
           </table>
           </td>
           </tr>
           </table></td></tr>\n
			<form method='post' action='form_in.php'>
			<input type='hidden' name='action' value='addtoform'>
			<input type='hidden' name='form_id' value='$form_id'>
			<input type='hidden' name='group_id' value='$group_id'>
			<input type='hidden' name='perpage' value='$perpage'>
			<input type='hidden' name='pagenum' value='$pagenum'>";


}

function printfoot() {

    echo "</table>
        </td>
        </tr>
        </table>
</body>
    </html>\n";
}

