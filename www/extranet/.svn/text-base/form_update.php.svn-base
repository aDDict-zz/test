<?
include "auth.php";
include "_form.php";
include "common.php";
$language=select_lang();
include "./lang/$language/form.lang";
include "./lang/$language/dategen.lang";

//http://office.manufacture.co.yu/maxima/form_update.php?group=permission&uid=3093bc52191738f34c54

$group_title=slasher($group);
$unique_id=slasher($uid);

$mres = mysql_query("select id,title from groups where title='$group_title'");
if ($mres && mysql_num_rows($mres)) {
    $rowg=mysql_fetch_array($mres);  
}
else {
    exit; 
}

// an hour should be enough upon receiving the email to get to this page.
$wait=360000;
$res=mysql_query("select * from update_request where group_id='$rowg[id]' and unique_id='$unique_id' 
                  and unix_timestamp(now())-unix_timestamp(date)<$wait limit 1");
if ($res && mysql_num_rows($res)) {
    $k=mysql_fetch_array($res);
    $mx_form = new MxForm($rowg["id"],$rowg["title"],0,1,$unique_id);
    //$form_id=intval($uform);
//print "*";    
    if ($mx_form->InitForm($form_id,$k["user_id"])) {
//print "*";    
        $mx_form->MakeForm();
        mysql_query("update update_request set clicked='yes' where id='$k[id]'");
    }
}

?>
