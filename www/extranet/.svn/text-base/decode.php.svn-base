<?  
function decode_mime_string ($string,$trunc=0) {
  $pos = strpos($string, '=?');
  if (!is_int($pos)) {
    return $string;
  }
  
  // take out any spaces between multiple encoded words
  $string = preg_replace('|\?=\s+=\?|', '?==?', $string);
  
  $preceding = substr($string, 0, $pos); // save any preceding text
  
  $search = substr($string, $pos+2, 86); // the mime header spec says this is the longest a single encoded word can be
  $d1 = strpos($search, '?');
  if (!is_int($d1)) {
    if ($trunc==1)
      return "";
    else
      return $string;
  }
  
  $charset = substr($string, $pos+2, $d1);
  $search = substr($search, $d1+1);
  
  $d2 = strpos($search, '?');
  if (!is_int($d2)) {
    if ($trunc==1)
      return "";
    else
      return $string;
  }
  
  $encoding = substr($search, 0, $d2);
  $search = substr($search, $d2+1);
  
  $end = strpos($search, '?=');
  if (!is_int($end)) {
    if ($trunc==1)
      return "";
    else
      return $string;
  }
  
  $encoded_text = substr($search, 0, $end);
  $rest = substr($string, (strlen($preceding . $charset . $encoding . $encoded_text)+6));
  
  switch ($encoding) {
  case 'Q':
  case 'q':
    $encoded_text = str_replace('+', '%2B', $encoded_text);
    $encoded_text = str_replace('_', '%20', $encoded_text);
    $encoded_text = str_replace('=', '%', $encoded_text);
    $decoded = urldecode($encoded_text);
    if (!eregi("utf-?8",$charset)) {
      $decoded = @iconv($charset,"utf-8//IGNORE",$decoded);
    }
    break;
    
  case 'B':
  case 'b':
    $decoded = urldecode(base64_decode($encoded_text));
    if (!eregi("utf-?8",$charset)) {
      $decoded = @iconv($charset,"utf-8//IGNORE",$decoded);
    }
    break;
    
  default:
    $decoded = '=?' . $charset . '?' . $encoding . '?' . $encoded_text . '?=';
    break;
  }
  
  return $preceding . $decoded . decode_mime_string($rest,1);
} // decode_mime_string()
?>
