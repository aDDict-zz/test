<?php /* Smarty version 2.6.6, created on 2010-01-12 13:24:56
         compiled from agencies.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'agencies.html', 451, false),)), $this); ?>
<?php echo '
<script language="javascript" type="text/javascript" src="../templates/admin/common.js"></script>
<script language="javascript">
function delAgency(agencyID, agencyName){
	if(confirm(\'Biztos benne, hogy torli a(z) `\' + agencyName + \'` hriforrast?\')){
		document.forms[\'del_agency_\'+agencyID].submit();		
	}
	return false;
}
function showAgencyModProcess(request, data){
	var td = data[0];
	var answer = request.responseText;
	td.innerHTML = answer;
}
function closeAgencyMod(agencyID, object){
	object.innerHTML = "M&oacute;dos&iacute;t";
	object.onclick = function(){
		showAgencyMod(agencyID, this);return false;
	}
	var tr = document.getElementById(\'a_\' + agencyID);
	var table = tr.parentNode;
	table.removeChild(document.getElementById(\'tr_\' + agencyID));
}
function showAgencyMod(agencyID, object){	
	object.innerHTML = "Bez&aacute;r";
	object.onclick = function(){
		closeAgencyMod(agencyID, this);return false;
	}
	var tr = document.getElementById(\'a_\' + agencyID);
	var table = tr.parentNode;
	
	var trMod = document.createElement(\'tr\');
	trMod.id = "tr_" + agencyID;
	
	table.insertBefore(trMod, tr.nextSibling);
	var td = document.createElement(\'td\');
	td.colSpan = 5;
	trMod.appendChild(td);	
	var pars = "action=get_agency_prop&agency_id=" + agencyID;
	var url = "ajax.php";
	var data = Array();
	data[0] = td;
	Ajax.send(url, "POST", pars, showAgencyModProcess, data);	
}
function updateAgencyProcess(request, data){
	if(request.responseText=="-1") alert(\'Mar van ilyen hirforras!\');
		else{
			var tr = document.getElementById(\'a_\' + data[0]);
			var tds = tr.getElementsByTagName(\'td\');
			tds[1].innerHTML = \'<a href="\' + data[2] + \'" target="_blank">\' + data[1] + \'</a>\';
			if(data[3]==1) tds[2].innerHTML = \'RSS\';	
				else if(data[3]==2) tds[2].innerHTML = \'HTML\';	
			tds[4].innerHTML = \'[ <a href="#" onClick="closeAgencyMod(\\\'\' + data[0] + \'\\\', this);return false;">M&oacute;dos&iacute;t</a> ] [ <a href="#" onClick="delAgency(\\\'\' + data[0] + \'\\\', \\\'\' + data[1] +\'\\\');">T&ouml;r&ouml;l</a> ]\';	
		}
}
function updateAgency(agencyID){
	var agencyName = document.getElementById(\'agency_name_\' + agencyID).value;
	var agencyURL = encodeURIComponent(document.getElementById(\'agency_url_\' + agencyID).value);
	var description = document.getElementById(\'description_\' + agencyID).value;
	var agencyFavicon = document.getElementById(\'agency_favicon_\' + agencyID).value;
	var agencyType = document.getElementById(\'agency_type_\' + agencyID).options[document.getElementById(\'agency_type_\' + agencyID).selectedIndex].value;	
	var pars = "action=update_agency&agency_id=" + agencyID + \'&agency_name=\' + agencyName + \'&agency_url=\' + agencyURL + \'&description=\' + description + \'&agency_type=\' + agencyType + \'&agency_favicon=\' + encodeURIComponent(agencyFavicon);
	var url = "ajax.php";	
	var data = Array();
	data[0] = agencyID;
	data[1] = agencyName;
	data[2] = agencyURL;
	data[3] = agencyType;
	Ajax.send(url, "POST", pars, updateAgencyProcess, data);
}
function closeRSSMod(rssID, agencyID, object){
	object.innerHTML = \'M&oacute;dos&iacute;t\';
	object.onclick = function(){
		showRSSMod(rssID, agencyID, this);return false;
	}
	var tr = document.getElementById(\'rss_\' + rssID  + \'_\' + agencyID);
	var table = tr.parentNode;
	table.removeChild(document.getElementById(\'tr_\' +  rssID  + \'_\' + agencyID));
}
function showRSSModProcess(request, data){
	var td = data[0];
	var answer = request.responseText;
	td.innerHTML = answer;
}

function showRSSMod(rssID, agencyID, object){
	object.innerHTML = \'Bez&aacute;r\';
	object.onclick = function(){
		closeRSSMod(rssID, agencyID, this);return false;
	}
	var tr = document.getElementById(\'rss_\' + rssID  + \'_\' + agencyID);
	var table = tr.parentNode;
	
	var trMod = document.createElement(\'tr\');
	trMod.id = "tr_" +  rssID  + \'_\' + agencyID;
	
	table.insertBefore(trMod, tr.nextSibling);
	var td = document.createElement(\'td\');
	td.colSpan = 4;
	trMod.appendChild(td);	

	var pars = "action=get_rss_prop&rss_id=" + rssID + "&agency_id=" + agencyID;
	var url = "ajax.php";
	var data = Array();
	data[0] = td;
	Ajax.send(url, "POST", pars, showRSSModProcess, data);	
}
function changeRSSType(object, rssID, agencyID){
	var table = document.getElementById(\'rss_type_\' + rssID + \'_\' + agencyID);
	if(object.options[object.selectedIndex].value==1){
		table.style.display = "none";
		table.style.visibility = "hidden";
	}else if(object.options[object.selectedIndex].value==2){
		table.style.display = "block";
		table.style.visibility = "visible";
	}
}

function updateRSSProcess(request, data){
	if(request.responseText==-1) alert(\'Mar van ilyen hirfolyam\');
		else{
			var rssID  = data[0];
			var agencyID = data[1];
			var rssURL = data[2];
			var rssName = data[3];
			var tr = document.getElementById(\'rss_\' + rssID + \'_\' + agencyID);
			var tds = tr.getElementsByTagName(\'td\');
			tds[1].innerHTML = \'<a href="\' + rssURL + \'" target="_blank">\' + rssName + \'</a>\'
			tds[3].innerHTML = \'[ <a href="#" onclick="delRSS(\\\'\' + rssID + \'\\\', \\\'\' + agencyID + \'\\\', \\\'\' + rssName + \'\\\'); return false;">T&ouml;r&ouml;l</a> ]\';
		}
}

function updateRSS(rssID, agencyID){		
	var agency = document.getElementById(\'agencies_\' + rssID + \'_\' + agencyID).options[document.getElementById(\'agencies_\' + rssID + \'_\' + agencyID).selectedIndex].value;
	var rssType = document.getElementById(\'type_\' + rssID + \'_\' + agencyID).options[document.getElementById(\'type_\' + rssID + \'_\' + agencyID).selectedIndex].value;
	var rssName = document.getElementById(\'rss_name_\' + rssID + \'_\' + agencyID).value;
	if(rssName==""){
		alert(\'Ures rss nev!\');
		document.getElementById(\'rss_name_\' + rssID + \'_\' + agencyID).focus();
		return false;
	}
	var rssURL = encodeURIComponent(document.getElementById(\'rss_url_\' + rssID + \'_\' + agencyID).value);
	if(rssURL==""){
		alert(\'Ures rss url!\');
		document.getElementById(\'rss_url_\' + rssID + \'_\' + agencyID).focus();
		return false;
	}
	var description = document.getElementById(\'description_\' + rssID + \'_\' + agencyID).value;
	var pattern = encodeURIComponent(document.getElementById(\'pattern_\' + rssID + \'_\' + agencyID).value);
	if(rssType==2 && pattern==""){
		alert(\'Ures reguralis kifejezes!\');
		document.getElementById(\'pattern_\' + rssID + \'_\' + agencyID).focus();
		return false;
	}	
	var auxURL = document.getElementById(\'aux_url_\' + rssID + \'_\' + agencyID).value;			
	var matchLink = document.getElementById(\'match_link_\' + rssID + \'_\' + agencyID).value;
	if(rssType==2 && matchLink==""){
		alert(\'Ures link talalat!\');
		document.getElementById(\'match_link_\' + rssID + \'_\' + agencyID).focus();
		return false;
	}
	if(rssType==2 && !IsNumeric(matchLink)){
		alert(\'A link talalat csak numerikus ertek lehet!\');
		document.getElementById(\'match_link_\' + rssID + \'_\' + agencyID).focus();
		return false;
	}
	var matchTitle = document.getElementById(\'match_title_\' + rssID + \'_\' + agencyID).value;
	if(rssType==2 && matchTitle==""){
		alert(\'Ures cim talalat!\');
		document.getElementById(\'match_title_\' + rssID + \'_\' + agencyID).focus();
		return false;
	}
	if(rssType==2 && !IsNumeric(matchTitle)){
		alert(\'A cim talalat csak numerikus ertek lehet!\');
		document.getElementById(\'match_title_\' + rssID + \'_\' + agencyID).focus();	
		return false;
	}
	var matchLead = document.getElementById(\'match_lead_\' + rssID + \'_\' + agencyID).value;
	var period = document.getElementById(\'period_\' + rssID + \'_\' + agencyID).value;
		if(period==""){
		alert(\'Ures periodus!\');
		document.getElementById(\'period_\' + rssID + \'_\' + agencyID).focus();
		return false;
	}
	if(!IsNumeric(period)){
		alert(\'A periodus csak numerikus ertek lehet!\');
		document.getElementById(\'period_\' + rssID + \'_\' + agencyID).focus();
		return false;
	}

	var status = document.getElementById(\'rss_status_\' + rssID + \'_\' + agencyID).options[document.getElementById(\'rss_status_\' + rssID + \'_\' + agencyID).selectedIndex].value;		
	var news_order = document.getElementById(\'news_order_\' + rssID + \'_\' + agencyID).options[document.getElementById(\'news_order_\' + rssID + \'_\' + agencyID).selectedIndex].value;		
	
	var pars = "action=update_rss&rss_id=" + rssID + \'&agencies=\' + agency + \'&type=\' + rssType + \'&rss_name=\' + rssName + \'&rss_url=\' + rssURL + \'&description=\' + description + \'&pattern=\' + pattern + \'&aux_url=\' + auxURL + \'&match_link=\' + matchLink + \'&match_title=\' + matchTitle + \'&match_lead=\' + matchLead + \'&period=\' + period + \'&status=\' + status+ \'&news_order=\'+news_order;	
	var url = "ajax.php";	
	var data = Array();
	data[0] = rssID;
	data[1] = agencyID;
	data[2] = rssURL;
	data[3] = rssName;
	Ajax.send(url, "POST", pars, updateRSSProcess, data);
}

function delAgency(agencyID, agencyName){
	if(confirm(\'Biztos benne, hogy torli a(z) `\' + agencyName + \'` hirforrast?\')){
		var pars = "action=del_agency&agency_id=" + agencyID;	
		var url = "ajax.php";	
		Ajax.send(url, "POST", pars, null, null);
		var tr = document.getElementById(\'a_\' + agencyID);
		var table = tr.parentNode;
		table.removeChild(tr);
		if(document.getElementById("tr_" + agencyID)!=null){
			var trMod = document.getElementById("tr_" + agencyID);
			table.removeChild(trMod);
		}				
	}
}
function delRSS(rssID, agencyID, rssName){
	if(confirm(\'Biztos benne, hogy torli a(z) `\' + rssName + \'` hirfolyammot?\')){
		var pars = "action=del_rss&rss_id=" + rssID;	
		var url = "ajax.php";	
		Ajax.send(url, "POST", pars, null, null);
		
		var tr = document.getElementById(\'rss_\' + rssID  + \'_\' + agencyID);
		var table =tr.parentNode;
		table.removeChild(tr);
		if(document.getElementById("tr_" +  rssID  + \'_\' + agencyID)!=null){
			var trMod = document.getElementById("tr_" +  rssID  + \'_\' + agencyID);
			table.removeChild(trMod);
		}
		tr = document.getElementById(\'a_\' + agencyID);
		var tds = tr.getElementsByTagName(\'td\');
		tds[3].innerHTML = parseInt(tds[3].innerHTML) - 1;
	}
}
function addNewRssProcess(request, data){
	var td = data[0];
	var answer = request.responseText;
	td.innerHTML = answer;
}
function closeNewRss(agencyID, object){
	object.innerHTML = "&Uacute;j h&iacute;rfolyam";
	object.onclick = function(){
		addNewRss(agencyID, this);return false;
	}
	var tr = document.getElementById(\'related_rss_\' + agencyID);
	var table = tr.parentNode;	
	var td = document.getElementById("add_rss_" + agencyID);
	table.removeChild(td);
}
function addNewRss(agencyID, object){
	object.innerHTML = "Bez&aacute;r";
	object.onclick = function(){
		closeNewRss(agencyID, this);return false;
	}
	var tr = document.getElementById(\'related_rss_\' + agencyID)
	var table = tr.parentNode;
	var trRow = document.createElement(\'tr\');	
	
	if(tr.nextSibling)	table.insertBefore(trRow, tr.nextSibling);
		else table.appendChild(trRow);
	var td = document.createElement(\'td\');
	trRow.appendChild(td);
	td.colSpan = 4;
	trRow.id = "add_rss_" + agencyID;
	
	var pars = "action=add_rss&agency_id=" + agencyID;	
	var url = "ajax.php";	
	var data = Array();
	data[0] = td;
	Ajax.send(url, "POST", pars, addNewRssProcess, data);
}

function postRSSProcess(request, data){	
	var answer = request.responseText;
	if(answer=="-1") alert(\'Mar van ilyen hirfolyam\');
		else{
			var tr = document.getElementById(\'related_rss_\' + data[0]);
			var table = tr.parentNode;
			var td = document.getElementById("add_rss_" + data[0]);
			var a = table.getElementsByTagName(\'a\');
			a[0].innerHTML = "&Uacute;j h&iacute;rfolyam";
			a[0].onclick = function(){
				addNewRss(data[0], this);return false;
			}	
			table.removeChild(td);	
			if(document.all){		
				var myregexp = /<tr id="rss_(\\d+)_(\\d+)">/;
				var rssID = "";
				var agencyID = "";
				var match = myregexp.exec(answer);
				if (match != null) {
					rssID = match[1];
					agencyID = match[2];
				} else {
					result = "";
				}
				var rssURL = "";
				var rssName = "";
				myregexp = /<td><a href="(.*?)" target="_blank">(.*?)<\\/a>/;
				var match = myregexp.exec(answer);
				if (match != null) {
					rssURL = match[1];
					rssName = match[2];
				} else {
					result = "";
				}
				var tr = document.createElement(\'<tr id="rss_\' + rssID + \'_\' + agencyID + \'">\');
				table.appendChild(tr);
				var td = document.createElement(\'<td>\');
				td.innerHTML = rssID;
				tr.appendChild(td);
				td = document.createElement(\'<td>\');
				td.innerHTML = \'<a href="\' + rssURL + \'" target="_blank">\' + rssName + \'</a>\';
				tr.appendChild(td);
				td = document.createElement(\'<td>\');
				td.innerHTML = \'[ <a href="#" onclick="showRSSMod(\\\'\' + rssID + \'\\\', \\\'\' + agencyID + \'\\\', this);return false;">M&oacute;dos&iacute;t</a> ]\';
				tr.appendChild(td);
				td = document.createElement(\'<td>\');
				td.innerHTML = \'[ <a href="#" onclick="delRSS(\\\'\' + rssID + \'\\\', \\\'\' + agencyID + \'\\\', \\\'\' + rssName + \'\\\'); return false;">T&ouml;r&ouml;l</a> ]\';
				tr.appendChild(td);
			}else	table.innerHTML += answer;
			
			var tr = document.getElementById(\'a_\' + data[0]);
			var tds = tr.getElementsByTagName(\'td\');
			tds[3].innerHTML = parseInt(tds[3].innerHTML) + 1;
	}
}

function postRSS(agencyID){		
	var agency = document.getElementById(\'agencies__\' + agencyID).options[document.getElementById(\'agencies__\' + agencyID).selectedIndex].value;
	var rssType = document.getElementById(\'type__\' + agencyID).options[document.getElementById(\'type__\' + agencyID).selectedIndex].value;
	var rssName = document.getElementById(\'rss_name__\' + agencyID).value;
	if(rssName==""){
		alert(\'Ures hirfolyam nev!\');
		document.getElementById(\'rss_name__\' + agencyID).focus();
		return false;
	}
	var rssURL = encodeURIComponent(document.getElementById(\'rss_url__\' + agencyID).value);
	if(rssURL==""){
		alert(\'Ures hirfolyam URL!\');
		document.getElementById(\'rss_url__\' + agencyID).focus();
		return false;
	}
	var description = document.getElementById(\'description__\' + agencyID).value;	
	var pattern = encodeURIComponent(document.getElementById(\'pattern__\' + agencyID).value);
	if(rssType==2 && pattern==""){
		alert(\'Ures reguralis kifejezes!\');
		document.getElementById(\'pattern__\' + agencyID).focus();
		return false;
	}
	var auxURL = document.getElementById(\'aux_url__\' + agencyID).value;
	var matchLink = document.getElementById(\'match_link__\' + agencyID).value;
	if(rssType==2 && matchLink==""){
		alert(\'Ures link talalat!\');
		document.getElementById(\'match_link__\' + agencyID).focus();
		return false;
	}
	if(rssType==2 && !IsNumeric(matchLink)){
		alert(\'A link talalat csak numerikus ertek lehet!\');
		document.getElementById(\'match_link__\' + agencyID).focus();
		return false;
	}
	var matchTitle = document.getElementById(\'match_title__\' + agencyID).value;
	if(rssType==2 && matchTitle==""){
		alert(\'Ures cim talalat!\');
		document.getElementById(\'match_title__\' + agencyID).focus();
		return false;
	}
	if(rssType==2 && !IsNumeric(matchTitle)){
		alert(\'A cim talalat csak numerikus ertek lehet!\');
		document.getElementById(\'match_title__\' + agencyID).focus();	
		return false;
	}
	var matchLead = document.getElementById(\'match_lead__\' + agencyID).value;
	var period = document.getElementById(\'period__\' + agencyID).value;
	if(period==""){
		alert(\'Ures periodus!\');
		document.getElementById(\'period__\' + agencyID).focus();
		return false;
	}
	if(!IsNumeric(period)){
		alert(\'A periodus csak numerikus ertek lehet!\');
		document.getElementById(\'period__\' + agencyID).focus();
		return false;
	}
	var status = document.getElementById(\'rss_status__\' + agencyID).options[document.getElementById(\'rss_status__\' + agencyID).selectedIndex].value;
	
	var pars = \'action=post_rss&agency_id=\' + agencyID + \'&agencies=\' + agency + \'&type=\' + rssType + \'&rss_name=\' + rssName + \'&rss_url=\' + rssURL + \'&description=\' + description + \'&pattern=\' + pattern + \'&aux_url=\' + auxURL + \'&match_link=\' + matchLink + \'&match_title=\' + matchTitle + \'&match_lead=\' + matchLead + \'&period=\' + period + \'&status=\' + status;	
	var url = "ajax.php";	
	var data = Array();
	data[0] = agencyID;
	Ajax.send(url, "POST", pars, postRSSProcess, data);
}
</script>
'; ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="page_title">H&iacute;rforr&aacute;sok</td>
  </tr>
  <tr>
  	<td>
		<form name="searc_agencies" method="get" action="index.php">
		<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['id']; ?>
" />
		<input type="hidden" name="sub_id" value="<?php echo $this->_tpl_vars['sub_id']; ?>
" />		
		<?php ob_start(); ?>
			<table width="100%" border="0" cellspacing="0" cellpadding="5">
			  <tr>
				<td><b>N&eacute;v:&nbsp;</b><input type="text" name="agency_name" value="<?php echo $this->_tpl_vars['agency_name']; ?>
" /></td>
				<td><b>URL:&nbsp;</b><input type="text" name="agency_url" value="<?php echo $this->_tpl_vars['agency_url']; ?>
" /></td>
				<td>
					<b>Tipus:&nbsp;</b>
					<select name="agency_type" style="width:auto; ">						
						<?php if ($this->_tpl_vars['agency_type'] == ""): ?>
							<option value="">Mindegy</option>
							<option value="1">RSS</option>
							<option value="2">HTML</option>
						<?php elseif ($this->_tpl_vars['agency_type'] == '1'): ?>
							<option value="">Mindegy</option>
							<option value="1" selected>RSS</option>
							<option value="2">HTML</option>
						<?php elseif ($this->_tpl_vars['agency_type'] == '2'): ?>
							<option value="">Mindegy</option>
							<option value="1">RSS</option>
							<option value="2" selected>HTML</option>
						<?php endif; ?>
					</select>
				</td>
				<td><input type="submit" value="Keres" class="button" /></td>
			  </tr>
			</table>
		<?php $this->_smarty_vars['capture']['search_agencies'] = ob_get_contents(); ob_end_clean(); ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.html", 'smarty_include_vars' => array('title' => "H&iacute;rforr&aacute;sok keres&eacute;se",'content' => $this->_smarty_vars['capture']['search_agencies'],'extra' => "width=100%")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		</form>
	</td>
  </tr>
  <tr>
  	<td class="page_title"></td>
  </tr>
  <tr>
    <td>
		<table width="100%" border="0" cellspacing="0" cellpadding="4" style="border:1px solid #BDBEBD;">
		  <tr style="height:20px;background-image:url('../i/bh.gif');background-repeat:repeat-x;">
			<td align="center" style="border-bottom:1px solid #BDBEBD;"><b>ID</b></td>
			<td style="border-bottom:1px solid #BDBEBD;"><b>N&eacute;v</b></td>
			<td style="border-bottom:1px solid #BDBEBD;" align="center"><b>Tipus</b></td>
			<td style="border-bottom:1px solid #BDBEBD;" align="center"><b>H&iacute;rfolyamok sz&aacute;ma</b></td>
			<td align="center" style="border-bottom:1px solid #BDBEBD;"><b>M&#369;veletek</b></td>
		  </tr>
		  <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['agencies']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
		  <tr bgcolor="<?php echo smarty_function_cycle(array('values' => "#EAEAEA,#F4F4F4"), $this);?>
" id="a_<?php echo $this->_tpl_vars['agencies'][$this->_sections['i']['index']]['agency_id']; ?>
">
			<td align="center"><?php echo $this->_tpl_vars['agencies'][$this->_sections['i']['index']]['agency_id']; ?>
</td>
			<td><a href="<?php echo $this->_tpl_vars['agencies'][$this->_sections['i']['index']]['agency_url']; ?>
" target="_blank"><?php echo $this->_tpl_vars['agencies'][$this->_sections['i']['index']]['agency_name']; ?>
</a></td>			
			<td align="center">
				<?php if ($this->_tpl_vars['agencies'][$this->_sections['i']['index']]['agency_type'] == 1): ?>
					RSS
				<?php elseif ($this->_tpl_vars['agencies'][$this->_sections['i']['index']]['agency_type'] == 2): ?>
					HTML
				<?php endif; ?>
			</td>
			<td align="center"><?php echo $this->_tpl_vars['agencies'][$this->_sections['i']['index']]['news_flow_nr']; ?>
</td>
			<td align="center">[ <a href="#" onClick="showAgencyMod('<?php echo $this->_tpl_vars['agencies'][$this->_sections['i']['index']]['agency_id']; ?>
', this);return false;">M&oacute;dos&iacute;t</a> ] [ <a href="#" onClick="delAgency('<?php echo $this->_tpl_vars['agencies'][$this->_sections['i']['index']]['agency_id']; ?>
', '<?php echo $this->_tpl_vars['agencies'][$this->_sections['i']['index']]['agency_name']; ?>
');">T&ouml;r&ouml;l</a> ]</td>				
		  </tr>
		  <?php endfor; endif; ?>		  
		</table>
	</td>
  </tr>
</table>
<table border="0" cellspacing="4" cellpadding="2" align="center" class="paging">
  <tr>		
	<td><?php if ($this->_tpl_vars['__GT']['current'] != 1 && $this->_tpl_vars['__GT']['total_pages'] != 0): ?><a href="index.php?<?php echo $this->_tpl_vars['url']; ?>
&page=1&plimit=<?php echo $this->_tpl_vars['__GT']['limit']; ?>
" target="_self">Els&#337;</a><?php endif; ?></td>
	<td><?php if ($this->_tpl_vars['__GT']['current'] != 1 && $this->_tpl_vars['__GT']['total_pages'] != 0): ?><a href="index.php?<?php echo $this->_tpl_vars['url']; ?>
&page=<?php echo $this->_tpl_vars['__GT']['prev']['link']; ?>
&plimit=<?php echo $this->_tpl_vars['__GT']['limit']; ?>
" target="_self">El&#337;z&#337;</a><?php endif; ?></td>
	<td>
		<table border="0" cellspacing="3" cellpadding="0" align="center" class="paging">
			<tr>
				<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['__GT']['pages']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>	  		
					<td align="center">								
						<?php if ($this->_tpl_vars['__GT']['pages'][$this->_sections['i']['index']]['link'] == $this->_tpl_vars['__GT']['current']): ?>
						<b><?php echo $this->_tpl_vars['__GT']['pages'][$this->_sections['i']['index']]['link']; ?>
</b>
						<?php else: ?>
						<a href="index.php?<?php echo $this->_tpl_vars['url']; ?>
&page=<?php echo $this->_tpl_vars['__GT']['pages'][$this->_sections['i']['index']]['link']; ?>
&plimit=<?php echo $this->_tpl_vars['__GT']['limit']; ?>
" target="_self"><?php echo $this->_tpl_vars['__GT']['pages'][$this->_sections['i']['index']]['link']; ?>
</a>
						<?php endif; ?>						
					</td>			
				<?php endfor; endif; ?>			
			</tr>
		</table>
	</td>				
	<td><?php if ($this->_tpl_vars['__GT']['current'] != $this->_tpl_vars['__GT']['total_pages'] && $this->_tpl_vars['__GT']['total_pages'] != 0): ?><a href="index.php?<?php echo $this->_tpl_vars['url']; ?>
&page=<?php echo $this->_tpl_vars['__GT']['next']['link']; ?>
&plimit=<?php echo $this->_tpl_vars['__GT']['limit']; ?>
" target="_self">K&ouml;vetkez&#337;</a><?php endif; ?></td>
	<td><?php if ($this->_tpl_vars['__GT']['current'] != $this->_tpl_vars['__GT']['total_pages'] && $this->_tpl_vars['__GT']['total_pages'] != 0): ?><a href="index.php?<?php echo $this->_tpl_vars['url']; ?>
&page=<?php echo $this->_tpl_vars['__GT']['total_pages']; ?>
&plimit=<?php echo $this->_tpl_vars['__GT']['limit']; ?>
" target="_self">Utols&oacute;</a><?php endif; ?></td>
  </tr>  
</table>