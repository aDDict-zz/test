<?php
include_once "common.php";
include_once "decode.php";
include_once "auth.php";
include_once "cookie_auth.php";

$language=select_lang();
include "./lang/$language/sender.lang";
include "./lang/$language/ajax.lang";

header('Content-Type: text/html; charset=utf-8');

//function name
$func = get_http("func","");

//data for get_proc_list
$data['auid'] = $active_userid;  // NOT THROUGH GET/POST FOR GODS SAKE
$data['stop_sending'] = get_http("stop_sending",0);
$data['mid'] = get_http("mid",0);


//data for proclist_autoopen
$data['set'] = get_http("set","");

//data for save_help (pid goes to get_help too)
$data['pid'] = get_http("pid","");
$data['lng'] = get_http("lng","");
$data['txt'] = get_http("txt","");

//data for ajax upload
$data['field'] = get_http("field","");

//data for deleting images/attachments
$data['id'] = get_http("id","");
$data['sessionlastid'] = get_http("sessionlastid","");

//data for showing words - useful for js alerts, confirms, etc.
$data['key'] = get_http("key","");

//loading bases for groups
$data['group_id'] = get_http("group_id","");

//loading subject
$data['base_id'] = get_http("base_id","");

//call the function

if (in_array($func,array("get_proc_list","stop_send_proc","proclist_autoopen","save_help","get_help","get_proc_data","ajaxupload","check_dir","delete_uploaded","load_subject","load_base_list","load_base_user_list","show_word","my_json_encode"))) {
    $func($data);
}

function get_proc_list($data) {
    print '<table cellspacing="0" cellpadding="1" border="0" style="width:100%;">
                <tr>
                    <td style="width:7%;"><span class="bold" id="sort_cdate">Indítás</span></td>
                    <td style="width:38%;"><span class="bold" id="sort_name">Csoport</span></td>
                    <td style="width:38%;"><span class="bold" id="sort_subject">Hírlevél neve</span></td>
                    <td style="width:10%;"><span class="bold" id="sort_sendstopped">Státusz</span></td>
                    <td style="width:7%;" class="tac"><span class="bold" id="sort_tlbcount">Elküldve</span></td>
                </tr>';

                        
      $quq="select m.id,m.group_id,m.send_plan,m.tlb_count,substring(m.create_date,12,5) cdate,g.name,m.subject,m.spool,m.tlb_queue_plan,g.important,m.send_stopped
            from messages m,groups g,members p where m.tlb_finished='no' and m.send_plan>0 
            and m.group_id=g.id and g.id=p.group_id and p.user_id='$data[auid]' and p.membership in ('owner','moderator')";
      $qur=mysql_query($quq);
      if ($qur && mysql_num_rows($qur)) {
          while ($quk=mysql_fetch_array($qur)) {
              /*if ($stop_sending==$quk["id"] && $quk["send_stopped"]=="no") {
              if ($quk["send_stopped"]=="no") {
                  mysql_query("update messages set send_stopped='yes' where id=$quk[id]");
                  $quk["send_stopped"]="yes";

              }*/
              $send_stopped="<a id='stop_proc_".$quk["id"]."' href='javascript:void(0);'>[Leállít]</a>";
              if ($quk["send_stopped"]=="yes") {
                  $send_stopped=" <span class='headlinestopped'>[Leállítva]</span>";
              }
              print "<tr>";
              print "<td>".htmlspecialchars($quk["cdate"])."</td>";
              print "<td>".htmlspecialchars($quk["name"]) ."</td>";
              print "<td><a href='threadlist.php?group_id=$quk[group_id]'>".htmlspecialchars(quoted_printable_decode(decode_mime_string($quk["subject"]))) ."</a></td>";
              print "<td><span id='stop_send_status_".$quk["id"]."' value=".$quk["id"].">".$send_stopped."</span></td>";
              print '<td class="tac" id="percent_'.$quk["id"].'">'.min(100,round(max(0,$quk["tlb_count"])*100/$quk["send_plan"]))."%</td></tr>";
          }
      }
    print "</table></div></div>";
}

function stop_send_proc($data) {
    $quq="select m.id,m.group_id,m.send_plan,m.tlb_count,substring(m.create_date,12,5) cdate,g.name,m.subject,m.spool,m.tlb_queue_plan,g.important,m.send_stopped
           from messages m,groups g,members p where m.tlb_finished='no' and m.send_plan>0
           and m.group_id=g.id and g.id=p.group_id and p.user_id='$data[auid]' and p.membership in ('owner','moderator')";
    $qur=mysql_query($quq);
    if ($qur && mysql_num_rows($qur) && $data["stop_sending"]=="yes") {
        mysql_query("update messages set send_stopped='yes' where id = ".$data["mid"]);  
    }
}

function proclist_autoopen($data) {
    setcookie('proclist_autoopen', $data['set'], time()+31536000);
    print 'ok';
}

function save_help($data) {

    if (mx_can_edit_help() && !empty($data['pid']) && !empty($data['lng']) && !empty($data['txt'])) {
        $res = mysql_query("select text from help where lang = '$data[lng]' and page_id = '$data[pid]'");
        if ($res && mysql_num_rows($res)) {
            mysql_query("update help set text = '$data[txt]' where page_id = '$data[pid]' and lang = '$data[lng]'");
        }
        else {
            mysql_query("insert into help (page_id,lang,text) values ('$data[pid]','$data[lng]','$data[txt]')");
        }
        print $data['lng'].'_ok';
    }
    else {
        print $data['lng'].'_fail';
    }
    
}

function get_help($data) {
    $result = array();
    if (!empty($data['pid']) && !empty($data['lng'])) {
        $res = mysql_query("select text, lang from help where page_id = '$data[pid]' and lang = '$data[lng]'");
        if ($res && mysql_num_rows($res)) {
            while ($r=mysql_fetch_array($res)) {
                $result[$r['lang']] = $r['text'];
            }
        }

        if (!empty($result)) {
            print my_json_encode($result);
        }
        else {
            print my_json_encode(array($data['lng']=>'empty'));
        }
    }
}

function get_proc_data($data) {
    $quq="select count(m.id) as linecount, sum(m.send_plan) as sum_send_plan, sum(m.tlb_count) as sum_tlb_count
        from messages m,groups g,members p where m.tlb_finished='no' and m.send_plan>0 
        and m.group_id=g.id and g.id=p.group_id and p.user_id='$data[auid]' and p.membership in ('owner','moderator')";
    $qur=mysql_query($quq);
    if ($qur && mysql_num_rows($qur)) {
        if ($quk=mysql_fetch_array($qur)) {
            if ($quk["sum_send_plan"]) $percent = min(100,round(max(0,$quk["sum_tlb_count"])*100/$quk["sum_send_plan"]));
            else $percent = 0;    
        }
    }

    print $quk['linecount'].'_'.$percent;
}

function ajaxupload($data) {
    global $_MX_var, $word, $_SESSION;

    $ret = "";
    $field = $data['field'];
    $files = array();
    $ftype = $_FILES[$field]['type'];
    $fname = $_FILES[$field]['name'];
    $ftmp  = $_FILES[$field]['tmp_name'];

    if (preg_match('/^upload_text_/',$field)) {
        $contenth = fopen($ftmp,"r");
        $content = fread($contenth, filesize($ftmp));
        fclose($contenth);
        header("Content-Type: text/plain");
        $ret = $content;
    }
    else {
    if (preg_match('/(rar|zip)/',$ftype) || 
        preg_match("/(rar|zip)$/i", $fname) || 
        $ftype=="application/x-zip-compressed") {
        $folder = "compressed";
        $tmpdir = date("Y") . "-" . date("m") . "-" . date("d") . "-" . mt_rand(0,0999);
        $path = "/$folder/" . $tmpdir . "/";
        $uploaddir = $_MX_var->uploaddir . $path;
        if (check_dir($uploaddir)) {
            $newname = rewrite_rule_string(basename($fname),0);
            $uploadfile = $uploaddir . $newname;
            if (move_uploaded_file($ftmp, $uploadfile)) {
                chdir("$uploaddir");
                system("mv $fname $newname");
                if (eregi("\.zip$", "$newname")) {
                    system("unzip -oq $newname");
                }
                elseif (eregi("\.rar$", "$newname")) {
                    @system("unrar x -o+ -inul $newname"); // not on dev!
                }
                unlink("$newname");
                $handler = opendir(".");
                while ($f = readdir($handler)) {
                    if ($f != "." && $f != "..") {
                        $ft = "";
                        if ($size = getimagesize($f)) {
                            $ft = $size['mime'];
                        }
                        $files[] = array("ftype"=>$ft,"fname"=>$f,"ftmp"=>$uploaddir.$f);
                        //unlink("$f");
                    }
                }
                closedir($handler);
                //chdir("..");
                //rmdir("$tmpdir");
            }
			else {
				$ret .= "Could not move to $uploadfile";
			}
        }
		else {
            $ret .= $word['upload_error_nodir'] . $uploaddir;
        }
    }
    else {
        $files[] = array("ftype"=>$ftype,"fname"=>$fname,"ftmp"=>$ftmp);
    }

    foreach ($files as $file) {
        if (preg_match('/image$/',$field)) {
            $folder="images"; 
        }
        else {
            $folder="attachments";
        }
        if (preg_match('/^image\//',$file['ftype']) && preg_match('/image$/',$field)) {
            $type="image";
        }
        else {
            $type="attachment";
            $folder="attachments"; // non-images can be only attachments!
        }
        $path = "/$folder/" . mt_rand(0,999) . "/";
        $uploaddir = $_MX_var->uploaddir . $path;
        $uploadpath = $_MX_var->uploadurl . $path;
        
        if (check_dir($uploaddir)) {
            $name_exploded = explode(".",basename($file['fname']));
            $ext = array_pop($name_exploded);

            $try=0;
            do {
                $rnd = mt_rand(100000,999999);
                $newname = rewrite_rule_string(implode(".",$name_exploded),0);
                if ($try>0) {
                    $newname .= '_' . $rnd;
                }
                $newname .= '.' . $ext;
                $try++;
            }
            while (file_exists($uploaddir . $newname));

            $uploadfile = $uploaddir . $newname;
            $pathfile = $uploadpath . $newname;

            $moved = 0;
            if (move_uploaded_file($file['ftmp'], $uploadfile)) {
                $moved = 1;
            }
            elseif (rename($file['ftmp'], $uploadfile)) {
                $moved = 1;
            }
            if ($moved) {
                $filesize = filesize($uploadfile);
                $w = $h = 0;
                list($w,$h) = getimagesize($uploadfile);
                $ret .= "<span>";
                $ret .= "$newname ($filesize byte) &nbsp; ";
                $ret .= "[ ";
                if (!empty($w) && !empty($h)) {
                    $ret .= "<a href='javascript:;' onclick='img_preview(\"$pathfile\");'>$word[preview_image]</a>";
                }
                else {
                    $ret .= "<a href='$pathfile'>$word[preview_file]</a>";
                }
                if (preg_match('/image$/',$field) && $type=="image") {
                    $ret .= " / <a href='javascript:;' onclick='img_tag_paste(\"$pathfile\",\"$w\",\"$h\");'>$word[image_tag_paste]</a>";
                }
                $delstring = $word["delete_$field"];
                $_SESSION["maximaupload"][] = array("path"=>$path.$newname,"type"=>$type,"filesize"=>$filesize,"width"=>$w,"height"=>$h);
                $sessionlastid = array_pop(array_keys($_SESSION["maximaupload"]));
                $ret .= " / <a href='javascript:;' onclick='delete_uploaded(0,$(this),\"$delstring\",$sessionlastid);'>$word[delete]</a>";
                $ret .= " ]</span><br />";
                //print_r($_SESSION["maximaupload"]);
            }
            else {
                $ret .= $word['upload_error_nomove'];
            }
        }
        else {
            $ret .= $word['upload_error_nodir'];
        }
    }
//    print_r($_SESSION["maximaupload"]);
    }
    echo $ret;
}

function check_dir($dir) {
    $dir=ereg_replace("/$","",$dir);
    if (!is_dir($dir)) {
        if (@mkdir($dir,0777)) {
            return 1;
        }
        else {
            return 0;
        }
    }
    return 1;
}

function delete_uploaded($data) {
    global $_MX_var, $word;
    $id=$data['id'];
    $sessionlastid=$data['sessionlastid'];

    if ($sessionlastid=="") {
        $sql=mysql_query("select path from sender_base_uploaded_files where id='$id'");
        $rec=mysql_fetch_array($sql);

        $q="delete from sender_base_uploaded_files where id='$id'";
        if(mysql_query($q)) {
            $path=slasher($rec["path"]);
            $sql2="select id from sender_base_uploaded_files where path='$path'";
            $pathsnr=mysql_num_rows(mysql_query($sql2));
            if ($pathsnr==0) {
                $dir=$_MX_var->uploaddir.$rec['path'];
                $p=explode('/',$dir);
                $fname=array_pop($p);
                $dir=implode('/',$p);
                chdir ($dir);
                unlink ("$fname");
            }
            echo 'ok';
        }
        else {
            echo $word["delete_error_uploaded"];
        }
    }
    else {
        if (isset($_SESSION["maximaupload"][$sessionlastid])) {
            unset($_SESSION["maximaupload"][$sessionlastid]);
        }
        echo 'ok';
    }
}

function load_subject($data) {
    $ret = array("subject"=>"", "emphasized"=>"");
    $sql = mysql_query("select subject,emphasized from sender_base where id='$data[base_id]'");
    if (mysql_num_rows($sql)==1) {
        $rec = mysql_fetch_array($sql);
        $ret = array("subject"=>$rec["subject"], "emphasized"=>$rec["emphasized"]);
    }
    echo my_json_encode($ret);
}

function load_base_list($data) {
    $group_id = $data['group_id'];
    $ret = array();
    $sql = mysql_query("select id,name from filter where group_id='$group_id' and archived='no' order by name");
    while ($rec = mysql_fetch_array($sql)) {
        $ret[$rec[id]] = $rec['name'];
    }
    echo my_json_encode($ret);
}

function load_base_user_list($data) {
    $group_id = $data['group_id'];
    $ret = array();
    $sql = mysql_query("select u.id,concat(u.email,' <',u.name,'>') as name from members m, user u where m.group_id='$group_id' and m.membership in ('moderator','owner','support','sender') and m.user_id=u.id order by u.email");
    while ($rec = mysql_fetch_array($sql)) {
        $ret[$rec[id]] = htmlspecialchars($rec['name']);
    }
    echo my_json_encode($ret);
}

function show_word($data) {
    global $word;
    $key = $data['key'];
    if (!empty($key)) {
        echo $word[$key];
    }
    else {
        echo "";
    }
}

function my_json_encode($row) {
  $json = "{";
  $keys = array_keys($row);
  $i=1;
  foreach ($keys as $key) {
    if ($i>1) $json .= ',';
    $json .= '"'.addslashes($key).'":"'.addslashes($row[$key]).'"';
    $i++;
  }
  $json .= "}";
  return $json;
}


?>
