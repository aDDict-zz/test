<?

$_MX_superadmin=0;
include "auth.php";
include "cookie_auth.php";  
if (!$_MX_superadmin) {
    exit;
}
$action = get_http("action","");
$user_id = intval(get_http("user_id",""));

if ($action=="delete") {
    $email="";
    $res=mysql_query("select email from user where id='$user_id'");
    if ($res && mysql_num_rows($res))
        $email=mysql_result($res,0,0);
    if (empty($email)) {
        header("Location: admin_user.php");
        exit;
    }
    $owns=0;
    $res=mysql_query("select count(*) from members where membership='owner' and user_id='$user_id'");
    if ($res && mysql_num_rows($res)) {
        $owns=mysql_result($res,0,0);
    }
    else {
        header("Location: admin_user.php");
        exit;
    }
    if ($owns) {
        header("Location: admin_user.php");
        exit;
    }
    $res=mysql_query("select messages.id,groups.owner_id from messages,groups 
                      where messages.group_id=groups.id and messages.user_id='$user_id'");
    if ($res && mysql_num_rows($res)) {
        while ($k=mysql_fetch_array($res)) {
            $sq="update messages set user_id='$k[owner_id]' where id='$k[id]'";
            mysql_query($sq);
        }
    }
    $res=mysql_query("select messages_scheduled.id,groups.owner_id from messages_scheduled,groups 
                      where messages_scheduled.group_id=groups.id and messages_scheduled.user_id='$user_id'");
    if ($res && mysql_num_rows($res)) {
        while ($k=mysql_fetch_array($res)) {
            $sq="update messages_scheduled set user_id='$k[owner_id]' where id='$k[id]'";
            mysql_query($sq);
        }
    }
    $res=mysql_query("select validatemes.id,groups.owner_id from validatemes,groups 
                      where validatemes.group_id=groups.id and validatemes.user_id='$user_id'");
    if ($res && mysql_num_rows($res)) {
        while ($k=mysql_fetch_array($res)) {
            $sq="update validatemes set user_id='$k[owner_id]' where id='$k[id]'";
            mysql_query($sq);
        }
    }
    mysql_query("delete from message_client where user_id='$user_id'");
    mysql_query("delete from message_client_scheduled where user_id='$user_id'");
    mysql_query("delete from members where user_id='$user_id'");
    mysql_query("delete from user where id='$user_id'");
}

header("Location: admin_user.php");
?>
