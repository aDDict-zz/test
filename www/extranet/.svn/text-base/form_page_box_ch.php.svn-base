<?
include "auth.php";
include "decode.php";
$weare = 34;
include "cookie_auth.php";
include "common.php";

// The widgets in this form
$alldata = array('text_before' => 'textarea', 'text_after' => 'textarea',
                 'title' => 'input', 'subscribe_groups'=>'textarea', 'active'=>'checkbox');

// Get group data
$query = mysql_query("SELECT title, num_of_mess, membership
                       FROM groups, members
                      WHERE groups.id = members.group_id
                        AND groups.id = '$group_id'
                        AND (membership = 'owner'
                         OR membership = 'moderator' $admin_addq)
                        AND user_id = '$active_userid'");
if ($query && mysql_num_rows($query)) {
    $group = mysql_fetch_array($query);
} else {
    exit;
}

$title = $group['title'];
$active_membership = $group['membership'];

// Check whether the form exists
$form_id = get_http("form_id","");
$query = mysql_query("SELECT pages FROM form
                       WHERE id = '$form_id' AND group_id = '$group_id'");
if ($query && mysql_num_rows($query)) {
    $row = mysql_fetch_array($query);
    $pages = $row['pages'];
} else {
    exit;
}

// Check the page id
$page_id = get_http("page_id","");
$query = mysql_query("SELECT boxes FROM form_page
                       WHERE group_id = '$group_id' AND form_id = '$form_id'
                         AND page_id = '$page_id'");
logger("",$group_id,"","form_id=$form_id,page_id=$page_id","form_page");
if ($query && mysql_num_rows($query)) {
	$row = mysql_fetch_array($query);
	$boxes = chr(ord('A') + $row['boxes']);
} else {
    exit;
}

// Check whether the box exists
$box_id = get_http("box_id","");
$query = mysql_query("SELECT box_id FROM form_page_box
                       WHERE group_id = '$group_id' AND form_id = '$form_id'
                         AND page_id = '$page_id' AND box_id = '$box_id'");
if (!$query || !mysql_num_rows($query)) {
    if ($box_id >= 'A' && $box_id < $boxes) {
        // This is a valid box_id, but it is not in the database. Quick, create it!
        $query="INSERT INTO form_page_box (group_id, form_id, page_id, box_id)
                     VALUES ('$group_id', '$form_id', '$page_id', '$box_id')";
        mysql_query($query);
       logger($query,$group_id,"","form_id=$form_id,page_id=$page_id,box_id=$box_id","form_page_box");
    } else {
        exit;
    }
}

// Load the box's data
$query = mysql_query("SELECT * FROM form_page_box
                       WHERE group_id = '$group_id' AND form_id = '$form_id'
                         AND page_id = '$page_id' AND box_id = '$box_id'");
if ($query && mysql_num_rows($query)) {
    $boxdata = mysql_fetch_array($query);
} else {
    exit;
}

// Save the data if necessary
$error = array();
if (get_http("enter","") == 'yes') {
    form_enter();
}

include "menugen.php";
include "./lang/$language/form.lang";

printhead();

$errorlist = '';
foreach($error as $_error) {
    $errorlist .= htmlspecialchars($word[$_error]) . "<br>";
}
// Form header
echo "<form action=\"form_page_box_ch.php\" method=\"post\">
      <input type=\"hidden\" name=\"enter\" value=\"yes\">
      <input type=\"hidden\" name=\"group_id\" value=\"$group_id\">
      <input type=\"hidden\" name=\"form_id\" value=\"$form_id\">
      <input type=\"hidden\" name=\"page_id\" value=\"$page_id\">
      <input type=\"hidden\" name=\"box_id\" value=\"$box_id\">
      <tr>
      <td colspan=\"2\" bgcolor=\"white\"><span class=\"szoveg\">$errorlist&nbsp;</span></td>
      </tr>\n";
// Print each input
foreach ($alldata as $data => $widget) {
    if (isset($_POST) && isset($_POST[$data])) {
        $value = slasher($_POST[$data], 0);
    } elseif (!isset($_POST["enter"]) && isset($boxdata) && isset($boxdata[$data])) {
        $value = htmlspecialchars($boxdata[$data]);
    } else {
        $value = '';
    }
    $label = htmlspecialchars($word["ipageboxch_$data"]);
    if ($widget == 'input') {
        $input = "<input class=\"formframe\" type=\"text\" name=\"$data\" value=\"$value\" size=\"35\">";
    } elseif ($widget=="textarea") {
        $input = "<textarea class=\"formframe\" name=\"$data\" wrap=\"virtual\" rows=\"12\" cols=\"70\">$value</textarea>";
    } else {
        $checked=$value=="yes"?"checked":"";
        $input = "<input type='checkbox' name=\"$data\" value='yes' $checked>";
    }
    echo "<tr>
          <td style='vertical-align:top;' bgcolor=\"white\"><span class=\"szoveg\">$label</span></td>
          <td style='vertical-align:top;' bgcolor=\"white\"><span class=\"szoveg\">$input</span></td>
          </tr>\n";
}
// Form footer
echo "<tr>
      <td align=\"center\" colspan=\"2\">
      <input type=\"submit\" class=\"tovabbgomb\" value=\"$word[submit3]\">
      </td>
      </tr></form>";
printfoot();
include "footer.php";

function form_enter()
{
    global $_MX_var,$group_id, $form_id, $page_id, $box_id, $error, $alldata, $_POST;

    $values = array();
    foreach ($alldata as $data => $widget) {
        if (isset($_POST) && isset($_POST[$data])) {
            $value = $_POST[$data];
        } else {
            $value = '';
        }
//        $value = ereg_replace("\r\n","\\n",$value);
        if ($widget=="checkbox" && $value!="yes") { $value="no"; }
        $values[] = "$data = '" . $value . "'";
    }
    $values = implode(', ', $values);
    if (empty($error)) {
    	$query="UPDATE form_page_box SET $values
                      WHERE group_id = '$group_id' AND form_id = '$form_id'
                        AND page_id = '$page_id' AND box_id = '$box_id'";
		//print "$query<br>";
        mysql_query($query);
        logger($query,$group_id,"","form_id=$form_id,page_id=$page_id,box_id=$box_id","form_page_box");
        if (count($error) == 0) {
            header ("Location: form_elements.php?form_id=$form_id&group_id=$group_id");
            exit;
        }
    }
}

function printhead()
{

    global $_MX_var,$group_id, $form_id, $page_id, $box_id, $stat_text, $word;
    $stat_text = $word["iform_change"];

    if ($form_id) {
        $params   = "group_id=$group_id&amp;form_id=$form_id";
        $generate = "<a href=\"form_generate.php?$params\">$word[iform_generate]</a>&nbsp;";
        $modform  = "<a href=\"form_ch.php?$params\">$word[iform_change]</a>&nbsp;";
        $elements = "<a href=\"form_elements.php?$params\">$word[iform_elements]</a>&nbsp;";
        $css      = "<a href=\"form_css.php?$params\">$word[iform_css]</a>&nbsp;";
        $preview  = "<a href=\"#\" onclick=\"window.open('form_generate.php?$params&amp;preview=1', 'preview');\">$word[iform_preview]</a>&nbsp;";
        $links    = "$elements $css $generate $preview";
    } else {
        $links = '';
    }

    echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"1\" bgcolor=\"$_MX_var->main_table_border_color\" width=\"100%\">
          <tr>
          <td>
          <table class='addborder' border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"$_MX_var->main_table_border_color\" width=\"100%\">
          <tr>
          <td align=\"left\" class=\"bgkiemelt2\"><span class=\"szovegvastag\"><a href=\"form.php?group_id=$group_id\">$word[iform_title]</a> &gt; $page_id. $word[fe_page] &gt; $box_id. $word[fe_box] &gt; $word[iform_change]</span></td>
          <td align=\"right\" class=\"bgkiemelt2\"><span class=\"szovegvastag\">$links&nbsp;</span></td>
          </tr>\n";
}

function printfoot()
{
    echo "</table>
          </td>
          </tr>
          </table>";
}
