<?php
include_once "decode.php";

// if mime_part_id is given it means that this function
// should find th base64 encoded image and it should display it decoded.

function str_fetchstructure(&$string,$begin,$end,$mime_part_id="")
{
  global $_MX_var,$mime_types, $mime_encoding;
  global $_MX_var,$default, $lang, $textparts, $images, $atc, $msg, $images_not_inline, $index, $plaintext;

  settype($mime, 'object');
  $delim=chr(13).chr(10).chr(13).chr(10);
  if (strpos($string,$delim,$begin))
    $header_end=strpos($string,$delim,$begin)+2;
  elseif (strpos($string,"\n\n",$begin))
    $header_end=strpos($string,"\n\n",$begin)+1;
  else 
    return false; 
  $header=substr($string,$begin,$header_end-$begin);
  if (eregi("(Content-Type:)([^;\n]*)(;|\n)",$header,$reg))
  {
    $types=explode("/",$reg[2]);
    if (strlen($mime_types[trim(strtolower($types[0]))]))
        $mime->type=$mime_types[trim(strtolower($types[0]))];
    else
        $mime->type=TYPEOTHER;
    $mime->subtype=trim(strtolower($types[1]));
    if (strlen($mime->subtype))
      $mime->ifsubtype=1;
    if ($reg[3]==";")
    {
     $mime->ifparameters=1;
     $s=$reg[1].$reg[2].$reg[3];
     $sh=$header;
     $i=1;
     $reg[2]=$reg[3];
     while ($reg[2]==";")
      {
       $sh=ltrim(substr($sh,strpos($sh,$s)+strlen($s)));
       ereg("([^;\n]*)(;|\n)",$sh,$reg);
       $eq=strpos($reg[1],"=");
       settype($ps, 'object');
       $ps->attribute=trim(substr($reg[1],0,$eq));
       $ps->value=str_replace("\"","",trim(substr($reg[1],$eq+1)));
       $mime->parameters[$i]=$ps;
       $i++;
       if (strtolower($mime->parameters[$i-1]->attribute)=="boundary") 
          $boundary=$mime->parameters[$i-1]->value; 
       $s=$reg[1].$reg[2];
      }
    }
  }
  if (eregi("(Content-Disposition:)([^;\n]*)(;|\n)",$header,$reg))
  {
    $mime->ifdisposition=1;
    $mime->disposition=trim($reg[2]);
    if ($reg[3]==";")
    {
     $mime->ifdparameters=1;
     $s=$reg[1].$reg[2].$reg[3];
     $sh=$header;
     $i=1;
     $reg[2]=$reg[3];
     while ($reg[2]==";")
      {
       $sh=ltrim(substr($sh,strpos($sh,$s)+strlen($s)));
       ereg("([^;\n]*)(;|\n)",$sh,$reg);
       $eq=strpos($reg[1],"=");
       settype($ps, 'object');
       $ps->attribute=trim(substr($reg[1],0,$eq));
       $ps->value=str_replace("\"","",trim(substr($reg[1],$eq+1)));
       $mime->dparameters[$i]=$ps;
       $i++;
       $s=$reg[1].$reg[2];
      }
    }
  }

  if (eregi("Content-Description:([^;\n]*)(;|\n)",$header,$reg))
    $mime->description=trim($reg[1]);

  if (eregi("Content-Transfer-Encoding:([^;\n]*)(;|\n)",$header,$reg))
    $mime->encoding=$mime_encoding[strtolower(trim($reg[1]))];

  if (eregi("Content-ID:([^;\n]*)(;|\n)",$header,$reg))
    {
     $mime->ifid=1;
     $mime->id=trim($reg[1]);
     $mime->id=ereg_replace("^<","",$mime->id);
     $mime->id=ereg_replace(">$","",$mime->id);
    }

  if ($mime->type == TYPEMULTIPART)
   {
    $blen=strlen($boundary);
    $i=0;
    $attachments_end=0;
    $sub_begin=strpos($string,$boundary,$begin+strlen($header))+$blen;
    $sub_end=strpos($string,$boundary,$sub_begin);
    if (!$sub_end && (substr($string,$sub_begin,2) != "--"))
       {
        $sub_end=$end;
        $attachments_end=1;
       }
    while($sub_end)
    {
      $mime->parts[$i++]=str_fetchstructure($string,$sub_begin,$sub_end,$mime_part_id);
      $sub_begin=$sub_end+$blen;
      $sub_end=strpos($string,$boundary,min(strlen($string),$sub_begin));
      if (!$sub_end && (substr($string,$sub_begin,2) != "--") && !$attachments_end)
       {
        $sub_end=$end;
        $attachments_end=1;
       }
    }
   }
  else
  {
    $mime->part_begin=$begin+strlen($header);
    $mime->bytes=$end-($begin+strlen($header)+2);
        if (!empty($mime_part_id)) {
            if ($mime->id==$mime_part_id && $mime->type==TYPEIMAGE) {
               header("Content-Type: image/$mime->subtype");
               header("Content-Disposition: inline; filename=$mime_part_id.$mime->subtype");
               print base64_decode(trim(substr($string,$mime->part_begin,$mime->bytes)));
               exit;
            }
        }
        else {
			mimeParse($mime);
			if ($mime->type == TYPETEXT && 
				($mime->SUBTYPE == 'plain' || $mime->SUBTYPE == 'enriched' || $mime->SUBTYPE == 'x-unknown')
				&& $mime->bytes > 0
				// display only limited size parts inline
				&& ($default->text_inline_size == 0 || $mime->size <= $default->text_inline_size)
				&& ($mime->encoding == ENC7BIT || $mime->encoding == ENC8BIT || $mime->encoding == ENCQUOTEDPRINTABLE || $mime->encoding == ENCBASE64)
				&& ($textparts == 0 || $default->text_parts_inline)) {
				/* Safe to display, it is text */
				/* get and format the main body part */
                        $tmsg=trim(substr($string,$mime->part_begin,$mime->bytes));	
				/* If it's a quoted-printable document, translate it into the right charset */
                        if ($mime->encoding == ENCQUOTEDPRINTABLE) {
                              $tmsg = ereg_replace("=[\r]{0,1}\n","",$tmsg);
					$tmsg = quoted_printable_decode($tmsg);
				} elseif ($mime->encoding == ENCBASE64) {
					$tmsg = base64_decode($tmsg);
				}
	
				if (0 && strlen($tmsg) > 0) {
					$tmsg = str_replace('</A>', '</a>', $tmsg); // make sure that the original message doesn't contain any capital /A tags, so we can assume we generated them
					$tmsg = str_replace('<A', '<a', $tmsg);     // ditto for open <A tags
					$tmsg = preg_replace('|(\w+)://([^\s"<]*)([\w#?/&=])|', '<A href="\1://\2\3" target="_blank">\1://\2\3</A>', $tmsg);
					$tmsg = preg_replace('|[Mm][Aa][Ii][Ll][Tt][Oo]:(\s?)([\w+-=%&:_.~@]+[#\w+]*)|', 'mailto:\1<A href="javascript:open_compose_win(\'actionID=33&to=\2\'); ">\2</A>', $tmsg);
					$tmsg = htmlspecialchars($tmsg);
					$tmsg = str_replace('&lt;A href=&quot;', '<a href="', $tmsg);
					$tmsg = str_replace('&quot; target=&quot;_blank&quot;&gt;', '" target="_blank">', $tmsg);
					$tmsg = str_replace('&quot;&gt;','">', $tmsg);
					$tmsg = str_replace('\');&quot;&gt;', '\');">', $tmsg);
					$tmsg = str_replace('&lt;/A&gt;', '</a>', $tmsg); // only reconvert capital /A tags - the ones we generated
				}
				$charset = $mime->charset;
	
				$tmsg = '<pre>' . wrap_message($tmsg) . '</pre>';
                
				if ($textparts > 0) {
					$msg = $msg . '</td></tr><tr><td></td></tr><tr><td bgcolor="' . $default->text_bg . '">' . $tmsg;
				} else {
					$msg = $tmsg;
				}
				if ($default->inline_in_parts_list) {
					$atc .= mimeSummary($mime);
				}
				$textparts++;
	
				/* It may not be text directly, but if its configured to be displayed inline, inline it */
			} elseif (!empty($mime->conf['view']) && !empty($mime->conf['inline']) &&
					  ($mime->encoding == ENC7BIT || $mime->encoding == ENC8BIT || $mime->encoding == ENCQUOTEDPRINTABLE || $mime->encoding == ENCBASE64)
					  && isset($mime->conf['view_function'])
					  && $mime->disposition == 'inline' && !$plaintext
                            ) 
                        {
				if ($textparts > 0) {
					/* If this MIME-type is configured to override other things for display, do it */
					if (isset($mime->conf['override_text']) && $mime->conf['override_text']) {
						$msg = '';
					} else {
						$msg = $msg . '</td></tr><tr><td></td></tr><tr><td bgcolor="' . $default->text_bg . '">';
					}
				}
	
				$func = $mime->conf['view_function'];
				if ($mime->SUBTYPE == 'html')
					$msg .= $func($mime);
				else
					$msg .= '<pre>' . $func($mime) . '</pre>';
				$textparts++;
				
				/* add it as an attachment link to download if this type is downloadable */
				if (!empty($mime->conf['download']) && $default->inline_in_parts_list) {
					$atc .= mimeSummary($mime);
				}
			} else {
				if ($mime->ifid) 
                           $images[$mime->id] = "view.php?index=$index&mime_part_id=$mime->id";
				elseif (!$plaintext) {
                           $atc .= mimeSummary($mime);
                           $mname = decode_mime_string($mime->description);
                           if ($mime->type == TYPEIMAGE || ($mime->type == TYPEAPPLICATION && $mime->subtype == "octet-stream" && (ereg(".gif",$mname) || ereg(".jpg",$mname)))) {
                             //$images_not_inline[] = "view.php?index=$index&type=$mime->TYPE&subtype=$mime->SUBTYPE&name=".decode_mime_string($mime->description)."&begin=$mime->part_begin&bytes=$mime->bytes&encoding=$mime->encoding";
                            }
                        }
			}
      }
  }  // end else ($mime->type==TYPEMULTIPART)
 return $mime;
}

function mimeParse (&$mime) {
	global $_MX_var,$mime_actions, $mime_types, $mime_encoding, $lang;
	
	// set type information
	if (!isset($mime->type)) $mime->type = TYPETEXT;
	$mime->TYPE = isset($mime_types[$mime->type]) ? $mime_types[$mime->type] : $mime_types[TYPETEXT];
	$mime->SUBTYPE = ($mime->ifsubtype) ? strtolower($mime->subtype) : 'x-unknown';
	$mime->disposition = ($mime->ifdisposition) ? strtolower($mime->disposition) : 'inline';
	
	// set conf information
	if (isset($mime_actions["$mime->TYPE/$mime->SUBTYPE"]))
		$mime->conf = $mime_actions["$mime->TYPE/$mime->SUBTYPE"];
	elseif (isset($mime_actions["$mime->TYPE/"]))
		$mime->conf = $mime_actions["$mime->TYPE/"];
	elseif (isset($mime_actions['/']))
		$mime->conf = $mime_actions['/'];
	else
		$mime->conf = array('action' => 'unknown', 'icon' => 'mime_unknown.gif');
  
	// go through the paramters, if any
	if ($mime->ifparameters) {
		while (list(,$param) = each($mime->parameters)) {
			switch (strtolower($param->attribute)) {
			case 'charset':
				$mime->charset = $param->value;
				break;
			case 'name':
				$mime->name = $param->value;
				break;
			}
		}
	}
  
	// go through the dparameters, if any
	if ($mime->ifdparameters) {
		while (list(,$param) = each($mime->dparameters)) {
			switch (strtolower($param->attribute)) {
			case 'charset':
				$mime->charset = $param->value;
				break;
			case 'filename':
			case 'name':
				$mime->name = $param->value;
				break;
			}
		}
	}
  
	// make sure a charset is set
	if (empty($mime->charset)) {
		$mime->charset = 'ISO-8859-1';
	}
  
	// make sure there's a description
	if (empty($mime->description))
		if (!empty($mime->name))
			$mime->description = $mime->name;
		else
			$mime->description = '[' . $lang->no_description . ']';

	// make sure a name is set
	if (empty($mime->name))
		$mime->name = preg_replace('|\W|', '_', $mime->description);
	
	// make sure an encoding is set
	if (empty($mime->encoding))
		$mime->encoding = ENC7BIT;
	
	// set the size - bytes and kilobytes
	$mime->bytes = (isset($mime->bytes)) ? $mime->bytes : 0;
	$mime->size = (isset($mime->bytes)) ? sprintf('%0.2f', $mime->bytes/1024) : '?';
} // mimeParse()

function mimeSummary ($mime) {
  if (!($mime->size > 0)) return ''; // don't display zero-size attachments
  
  global $_MX_var,$default, $lang, $index;
  
  switch ($mime->conf['action']) {
  case 'function':
    $func = $mime->conf['function'];
    $row = $func($mime);
    break;
    
  default:
    // icon column
    $row = '<tr valign="center"><td>' .
      (($mime->conf['icon'] != '') ? 
       '<img src="' . $default->graphics_url . '/' . $mime->conf['icon'] . '" border=0 alt="' . $mime->TYPE . '/'. $mime->SUBTYPE . '">' :
       '&nbsp;' ) . '</td>';
    
    // number column
    $row .= '<td>' . $mime->imap_id . '</td>';
    
    // name/text part column
    $row .= '<td>';
    if ($mime->conf['view']) {
      //$row .= "<a href=\"view.php?index=$index&type=$mime->TYPE&subtype=$mime->SUBTYPE&name=".decode_mime_string($mime->description)."&begin=$mime->part_begin&bytes=$mime->bytes&encoding=$mime->encoding\">" . htmlspecialchars(decode_mime_string($mime->description)) . '</a>';
    }
    else
      $row .= htmlspecialchars(decode_mime_string($mime->description));
    $row .= '</td>';
    
    // type column
    $row .= '<td>' . $mime->TYPE . '/' . $mime->SUBTYPE . '</td>';
    
    // size column
    $row .= '<td>' . $mime->size . ' KB</td>';
    
    // download column
    if ($mime->conf['download']) {
      //$row .= "<td><a href=\"view.php?action=download&index=$index&type=$mime->TYPE&subtype=$mime->SUBTYPE&name=".decode_mime_string($mime->description)."&begin=$mime->part_begin&bytes=$mime->bytes&encoding=$mime->encoding\"><img src=\"$default->graphics_url/download.gif\" alt=\"$lang->download\" border=\"0\"></a></td>";
    }
    else
      $row .= '<td>&nbsp;</td>';
    
    $row .= "</tr>\n";
    break;
  }
  return $row;
} // mimeSummary()

function wrap_message ($text, $wrap=80, $break="\n") {
	$paragraphs = explode("\n", $text);
	for ($i = 0; $i < sizeof($paragraphs); $i++) {
		$paragraphs[$i] = wordwrap($paragraphs[$i], $wrap, $break);
	}
    return implode($break, $paragraphs);
}
/*
function wordwrap ($text, $wrap=80, $break="\n") {
		$len = strlen($text);
		if ($len > $wrap) {
			$result = '';
			$lastWhite = 0;
			$lastChar = 0;
			$lastBreak = 0;
      
			while ($lastChar < $len) {
				$char = substr($text, $lastChar, 1);
				if (($lastChar - $lastBreak > $wrap) && ($lastWhite > $lastBreak)) {
					$result .= substr($text, $lastBreak, ($lastWhite - $lastBreak)) . $break;
					$lastChar = $lastWhite + 1;
					if (substr($text, $lastChar, 1) == $break) $lastChar++;
					$lastBreak = $lastChar;
				}
	 
				if ($char == ' ' || $char == chr(13) || $char == chr(10)) { $lastWhite = $lastChar; }
				$lastChar = $lastChar + 1;
			}
			return($result . substr($text, $lastBreak));
		}
		else { return($text); }
	}*/

function mime_view_html ($mime) {
   $data = MimeFetchDecodedContent($mime);
   
   /* now render impotent evil client-side-executable code */
   // $data = preg_replace("|<([^>]*)script|i", "<imp_cleaned_script_tag", $data);
   $data = preg_replace("|<script|i", "<imp_cleaned_script_tag", $data);
   $data = preg_replace("|<([^>]*)embed|i", "<imp_cleaned_embed_tag", $data);
   $data = preg_replace("|<([^>]*)java|i", "<imp_cleaned_java_tag", $data);
   $data = preg_replace("|<([^>]*)object|i", "<imp_cleaned_object_tag", $data);
  #   $data = preg_replace("|<([^>]*)style|i", "<imp_cleaned_style_tag", $data);
   $data = preg_replace("|href=\"(.*)script:|i", "href=\"imp_cleaned_script:", $data);
   
   /* check for {..} and mocha urls, re: recent bugtraq advisory */
   $data = preg_replace("|<([^>]*)&{.*}([^>]*)>|i", "<&{;}\\3>", $data);
   $data = preg_replace("|<([^>]*)mocha:([^>]*)>|i", "<imp_cleaned_mocha:\\2>", $data);
   
   return $data;
}

function mime_view_text ($mime) {
  $text = MimeFetchDecodedContent($mime);
  
  $text = wrap_message($text, 85);
  $paras = explode("\n", $text);
  
  $result = array();
  $i = 0;
  while ($i < count($paras)) {
    if (strlen($paras[$i]) <= 85) {
      $result[] = $paras[$i];
      $i++;
    } else {
      $result[] = substr($paras[$i], 0, 85);
      $new = trim(substr($paras[$i], 85, strlen($paras[$i]) - 85));
      if ($new != '') $paras[$i] = $new;
      else $i++;
    }
  }
  $text = implode("\n", $result);
  
  $text = str_replace('</A>', '</a>', $text); // make sure that the original message doesn't contain any capital /A tags, so we can assume we generated them
  $text = str_replace('<A', '<a', $text);     // ditto for open <A tags
  $text = preg_replace('|(\w+)://([^\s"<]*)([\w#?/&=])|', '<A href="\1://\2\3" target="_blank">\1://\2\3</A>', $text);
  $text = preg_replace('|[Mm][Aa][Ii][Ll][Tt][Oo]:(\s?)([\w+-=%&:_.~@]+[#\w+]*)|', 'mailto:\1<A href="javascript:open_compose_win(\'actionID=33&to=\2\'); ">\2</A>', $text);
  $text = htmlspecialchars($text);
  $text = str_replace('&lt;A href=&quot;', '<a href="', $text);
  $text = str_replace('&quot; target=&quot;_blank&quot;&gt;', '" target="_blank">', $text);
  $text = str_replace('&quot;&gt;','">', $text);
  $text = str_replace('\');&quot;&gt;', '\');">', $text);
  $text = str_replace('&lt;/A&gt;', '</a>', $text); // only reconvert capital /A tags - the ones we generated
  
  return $text;
}

function MimeFetchDecodedContent ($mime) {
	global $_MX_var,$string;
	if (isset($mime->encoding) && $mime->encoding == ENCBASE64) {
		return base64_decode(trim(substr($string,$mime->part_begin,$mime->bytes)));
	} elseif (isset($mime->encoding) && $mime->encoding == ENCQUOTEDPRINTABLE) {
		$raw = trim(substr($string,$mime->part_begin,$mime->bytes));
            $raw = ereg_replace("=[\r]{0,1}\n","",$raw);
		$data = quoted_printable_decode($raw);
		if (empty($data))
			$data = $raw;
		return $data;
	} else {
		return trim(substr($string,$mime->part_begin,$mime->bytes));
	}
} // MimeFetchDecodedContent()

$mime_types =
array(
      TYPETEXT => 'text', 'text' => TYPETEXT,
      TYPEMULTIPART => 'multipart', 'multipart' => TYPEMULTIPART,
      TYPEMESSAGE => 'message', 'message' => TYPEMESSAGE,
      TYPEAPPLICATION => 'application', 'application' => TYPEAPPLICATION,
      TYPEAUDIO => 'audio', 'audio' => TYPEAUDIO,
      TYPEIMAGE => 'image', 'image' => TYPEIMAGE,
      TYPEVIDEO => 'video', 'video' => TYPEVIDEO,
      TYPEOTHER => 'unknown', 'unknown' => TYPEOTHER
      );

$mime_encoding =
array(
      ENC7BIT => '7bit', '7bit' => ENC7BIT,
      ENC8BIT => '8bit', '8bit' => ENC8BIT,
      ENCBINARY => 'binary', 'binary' => ENCBINARY,
      ENCBASE64 => 'base64', 'base64' => ENCBASE64,
      ENCQUOTEDPRINTABLE => 'quoted-printable', 'quoted-printable' => ENCQUOTEDPRINTABLE,
      ENCOTHER => 'unknown', 'unknown' => ENCOTHER
      );

$mime_actions =
array(
      'application/x-gzip' =>
      array('action' => 'default',
	    'view' => false,
	    'download' => true,
	    'icon' => 'mime_compressed.gif'),
      
      'application/x-imp-signature' =>
      array('action' => 'function',
	    'function' => 'mime_action_ximpsignature',
	    'view' => false,
	    'view_function' => 'mime_view_ximpsignature',
	    'download' => false,
	    'icon' => 'mime_imp_signature.gif'),
 
      'application/pgp-signature' =>
      array('action' => 'default',
            'view' => true,
            'view_function' => 'mime_view_text',
            'download' => true,
            'icon' => 'mime_imp_signature.gif'),   

      'application/pgp-keys' =>
      array('action' => 'default',
            'view' => true,
            'view_function' => 'mime_view_text',
            'download' => true,
            'icon' => 'mime_text.gif'),
     
      'application/' =>
      array('action' => 'default',
	    'view' => false,
	    'download' => true,
	    'icon' => 'mime_text.gif'),
      
      'image/' =>
      array('action' => 'default',
	    'view' => true,
	    'download' => true,
	    'icon' => 'mime_image.gif'),
      
      'message/source' =>
      array('action' => 'default',
	    'view' => true,
	    'download' => true,
	    'icon' => 'source.gif'),
      
      'message/' =>
      array('action' => 'default',
	    'view' => false,
	    'download' => true,
	    'icon' => 'mime_mail.gif'),
      
      'text/html' =>
      array('action' => 'default',
	    'view' => true,
	    'inline' => true,
	    'view_function' => 'mime_view_html',
	    'override_text' => true,
	    'download' => true,
	    'icon' => 'mime_html.gif'),
      
      'text/plain' =>
      array('action' => 'default',
	    'view' => true,
	    'download' => true,
	    'icon' => 'mime_text.gif'),
      
      '/' => array('action' => 'default',
		   'view' => true,
		   'download' => true,
		   'icon' => 'mime_text.gif'));

settype($default,'object');
$default->text_parts_inline = true;
$default->graphics_url = 'graphics';
$default->text_parts_inline = true;
$default->inline_in_parts_list = false;
$default->text_inline_size = 0; // how big a part can be (in k) before we won't
$default->text_bg='cccccc';

settype($lang,'object');
$lang->no_description='no description';
$lang->download='download';

?>
