<table class="print_title" width="100%" border="0" cellspacing="0" cellpadding="0" class="">
    <tr class="tal activetitle" valign="middle"> 
        <td>
            <span class='szovegvastag'>&nbsp;{ACTIVETITLE}</span>
        </td>
    </tr>
    <tr class="tal" valign="middle"> 
        <td height="22">
            <span class='szovegvastag'>&nbsp;{DOCTITLE}</span>
        </td>
        <td class="tar">
            {CSVLINK}<a href='javascript:void(0);' id='print_page'>{html_report}</a>
        </td>
    </tr>
</table>
<table class="noprint bgcolor" width="100%" border="0" cellspacing="0" cellpadding="0" class="bgcolor">
    <tr class="tal" valign="middle"> 
        <td>
            {DATEGEN}
        </td>
    </tr>
</table>

<!-- BEGIN DYNAMIC BLOCK: affs -->
<table width="100%" border="0" class="bgcolor noprint" cellpadding="1" cellspacing="0">     
    <tr>
        <td>      
            <table width="100%" border="0" cellspacing="0">     
                <tr align="left" valign="bottom">      
	                <form action="{FORM_ACTION}">
                        {AFF_HIDDEN_PARTS}
                        <td align="left" width="613" class="formmezo">
                            {aff_filt}&nbsp;
                            <select name="aff" onchange="this.form.submit();">
    <!-- BEGIN DYNAMIC BLOCK: allaffs -->
                                <option value='0'>{aff_all}</option>  
    <!-- END DYNAMIC BLOCK: allaffs -->
                                {AFFLIST}
                            </select>
                            
                        </td>
                    </form>
                </tr>    
            </table>     
        </td>     
    </tr>     
</table>     
<!-- END DYNAMIC BLOCK: affs -->

<!-- BEGIN DYNAMIC BLOCK: statmain -->			
<table width="100%" border="0" cellpadding="1" cellspacing="0">     
    <tr>
        <td>      
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                    <td class="bggray" align="center" valign="center" width=100%>
                        <img src="images/spacer.gif" width="4" height="3">
                    </td>
                </tr>
                <tr> 
                    <td class="bggray tac" style="width:100%">
                      <div style="clear:both;width:100%;" class="tac">
                  <!--<img src="grafnew/bars.php?id={ID}&cache_id={CACHE_ID}&end_daynum={END_DAYNUM}&start_daynum={START_DAYNUM}&desc={DESC}&language={language}" align="center" valign="center">-->             
                        <script type="text/javascript" src="{BASEURL}/amcharts/amcolumn/swfobject.js"></script>
                        <div id="flashcontent" class="tac flash" style="clear:both;width:100%;">
            		        <strong>You need to upgrade your Flash Player</strong>
                	    </div>
                        <script type="text/javascript">
            		    // <![CDATA[		
            		        var so = new SWFObject("{BASEURL}/amcharts/amstock/amstock.swf", "amstock", "920", "400", "8", "#FFFFFF");
                            so.addVariable("path", "");
                            so.addVariable("settings_file", encodeURIComponent("{BASEURL}/amcharts/maxima/timeline.php?id={ID}&cache_id={CACHE_ID}&end_daynum={END_DAYNUM}&start_daynum={START_DAYNUM}&desc={DESC}&language={language}&type={TYPE}"));
                            so.write("flashcontent");
            		    // ]]>
                        </script>
                      </div>
                    </td>
                </tr>
                <tr>
                    <td class="bggray tac" style="width:100%">
                      <div style="clear:both;width:100%;" class="tac">
                        <!--<script type="text/javascript" src="{BASEURL}/amcharts/amcolumn/swfobject.js"></script>-->
                        <div id="flashcontent2" class="tac flash" style="clear:both;width:100%;">
            		        <strong>You need to upgrade your Flash Player</strong>
                	    </div>
                	    <script type="text/javascript">
                            // <![CDATA[		
                            var so2 = new SWFObject("{BASEURL}/amcharts/amcolumn/amcolumn.swf", "amcolumn", "920", "400", "8", "#FFFFFF");
                            so2.addVariable("path", "{BASEURL}/amcharts/amcolumn/");
                            so2.addVariable("settings_file",
                            encodeURIComponent("{BASEURL}/amcharts/maxima/settings.php?id={ID}&cache_id={CACHE_ID}&end_daynum={END_DAYNUM}&start_daynum={START_DAYNUM}&desc={DESC}&language={language}&type={TYPE}"));
                            so2.addVariable("data_file", encodeURIComponent("{BASEURL}/amcharts/maxima/xml.php?id={ID}&cache_id={CACHE_ID}&end_daynum={END_DAYNUM}&start_daynum={START_DAYNUM}&desc={DESC}&language={language}&type={TYPE}"));
                            //so.addVariable("chart_data", encodeURIComponent('{CHARTDATAXML}'));
                            so2.write("flashcontent2");
                            // ]]>
                	    </script>
                      </div>
                    </td>
                </tr>
                <tr> 
                    <td class="bggray" align="center" valign="center" width=100%>
                        <img src="images/spacer.gif" width="4" height="3">
                    </td>
                </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">			
                <tr> 
                    <td class="formmezo tac" colspan="4"><span class="alcim">{t_date}</span></td>
                    <td class="formmezo tal" width="494" style="padding-right:10px;"><span class="alcim">{t_members}</span></td> 
                </tr>
                <tr> 
                    <td colspan="5"><img src="images/spacer.gif" width="50" height="1"></td>
                </tr>
<!-- BEGIN DYNAMIC BLOCK: list_row -->			
                {YEARSEP}{MONTHSEP}{WEEKSEP}{DAYSEP}
                <tr> 
                    <td class="{YEARSTYLE} tal" width="40">
                        <span class="font1">
				            <a href="subs_stat.php?group_id={GROUP_ID}&multiid={MULTIID}&aff={AFF}&type={TYPE}&lyear={YEAR}&dostat=1">{YEARNAME}</a>&nbsp;
                        </span>
                    </td>
                    <td class="{MONTHSTYLE} tal" width="87">
                        <span class="font1">
				            <a href="subs_stat.php?group_id={GROUP_ID}&multiid={MULTIID}&aff={AFF}&type={TYPE}&lyear={YEAR}&lmonth={MONTH}&dostat=1">{MONTHNAME}</a>&nbsp;
                        </span>
                    </td> 
                    <td class="{DAYSTYLE} tal" width="105">
                        <span class="font1">
				            <a href="subs_stat.php?group_id={GROUP_ID}&multiid={MULTIID}&aff={AFF}&type={TYPE}&lyear={YEAR}&lmonth={MONTH}&lday={DAY}&dostat=1">{DAYNAME}</a>
				        </span>
                        <span class="{HETVEGE}">&nbsp;{SUBPERIOD_B}&nbsp;</span>
                    </td>
                    <td class="{TD_STYLE} tal" width="44">
				        <span class=font1>&nbsp;{HOURS}</span>
                    </td>
                    <td class="{TD_STYLE} tal" width="494" style="padding-right:10px;">
                        <span class="font1">{SUBSNUM}</span>
                    </td>
                </tr>
<!-- END DYNAMIC BLOCK: list_row -->	
                <tr> 
                    <td colspan="5">
                        <img src="images/spacer.gif" width="50" height="1">
                    </td>
                </tr>
                <tr> 
                    <td class="bggray tac" colspan="4">
                        <span class="font1">{SUMTEXT}</span>
                    </td>
                    <td class="bggray tar" width="494">
                        <span class="font1">{SUBSNUM_SUM}&nbsp;</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!-- END DYNAMIC BLOCK: statmain -->			
