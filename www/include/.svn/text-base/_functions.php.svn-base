<?php
function deekezet($s){
  $s = ltrim(rtrim($s));
  $s = strtolower($s);
  $s = str_replace("&#8482;","",$s);

  $s = str_replace("","",$s);
//  $s = str_replace("-","_",$s);
  $s = str_replace("(","",$s);
  $s = str_replace(")","",$s);
  $s = str_replace("  "," ",$s);
  $s = str_replace("~","",$s);
  $s = str_replace(" ","-",$s);
  $s = str_replace("&","",$s);
  $s = str_replace("*","",$s);
  $s = str_replace("+","-",$s);
  $s = str_replace(".","",$s);
  $s = str_replace(",","-",$s);
  $s = str_replace("/","-",$s);

  $chr['á'] = "a";
  $chr['é'] = "e";
  $chr['í'] = "i";
  $chr['ó'] = "o";
  $chr['ö'] = "o";
  $chr['ő'] = "o";
  $chr['ú'] = "u";
  $chr['ü'] = "u";
  $chr['ű'] = "u";

  $chr['Á'] = "a";
  $chr['É'] = "e";
  $chr['Í'] = "i";
  $chr['Ó'] = "o";
  $chr['Ö'] = "o";
  $chr['Ő'] = "o";
  $chr['Ú'] = "u";
  $chr['Ü'] = "u";
  $chr['Ű'] = "u";

  for ($i = 0; $i < strlen($s); $i++) {
    if ($chr[$s[$i]])
      $s[$i] = $chr[$s[$i]];
    elseif (ord($s[$i]) == "0153")
      $s[$i] = "";
    elseif (ord($s[$i]) == "8482")
      $s[$i] = "";
    elseif (ord($s[$i]) == "0174")
      $s[$i] = "";
  }

  $s=ereg_replace("[^a-z0-9_-]","",$s);

  while(strstr($s,"--")) $s=str_replace("--","-",$s);

  return $s;
}

?>