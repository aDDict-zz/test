<?
$file = $_REQUEST["file"];
if (preg_match("/^https?:\/\//i", $file)) {
    if ($handle = fopen($file, "rb")) {
    header("Content-type: text/html; charset=utf-8" );
        while (!feof($handle)) {
          print fread($handle, 8192);
        }
        fclose($handle);
    }
}
?>
