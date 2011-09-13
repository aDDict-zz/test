<?
if ($report!=3) {
    $tpl->parse("MAIN",".statistics");
    $tpl->FastPrint("MAIN");
    include "footer.php";
}
?>
