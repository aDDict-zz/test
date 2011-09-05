<?

function mx_mail_generator($base_id,$format,$export=0) {

    error_reporting(0);

    $errors=array();
    $contentlist_plain=array();
    $contentlist_html=array();

    $base_id=intval($base_id);
    $res=mysql_query("select * from sender_base where id='$base_id'");
    if ($res && mysql_num_rows($res)) {
        $mdata=mysql_fetch_array($res);
    }
    else {
        exit;
    }

    if ($format!="mime") {
        $format="html";
    }
    $html=$mdata["html"];
    $res = mysql_query("select property,value from sender_base_property where sender_base_id='$base_id'");
    if ($res && mysql_num_rows($res)) {
        while ($kk = mysql_fetch_array($res)) {
            $html = eregi_replace("{css-$kk[property]}",$kk["value"],$html);
        }
    }
    if ($format=="html") {
        print $html;
        exit;
    }
    $delim1=substr(md5(time().$base_id."akarmimasegyebvalami"),2,12);
    $delim2=$delim1."a";
    $delim1=$delim1."b";

    // prepare contents data
    $r2=mysql_query("select name from sender_contents where group_id='$mdata[group_id]'");
    if ($r2 && mysql_num_rows($r2)) {
        while ($k2=mysql_fetch_array($r2)) {
            if (strpos($mdata["plain"],"{c-"."$k2[name]}")!==false) {
                $contentlist_plain[]=$k2["name"];
                $mdata["plain"]=str_replace("{c-"."$k2[name]}","{p-"."$k2[name]}",$mdata["plain"]);
            }
            if (strpos($html,"{c-"."$k2[name]}")!==false) {
                $contentlist_html[]=$k2["name"];
                $html=str_replace("{c-"."$k2[name]}","\n{h-"."$k2[name]}\n",$html);
            }
        }
    }
    $i=0;
    $imgparts=0;
    $imgurls=array();
    $imgcid=array();
    $imgconttype=array();
    $imgext=array();
    $adslist=array(); 
    while (eregi("(<img[^>]+src=['\"]?)(http://[^'\">]+)(['\"]?[^>]*>)",$html,$regs) && $i<1000) {
        $rpl="";
        $i++;
        $imgurls[$imgparts]="$regs[2]";
        $randcid=mt_rand(0,999999);
        $imgcid[$imgparts]="mx$imgparts"."q$base_id$randcid";
        $idataext="";
        if (eregi("\.([a-z]+)$",$regs[2],$irg)) {
            $idataext=$irg[1];
        }
        if (strtolower($idataext)=="jpg" || strtolower($idataext)=="jpeg") {
            $imgconttype[$imgparts]="image/jpeg";
        }
        else { // not quite right, but will hold for now.
            $imgconttype[$imgparts]="image/gif";
        }
        $imgext[$imgparts]=$idataext;
        $rpl="$regs[1]cid:$imgcid[$imgparts]$regs[3]";
        $imgparts++;
        $html=str_replace($regs[0],$rpl,$html);
    }
    while (eregi("{adslot-([0-9]+)}",$html,$regs) && $i<100) {
        $rpl="";
        $i++;
        $imgurls[$imgparts]="{ad-img-$regs[1]}";
        $randcid=mt_rand(0,999999);
        $imgcid[$imgparts]="mx$imgparts"."q$base_id$randcid";
        $imgconttype[$imgparts]="image/{ad-imgtype-$regs[1]}";
        $imgext[$imgparts]="{ad-imgtype-$regs[1]}";
        $rpl="{ad-link-$regs[1]}";
        $html=str_replace($regs[0],$rpl,$html);
        $adslist[]="$regs[1]";
        $imgparts++;
    }

    $mime_header="Mime-version: 1.0
Content-Type: multipart/related;
  boundary=\"----=_NextPart_001_$delim2\"";

    $encoded="This is a multi-part message in MIME format.

------=_NextPart_001_$delim2
Content-Type: multipart/alternative;
    boundary=\"----=_NextPart_000_$delim1\"

------=_NextPart_000_$delim1
Content-Type: text/plain; charset=\"utf-8\"
Content-Transfer-Encoding: quoted-printable

".mx_qp_encode($mdata["plain"])."

------=_NextPart_000_$delim1
Content-Type: text/html; charset=\"utf-8\"
Content-Transfer-Encoding: quoted-printable

".mx_qp_encode($html)."

------=_NextPart_000_$delim1--\n\n";

    $adimglist="";
    for ($i=0;$i<$imgparts;$i++) {
        if (ereg("^.ad-",$imgurls[$i])) {
            $adimglist.="$imgurls[$i]";
        }
        elseif ($fp=fopen($imgurls[$i],"rb")) {
            $sh="\n------=_NextPart_001_$delim2
Content-Type: $imgconttype[$i]; 
        name=\"$imgcid[$i].$imgext[$i]\"
Content-Transfer-Encoding: base64
Content-Disposition: inline
Content-Id: <$imgcid[$i]>\n\n";
            $encoded.=$sh;
	    $img="";
            while (!feof($fp)) {
                 $img .= fread($fp, 8192);
            }
            $sh=chunk_split(base64_encode($img));
            fclose($fp);
            $encoded.=$sh;
        }
        else {
            $errors[]="File open error: ". htmlspecialchars($imgurls[$i]);
        }
    }
    foreach ($contentlist_html as $ch_image) {
        $encoded.="{h-"."$ch_image-i}";
    }
    $encoded.="$adimglist\n\n------=_NextPart_001_$delim2--\n\n";

    if (count($errors)==0) {
        if ($export) {
            mysql_query("update sender_base set mime='". mysql_escape_string($encoded) ."',
                         mime_header='". mysql_escape_string($mime_header) ."',
                         contentlist_plain='". mysql_escape_string(implode("|",$contentlist_plain)) ."',
                         contentlist_html='$delim2|". mysql_escape_string(implode("|",$contentlist_html)) ."',
                         adslist='". mysql_escape_string(implode("|",$adslist)) ."'
                         where id='$base_id'");
        }
        else {
            print nl2br(htmlspecialchars("$mime_header\n\n$encoded"));
        }
    }
    elseif ($export==0) {
        print implode("<br>",$errors)."<br>images to encode: <br>".implode("<br>",$imgurls);
    }
    
    error_reporting(7);

    return array(count($errors),implode(" ",$errors),$imgurls,$contentlist_plain,$contentlist_html);
}

function mx_qp_encode($Message) {
  
   /* Build (most polpular) Extended ASCII Char/Hex MAP (characters >127 & <255) */
   for ($i=0; $i<127; $i++) {
       $CharList[$i] = "/".chr($i+128)."/";
       $HexList[$i] = "=".strtoupper(bin2hex(chr($i+128)));
   }

   /* Encode equal sign & 8-bit characters as equal signs followed by their hexadecimal values */
   $Message = str_replace("=", "=3D", $Message);
   $Message = preg_replace($CharList, $HexList, $Message);

   /* Lines longer than 76 characters (size limit for quoted-printable Content-Transfer-Encoding)
       will be cut after character 75 and an equals sign is appended to these lines. */
   $MessageLines = split("\n", $Message);
   $Message_qp = "";
   while(list(, $Line) = each($MessageLines)) {
       if (strlen($Line) > 75) {
           $Pointer = 0;       
           while ($Pointer <= strlen($Line)) {
               $Offset = 0;
               if (preg_match("/^=(3D|([8-9A-F]{1}[0-9A-F]{1}))$/", substr($Line, ($Pointer+73), 3))) $Offset=-2;
               if (preg_match("/^=(3D|([8-9A-F]{1}[0-9A-F]{1}))$/", substr($Line, ($Pointer+74), 3))) $Offset=-1;
               $Message_qp.= substr($Line, $Pointer, (75+$Offset))."=\n";
               if ((strlen($Line) - ($Pointer+75)) <= 75) {               
                   $Message_qp.= substr($Line, ($Pointer+75+$Offset))."\n";
                   break 1;
               }
               $Pointer+= 75+$Offset;
           }
       } else {
           $Message_qp.= $Line."\n";
       }
   }       
   return rtrim($Message_qp);
}

function list_uploaded($base_id,$type="upload_image") {
    global $word,$_MX_var;
    switch($type){
        case "upload_image":$dtype="images";$stype="image";break;
        case "upload_attachment":$dtype="attachments";$stype="attachment";break;
    }
    $sql=mysql_query("select * from sender_base_uploaded_files where base_id='$base_id' and type='$stype' order by id");
    $ret="";
    while ($rec=mysql_fetch_array($sql)) {
        $path = explode('/',$rec['path']);
        $filename = array_pop($path);
        $ret .= "<span>";
        $ret .= "$filename ($rec[filesize] byte) &nbsp; ";
        $ret .= "[ ";
        if (!empty($rec['width']) && !empty($rec['height'])) {
            $ret .= "<a href='javascript:;' onclick='img_preview(\"$_MX_var->uploadurl$rec[path]\");'>$word[preview_image]</a>";
        }
        elseif ($rec['type']=="attachment") {
            $ret .= "<a href='$_MX_var->uploadurl$rec[path]'>$word[preview_file]</a>";
        }
        if ($dtype=="images") {
            $ret .= " / <a href='javascript:;' onclick='img_tag_paste(\"$_MX_var->uploadurl$rec[path]\",\"$rec[width]\",\"$rec[height]\");'>$word[image_tag_paste]</a>";
        }
        $delstring = $word["delete_$type"];
        $ret .= " / <a href='javascript:;' onclick='delete_uploaded($rec[id],$(this),\"$delstring\",\"\");'>$word[delete]</a>";
        $ret .= " ]<br></span>";
    }
    return $ret;
}

?>
