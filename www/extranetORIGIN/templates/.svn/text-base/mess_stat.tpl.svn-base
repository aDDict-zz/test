<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bgcolor">
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr class="tal activetitle" valign="middle"> 
                    <td>
                        <span class='szovegvastag'>&nbsp;{ACTIVETITLE}</span>
                    </td>
                </tr>                
                <tr class="tal" valign="middle" style="background-color:#FFFFFF;"> 
                    <td height="22">
                        <span class='szovegvastag'>&nbsp;{mess_sent}</span><br>
                    </td>
                    <td class="tar">
                        <a href='javascript:void(0);' id='print_page'>{html_report}</a>
                    </td>
                </tr>
            </table>
            <table class="noprint" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        {DATEGEN}
                    </td>
                </tr>
            </table>
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
                  <td class="bggray" align="center" valign="center" width=100%>

       <!--<img src="grafnew/bars.php?id={ID}&cache_id={CACHE_ID}&end_daynum={END_DAYNUM}&start_daynum={START_DAYNUM}&desc=2&language={language}" align="center" valign="center"
       />-->
                  
                        <script type="text/javascript" src="{BASEURL}/amcharts/amcolumn/swfobject.js"></script>
                        <div id="flashcontent2" align="center">
            		        <strong>You need to upgrade your Flash Player</strong>
                	    </div>
                	    <script type="text/javascript">
                            // <![CDATA[		
                            var so2 = new SWFObject("{BASEURL}/amcharts/amcolumn/amcolumn.swf", "amcolumn", "920", "400", "8", "#FFFFFF");
                            so2.addVariable("path", "{BASEURL}/amcharts/amcolumn/");
                            so2.addVariable("settings_file",
                            encodeURIComponent("{BASEURL}/amcharts/maxima/settings.php?id={ID}&cache_id={CACHE_ID}&end_daynum={END_DAYNUM}&start_daynum={START_DAYNUM}&desc={DESC}&language={language}&type=mess"));
                            so2.addVariable("data_file", encodeURIComponent("{BASEURL}/amcharts/maxima/xml.php?id={ID}&cache_id={CACHE_ID}&end_daynum={END_DAYNUM}&start_daynum={START_DAYNUM}&desc={DESC}&language={language}&type=mess"));
                            //so.addVariable("chart_data", encodeURIComponent('{CHARTDATAXML}'));
                            so2.write("flashcontent2");
                            // ]]>
                	    </script>

                  </td>
                </tr>
                <tr> 
                  <td class="bggray" align="center" valign="center" width=100%>
       <img src="images/spacer.gif" width="4" height="3">
                  </td>
                </tr>
              </table>

      
      <table width="100%" border="0" bgcolor="#FFFFFF" cellspacing="0" cellpadding='0'>			
                <tr> 
                  <td class="formmezo" colspan="4" align="center"><span class="alcim">{t_date}</span></td>
                  <td class="formmezo" width="247" align="center"><span class="alcim">{t_messages}</span></td> 
                  <td class="formmezo" width="247" align="center"><span class="alcim">{t_copies}</span></td> 
                </tr>
                <tr> 
                  <td colspan="6"><img src="images/spacer.gif" width="50" height="1"></td>
                </tr>
<!-- BEGIN DYNAMIC BLOCK: list_row -->			
                {YEARSEP}{MONTHSEP}{WEEKSEP}{DAYSEP}
                <tr> 
                  <td class="{YEARSTYLE}" width="40" align="center"><span class="font1">
				  <a href="mess_stat.php?group_id={GROUP_ID}&lyear={YEAR}&dostat=1">{YEARNAME}</a>&nbsp;</td>
                  <td class="{MONTHSTYLE}" width="87" align="center"><span class="font1">
				  <a href="mess_stat.php?group_id={GROUP_ID}&lyear={YEAR}&lmonth={MONTH}&dostat=1">{MONTHNAME}</a>&nbsp;</td> 
                  <td class="{DAYSTYLE}" width="105" align="left"><span class="font1">
				  <a href="mess_stat.php?group_id={GROUP_ID}&lyear={YEAR}&lmonth={MONTH}&lday={DAY}&dostat=1">{DAYNAME}</a>
				  </span><span class="{HETVEGE}">&nbsp;{SUBPERIOD_B}&nbsp;</span></td>
                  <td class="{TD_STYLE}" width="44" align="left"><span class="font1">
				  <span class=font1>&nbsp;{HOURS}</span></td>
                 <td class="{TD_STYLE}" width="247" align="right"><span class="font1">{SUBSNUM}&nbsp;</span></td>
                 <td class="{TD_STYLE}" width="247" align="right"><span class="font1">{ALLNUM}&nbsp;</span></td>
                </tr>
<!-- END DYNAMIC BLOCK: list_row -->	
                <tr> 
                  <td colspan="6"><img src="images/spacer.gif" width="50" height="1"></td>
                </tr>
                <tr> 
                  <td class="bggray" colspan="4" align="center"><span class="font1">{total}</span></td>
                  <td class="bggray" width="247" align="right"><span class="font1">{SUBSNUM_SUM}&nbsp;</span></td>
                  <td class="bggray" width="247" align="right"><span class="font1">{ALLNUM_SUM}&nbsp;</span></td>
                </tr>
              </table>
    </td>
  </tr>
</table>
    </td>
  </tr>
</table>
<!-- END DYNAMIC BLOCK: statmain -->			
