<? session_start();
include "../include/_config.php"; 
$_homedir="/var/www/maxima.hu/www/www/";
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title id="page-title">Maxima Admin</title>



    <!-- ** CSS ** -->
    <!-- base library -->
    <link rel="stylesheet" type="text/css" href="js/resources/css/ext-all.css" />
    <link rel="stylesheet" type="text/css" href="js/ux/treegrid/treegrid.css" rel="stylesheet" />

    <!-- overrides to base library -->
    <link rel="stylesheet" type="text/css" href="js/ux/css/CenterLayout.css" />

    <!-- page specific -->
    <link rel="stylesheet" type="text/css" href="layout-browser.css">
    <link rel="stylesheet" type="text/css" href="$_homedir/admin/js/ux/css/tab-scroller-menu.css">
    
    <!-- custom saját css -->
    <link rel="stylesheet" type="text/css" href="$_homedir/admin/js/custom_css/examples.css">
    <link rel="stylesheet" type="text/css" href="$_homedir/admin/js/custom_css/custom.css">


    <!-- ** Javascript ** -->
    <!-- ExtJS library: base/adapter -->
    <script type="text/javascript" src="js/adapter/ext/ext-base.js"></script>
	<!--
    <script type="text/javascript" src="js/adapter/ext/ext-base-debug.js"></script>
	-->	
	
    <!-- ExtJS library: all widgets -->    
    <script type="text/javascript" src="js/ext-all.js"></script>
    <!--
    <script type="text/javascript" src="js/ext-all-debug.js"></script>
	-->
	
	
    <!-- overrides to base library -->

    <!-- extensions -->
    <script type="text/javascript" src="js/ux/CenterLayout.js"></script>
    <script type="text/javascript" src="js/ux/RowLayout.js"></script>
    <script type="text/javascript" src="js/ux/SearchField.js"></script>
    <script type="text/javascript" src="js/ux/RowExpander.js"></script>
    <script type="text/javascript" src="js/ux/TabCloseMenu.js"></script>
    <script type="text/javascript" src="js/ux/TabScrollerMenu.js"></script>

	<!-- TreeGrid -->
    <script type="text/javascript" src="js/ux/treegrid/TreeGridSorter.js"></script>
    <script type="text/javascript" src="js/ux/treegrid/TreeGridColumnResizer.js"></script>
    <script type="text/javascript" src="js/ux/treegrid/TreeGridNodeUI.js"></script>
    <script type="text/javascript" src="js/ux/treegrid/TreeGridLoader.js"></script>
    <script type="text/javascript" src="js/ux/treegrid/TreeGridColumns.js"></script> 
    <script type="text/javascript" src="js/ux/treegrid/TreeGrid.js"></script>

	<!-- CheckTreePanel -->
	<script type="text/javascript" src="js/ux/CheckColumn.js"></script> 
    <script type="text/javascript" src="js/ux_innenonnan/checktree/js/Ext.ux.tree.CheckTreePanel.js"></script>
    <link rel="stylesheet" type="text/css" href="js/ux_innenonnan/checktree/css/Ext.ux.tree.CheckTreePanel.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="js/ux_innenonnan/checktree/css/checktree.css" rel="stylesheet" />
    
    <!-- RowAction -->
    <script type="text/javascript" src="js/ux_innenonnan/rowactions/js/Ext.ux.grid.RowActions.js"></script>
    <link rel="stylesheet" type="text/css" href="js/ux_innenonnan/rowactions/css/Ext.ux.grid.RowActions.css" rel="stylesheet" />


	<script type="text/javascript" src="js/ux/fileuploadfield/FileUploadField.js"></script>

	<?php
	
	if (!isset($_SESSION['admin_id'])){
	echo "<script type=\"text/javascript\" src=\"js/login.js\"></script>";	
	echo "<div id='loginFormId'></div>";
	}
	?>
	<script type="text/javascript">
		var clientIP = '<?=$_SERVER["HTTP_X_FORWARDED_FOR"] ?>';
		var tesztIP = '213.181.207.70';		
		var adminName = '<?=$_SESSION["admin_username"]?>';
		var adminLevel = <?=(int)$_SESSION["admin_level"]?>;
		var p_reference_types = <?
			$p_ref_types = $p_reference_types;
			if(count($p_ref_types)){
			foreach ($p_ref_types as $id => $type){
				$p_ref_types[$id][name] = iconv("ISO-8859-2", "UTF-8", $type[name]);
			}
			}
			echo json_encode($p_ref_types);
		?>
	</script>

    <!-- page specific -->
    <script type="text/javascript" src="js/maxima_references.js"></script>
    <script type="text/javascript" src="js/maxima_maineditor.js"></script>
    <script type="text/javascript" src="js/maxima_tabs.js"></script>
    <script type="text/javascript" src="js/admin.js"></script>
    <script type="text/javascript" src="js/commonShared.js"></script>


<style type="text/css">
    .x-selectable, .x-selectable * {
        -moz-user-select: text!important;
        -khtml-user-select: text!important;
    }

.ext-el-mask {
    opacity: 0.5; //for instance 10 az atlatszatlan 0.2 a default
}
</style>


</head>
<body>



    <div id="header"><h1>Maxima Admin</h1></div>
</body>
</html>
