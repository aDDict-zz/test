<?php

	//temporary stuff
  $cuser_id= 79950;   //intval($_COOKIE["cuser_id"]);
  $cunique_id= "c9bf707a74173ec91d41ae6441b8e071"; //addslashes($_COOKIE["cunique_id"]);
  $active_userid = 0;
  $active_username  = "";
  $active_useremail = "";
  $active_emailname = "";
  $active_nickname  = ""; 
  $active_membership = "";
  $_MX_superadmin = 0;
  $_MX_change_variables = 0;
  $_MX_rights=array('moderator','admin','support','client','affiliate','sender'); // possible rights

  $result = mysql_query("select * from user where id='$cuser_id' and unique_id='$cunique_id'");
	//$result = $_MX_var->sql("select * from user where id='$cuser_id' and unique_id='$cunique_id'");
	//echo "<br />sdfdsf"; print_r($result); echo "<br />sdfdsf"; die();
	
	
# 	$PDO = getPDO::get();
# 	$result = $PDO->query("select * from user where id='$cuser_id' and unique_id='$cunique_id'")->fetchAll(PDO::FETCH_ASSOC);
# 	print_r($result); echo "<br />sdfdsf"; die();
	
  if ($result && mysql_num_rows($result)) {
    $ul=mysql_fetch_array($result);
    $active_userid = $cuser_id;
    $active_username  = $ul["email"];
    $active_useremail = $ul["email"];
    $active_emailname = $ul["name"];
    $active_nickname  = $ul["name"]; 
    if ($ul["superadmin"]=="yes") {
        $_MX_superadmin = 1;
    }
    if ($ul["change_variables"]=="yes") {
        $_MX_change_variables = 1;
    }
    $result = mysql_query("select membership from members where user_id = '$active_userid' and group_id = '$group_id'");
    if ($result && mysql_num_rows($result)) {
        $active_membership = mysql_result($result,0,0);
    }
    if ($ul["new_pass_state"]!="ok") { 
        if (ereg("(logout|password).php",$_SERVER["REQUEST_URI"])) {
            $_MX_force_pass_change=1;
        }
        else {
            header("Location: password.php");
            exit;
        }
    }
  }
	//// iiiitttt
  if ($active_userid==0) { 
	  $target_url = (preg_match("~ajax.php$~", $_SERVER["REQUEST_URI"])) ? $_MX_var->baseUrl : $_MX_var->publicBaseUrl . slasher($_SERVER["REQUEST_URI"]);
      $_SESSION["mx_lastpage"] = $target_url;
      header("Location: $_MX_var->publicBaseUrl");
      exit;
  }
  elseif (isset($_SESSION["mx_lastpage"])) { 
      $lp = $_SESSION["mx_lastpage"];
      unset($_SESSION["mx_lastpage"]);
      header("Location: $lp"); exit;
  }

  $admin_addq="";
  $weare=intval($weare);
  if ($active_membership=="admin" && $weare) {
    $r=mysql_query("select page_id from page_user where group_id='$group_id' and user_id='$active_userid' and page_id='$weare'");
    if ($r && mysql_num_rows($r)) { 
        $active_membership="moderator";
        $admin_addq=" or membership='admin'";
    }
    elseif (!$_MX_superadmin) {  
        header("Location: index.php");
        exit;
    }
  }

// az alabbi fuggvenyeket a moderators* oldalak hasznaljak.

// ez a fuggveny 1-et ad vissza ha az aktiv felhasznalo ($active_membership) modosithatja a $membership jogu felhasznalo tagsagat
// FONTOS: feltetelezzuk, hogy az $active_membership es a $membership ugyanarra a csoportra vonatkozik.
function mx_can_i_modify_membership($membership) {

    global $_MX_var,$_MX_superadmin,$active_membership;

    $lower_rights=array("sender","affiliate","client");
    if ($_MX_superadmin) {
        return 1;  // azaz, moderator jogot a superadmin modosithat.
    }
    // az admin nyilvan csak akkor dolgozhat a tagokkal ha ezt az oldalat megkapta,
    // viszont ha nem kapta meg akkor mar eleve nem jut el eddig a pontig az oldalak elejen levo auth miatt.
    $modifier_right=array("owner","moderator","admin");
    if (!in_array($active_membership,$modifier_right)) {
        return 0;
    }
    // a moderatort senki nem banthatja, a support/admin usereket csak a moderator
    // az owner nem hasznalt tobbe, a regi rendszerrel valo kompatibilitas celjabol van itt
    if ($membership!="moderator" && $membership!="owner") {
        if ($active_membership=="moderator" || $active_membership=="owner" || in_array($membership,$lower_rights)) {
            return 1;
        }
    }
    return 0;
}

// ez a fuggveny 1-et ad vissza ha az aktiv felhasznalo($active_membership) modosithatja/lathatja a $user_id felhasznalo adatait az adott csoportban.
// FONTOS: feltetelezzuk, hogy az $active_membership,$user_id es a $membership ugyanarra a csoportra vonatkozik.
function mx_can_i_modify_data($membership,$user_id) {

    global $_MX_var,$_MX_superadmin,$active_membership,$active_userid;

    $res=mysql_query("select superadmin from user where id='$user_id'");
    if ($res && mysql_num_rows($res)) {
        if (mysql_result($res,0,0)!="no") { // a superadminet nyilvan nem.
            return 0;
        }
    }
    else {
        return 0;
    }
    if ($_MX_superadmin) {
        return 1;  // azaz, moderator jogot a superadmin modosithat.
    }
    if (!mx_can_i_modify_membership($membership)) { // ha valakinek nem valtoztathatom meg a tagsagat akkor nyilvan az adatait sem.
        return 0;
    }
    // ha valaki barmely csoportban moderator,support, vagy admin akkor nem lehet a felhasznalo oldalrol valtoztatni az adatait.
    // az owner nem hasznalt tobbe, a regi rendszerrel valo kompatibilitas celjabol van itt
    $higher_rights="'moderator','support','admin','owner'";
    $rr=mysql_query("select count(*) from members where user_id='$user_id' and membership in ($higher_rights)");
    if ($rr && mysql_num_rows($rr)) {
    	if (mysql_result($rr,0,0)) {
            return 0;
        }
    }
    else {
        return 0;
    }
    // sot, akkor sem, ha tag barmelyik olyan csoportban, ahol az aktiv felhasznalo nem moderator.
    $change_rights="'owner','moderator'";
    $q="select a.* from members a left join members b 
        on a.group_id=b.group_id and b.membership in ($change_rights) and b.user_id='$active_userid'
        where a.user_id='$user_id' and b.user_id is null limit 1";
    //print "$q<br>";
    $rr=mysql_query($q);
    if ($rr) {
    	if (mysql_num_rows($rr)) {
            return 0;
        }
    }
    else {
        return 0;
    }
    return 1;
}

// returns 1 if the current user can edit help
function mx_can_edit_help() {

    global $_MX_superadmin,$active_useremail;

    if ($_MX_superadmin || preg_match("/@(hirek\.hu|hirekmedia\.hu)/",$active_useremail)) {
        return 1;
    }
    return 0;
}

class getPDO{
    public function &get(){
		static $obj;
		$params = array(
            "host"  => "localhost",
            "db"    => "maxima",
            "user"  => "root",
            "psw"   => "v"
        );
        if (!is_object($obj)){
            $obj = new PDO("mysql:host={$params["host"]};dbname={$params["db"]}", $params["user"], $params["psw"]);
        }
        return $obj;
    }
}

?>
