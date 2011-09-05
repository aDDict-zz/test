<?
$_MX_superadmin=0;
include "auth.php";
include "cookie_auth.php";  
if (!$_MX_superadmin) {
    exit;
}
include "menugen.php";

printhead();

$enter = get_http("enter", "");
$unique_demog = get_http("unique_demog", "");
$owner_id = get_http("owner_id", 0);
$title = get_http("title", "");
$name = get_http("name", "");

//$unique_demog=addslashes($unique_demog);


if ($enter=="yes") {
    $finished=1;
    $r5=mysql_query("select id from user where id='$owner_id'");
    if (!($r5 && mysql_num_rows($r5))) {
        echo "<tr><td><span class='szovegvastag'> Hiba! Nem választotta ki a csoport tulajdonosát.
              </span></td></tr>"; 
        $finished=0;
    }
    //if (!empty($unique_demog)) {
        $r5=mysql_query("select id,variable_type from demog where variable_name='$unique_demog'");
        if ($r5 && mysql_num_rows($r5)) {
            $demog_id=mysql_result($r5,0,0);
            $demog_type=mysql_result($r5,0,1);
            if (!($demog_type=='nick' || $demog_type=='email' || $demog_type=='text' || $demog_type=='number' || $demog_type=='phone')) {
                echo "<tr><td><span class='szovegvastag'>A unique demog info csak 'szöveg','szám','telefonszám','email' vagy 'nick' típusú lehet.
                      </span></td></tr>"; 
                $finished=0;
            }
        }
        else {
            echo "<tr><td><span class='szovegvastag'>Nemlétező (unique) demog info.
                  </span></td></tr>"; 
            $finished=0;
        }
    //}
    if (!preg_match("/^[a-z]+[0-9a-z]*$/", $title)) {
        echo "<tr><td><span class='szovegvastag'>Hibás csoport név. 
              Csak ékezet nélküli kisbetűkből és számokból állhat és betűvel kell hogy kezdődjön.</span></td></tr>"; 
        $finished=0;
    }
    else {
        $r5=mysql_query("select id from groups where title='$title'");
        if ($r5 && mysql_num_rows($r5)) {
            echo "<tr><td><span class='szovegvastag'> Hiba! Már létezik ilyen nevű csoport.
                  </span></td></tr>"; 
            $finished=0;
        }
        $r5=mysql_query("select id from multi where title='$title'");
        if ($r5 && mysql_num_rows($r5)) {
            echo "<tr><td><span class='szovegvastag'> Hiba! Már létezik ilyen nevű csoport.
                  </span></td></tr>"; 
            $finished=0;
        }
    }
}

if ($enter=="yes" && $finished==1) {
         $validator_page="
<html>
<head>
<title>$_MX_var->application_instance_name</title>
<meta http-equiv=\'Content-Type\' content=\'text/html; charset=utf-8\'>
</head>
<body>
 <TABLE cellSpacing=0 cellPadding=0 width=\'100%\' border=0>
  <TBODY>
  <TR>
    <TD vAlign=top width=\'100%\'>
	<br>
      <TABLE cellSpacing=0 cellPadding=1 width=\'100%\' bgColor=$_MX_var->main_table_border_color border=0>
        <TBODY>
        <TR>
          <TD class=formmezo vAlign=center align=\'left\'>&nbsp;$word[VAL_SUBUNSUB]</TD>
        <TR>
          <TD class=formmezo>
            <TABLE cellSpacing=0 cellPadding=0 width=\'100%\' bgColor=#ffffff border=0>
              <TBODY>
              <TR>
                <TD class=bgvilagos2 align=center><br><span class=szoveg>{MESSAGE}</span>
		</TD>
              </TR>
	      </TBODY>
	    </TABLE>
	   </TD>
	 </TR>
	 </TBODY>
       </TABLE></TD></TR></TBODY></TABLE>
</body>
</html>
";
         $landingpage2="
<html>
<head>
<title>$_MX_var->application_instance_name</title>
<meta http-equiv=\'Content-Type\' content=\'text/html; charset=utf-8\'>
</head>
<body>
<center>
<span class=\'szovegvastag\'>$word[PREFIX_SUBSCRIBE] $word[AZ_LOWER] $title $word[SUFIX_SUBSCRIBE]</span>
<table border=0 cellspacing=0 cellpadding=1 bgcolor=$_MX_var->main_table_border_color width=600>
<tr>
<td align=center>
<table border=0 cellspacing=0 cellpadding=5 width=100% bgcolor=#eeeeee>
<tr>
<td>$word[PROM_REGIST]<br>$word[PROM_SENT]<br>
<span class=szovegvastag>$word[PROM_CHECK]</span>
<table border=0 cellspacing=0 cellpadding=0 width=100% bgcolor=#eeeeee>
<form>
<tr>
<td align=center>
<input type=button class=\'tovabbgomb\' value=$word[PROM_BACK] onClick=\"history.go(-2);\">
</td>
</tr>
</form>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>";
         $custom_head="
<html>
<head>
<title>$_MX_var->application_instance_name</title>
<meta http-equiv=\'Content-Type\' content=\'text/html; charset=utf-8\'>
</head>
<body>
";
         $custom_foot="
</body>
</html>";
         $mail_footer="
---------------------------
$word[MAIL_UNSUBSCRIBE]: unsubscribe@$title.maxima.hu";
         $mail_subject="[$title]";
         $subscribe_subject="$word[PREFIX_SUBSCRIBE] $word[AZ_LOWER] $title $word[SUFIX_SUBSCRIBE]";
         $subscribe_body="$word[PREFIX_SUBSCRIBE] $word[AZ_LOWER] $title $word[SUFIX_SUBSCRIBE]";
         $landing2="$word[PREFIX_SUBSCRIBE] $word[AZ_LOWER] $title $word[SUFIX_SUBSCRIBE]";
         
    $sname=mysql_escape_string($name);
    $name=htmlspecialchars($name);
    $res=mysql_query("insert into groups 
                      (owner_id,title,tstamp,custom_head,custom_foot,mail_footer,mail_subject,
	                   subscribe_subject,subscribe_body,landing2,landingpage2,validator_page,unique_col,name) 
                       values 
                       ('$owner_id','$title',now(),'$custom_head','$custom_foot','$mail_footer',
                       '$mail_subject','$subscribe_subject','$subscribe_body','$landing2',
                       '$landingpage2','$validator_page','$unique_demog','$sname')");
    if ($res) {
        $group_id=mysql_insert_id();
        $res=mysql_query("insert into alapadatok (group_id) VALUES ('$group_id');");
        $subscribe_id = md5("${title}ihaj$group_id");
        mysql_query("update groups set subscribe_id='$subscribe_id' where id='$group_id'");
        $res=mysql_query("insert into members (user_id,group_id,membership,tstamp,create_date,modify_date) 
                          values ('$owner_id','$group_id','moderator',now(),now(),now())");
        $res=mysql_query("insert into vip_demog (demog_id,group_id,dateadd,mandatory,tstamp,changeable) 
                          values (100,'$group_id',now(),'yes',now(),'yes')");
        $udadd="";
        if ($unique_demog=="email") 
            $udadd="unique key ui_email(ui_email),\n";
        elseif (!empty($unique_demog)) {
            $udadd="key ui_email(ui_email),\n";
            if ($demog_type=="number")
                $udadd.="ui_$unique_demog int default '0' not null,\n";
            else 
                $udadd.="ui_$unique_demog varchar(255) default '' not null,\n";
            $udadd.="unique key ui_$unique_demog(ui_$unique_demog),\n";
            $res=mysql_query("insert into vip_demog (demog_id,group_id,dateadd,mandatory,tstamp,changeable) 
                              values ('$demog_id','$group_id',now(),'yes',now(),'yes')");
        }
        else 
            $udadd="key ui_email(ui_email),\n";
        $res=mysql_query("create table users_$title (
            id int not null auto_increment,
            primary key(id),
            ui_email varchar(100) default '' not null,
            $udadd
            last_clicked datetime default '1970-01-01 00:00:01' not null,
            key last_clicked(last_clicked),
            last_sent datetime default '1970-01-01 00:00:01' not null,
            key last_sent(last_sent),
            validated enum('yes','no') default 'no' not null,
            key validated(validated),
            robinson enum('yes','no') default 'no' not null,
            key robinson(robinson),
            sms_robinson enum('yes','no') default 'no' not null,
            key sms_robinson(sms_robinson),
            aff int not null default 0,
            key aff(aff),
            man_aff int not null default 0,
            key man_aff(man_aff),
            tstamp datetime default '1970-01-01 00:00:01' not null,
            key tstamp(tstamp),
            mess_total int default '0' not null,            
            unsub_date datetime,
            key unsub_date(unsub_date),
            validated_date datetime default '1970-01-01 00:00:01' not null,
            key validated_date(validated_date),
            bounced enum('yes','no') default 'no' not null,
            key bounced(bounced),
            date datetime default '1970-01-01 00:00:01' not null,
            messagelist text default '' not null,
            clicklist text default '' not null,
            score_bounced varchar(50) default '' not null,
            score_trust varchar(50) default '' not null,
            uglist text default '' not null, 
            sms_total int default 0 not null,
            last_sent_sms datetime default '1970-01-01 00:00:01' not null,
            smslist text default '' not null,
            data_changed datetime NOT NULL default '0000-00-00 00:00:00'
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8");
        $res=mysql_query("create table ${title}_cid (
            `cid` varchar(32) NOT NULL,
            dateadd timestamp not null default current_timestamp,
            key cid(cid)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8");
        $msg="Az új csoport hozzáadása sikeres: $title ($name), id: $group_id.<br>";
    }
    else
        $msg="Az új csoport hozzáadása sikertelen";
    echo "<tr>
        <td>
        $msg
        <a href='admin_statistic.php'>Csoportok listája</a><br>
        </td>
        </tr>\n";
}

$opusers="";
$res=mysql_query("select id,email from user order by email");
if ($res && mysql_num_rows($res))
    while ($k=mysql_fetch_array($res))
        $opusers.="<option value=$k[id]>$k[email]</option>";

if (empty($unique_demog))
    $unique_demog='email';

$unique_demog=htmlspecialchars($unique_demog);

echo "<form method=post>
    <input type='hidden' name='enter' value='yes'>
    <tr>
    <td valign='top'><span class='szoveg'>&nbsp;&nbsp;A csoport neve &nbsp;</span></td>
    <td valign='top'><input class=formframe type='text' name='title' value='$title'></td>
    </tr>
    <tr>
    <td valign='top'><span class='szoveg'>&nbsp;&nbsp;A csoport hosszú neve &nbsp;</span></td>
    <td valign='top'><input class=formframe type='text' name='name' value='$name'></td>
    </tr>
    <tr>
    <td valign='top'><span class='szoveg'>&nbsp;&nbsp;A csoport adminisztrátora&nbsp;</span></td>
    <td valign='top'>
    <select class=formframe name='owner_id'>
    <option value=0>-- Válasszon --</option>
    $opusers
    </select>
    </td>
    </tr>        
    <tr>
    <td valign='top'><span class='szoveg'>&nbsp;&nbsp;Unique demog info*&nbsp;</span></td>
    <td valign='top'>
    <input type='text' name='unique_demog' value=\"$unique_demog\">
    </td>
    </tr>        
    <tr>
    <td align=center colspan=2>
    <input type=submit class='tovabbgomb' value='Mehet'>
    </td>
    </tr>		
    <tr>
    <td colspan=2><span class='szoveg'>
    * Az a demog info, amely egyértelműen azonosítja a felhasználót, általában ez az email cím, de lehet bármely más 'szöveg','szám','telefonszám','email' vagy 'nick' típusú demog info (pl. 'mobil'). <!--A 'Unique demog info' mező lehet üres, ebben az esetben a csoportnak nem lesz unique azonosítója, bármely demog infonál előfordulhatnak azonos értékek.-->
    </span></td>
    </tr>		
    </form>
    </table>
    \n";

include "footer.php";

function printhead() {

global $_MX_var,$word;

print "
<table border='0' cellpadding='2' cellspacing='0' width='100%'>
<tr>
<td class=formmezo>$word[admin_statistic]</td>
</tr>
</table>
<table border=0 cellpadding=2 cellspacing=0 width=\"100%\">\n";
}
?>
