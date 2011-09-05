<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
<TR ALIGN="left" VALIGN="middle"> 
<TD HEIGHT="37"><span class='szovegvastag'>&nbsp;</span><br>
</TD>
</TR>
<TR ALIGN="left" VALIGN="middle"> 
<TD HEIGHT="37"><span class='szovegvastag'>&nbsp;{ct_dist}:</span><br>
</TD>
</TR>
</TABLE>
{DATEGEN}
<!-- BEGIN DYNAMIC BLOCK: is_ct -->			
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td class="bggray" align="center" valign="center" width=100%>
<img src="images/spacer.gif" width="4" height="3">
          </td>
        </tr>
        <tr>
          <td class="bggray" align="center" valign="center" width=100%>
<!--<img src="grafnew/bars.php?id={ID}&cache_id={CACHE_ID}&end_daynum={END_DAYNUM}&start_daynum={START_DAYNUM}&desc=11&language={LANGUAGE}" align="center" valign="center">-->
                        <script type="text/javascript" src="{BASEURL}/amcharts/amcolumn/swfobject.js"></script>
                        <div id="flashcontentfull1" align="center">
            		        <strong>You need to upgrade your Flash Player</strong>
                	    </div>
                        <script type="text/javascript">
            		    // <![CDATA[		
            		        var sofull = new SWFObject("{BASEURL}/amcharts/amstock/amstock.swf", "amstock", "920", "400", "8", "#FFFFFF");
                            sofull.addVariable("path", "");
                            sofull.addVariable("settings_file",
                            encodeURIComponent("{BASEURL}/amcharts/maxima/timeline.php?id={ID}&cache_id={CACHE_ID}&end_daynum={END_DAYNUM}&start_daynum={START_DAYNUM}&desc={DESC}&language=<?=$language;?>&type=click"));
                            sofull.write("flashcontentfull1");
            		    // ]]>
                        </script>
                        <script type="text/javascript" src="{BASEURL}/amcharts/amcolumn/swfobject.js"></script>
                        <div id="flashcontentfull2" align="center">
            		        <strong>You need to upgrade your Flash Player</strong>
                	    </div>
                	    <script type="text/javascript">
                            // <![CDATA[		
                            var sofull2 = new SWFObject("{BASEURL}/amcharts/amcolumn/amcolumn.swf", "amcolumn", "920", "400", "8", "#FFFFFF");
                            sofull2.addVariable("path", "{BASEURL}/amcharts/amcolumn/");
                            sofull2.addVariable("settings_file",
                            encodeURIComponent("{BASEURL}/amcharts/maxima/settings.php?id={ID}&cache_id={CACHE_ID}&end_daynum={END_DAYNUM}&start_daynum={START_DAYNUM}&desc={DESC}&language=<?=$language;?>&type=click"));
                            sofull2.addVariable("data_file",
                            encodeURIComponent("{BASEURL}/amcharts/maxima/xml.php?id={ID}&cache_id={CACHE_ID}&end_daynum={END_DAYNUM}&start_daynum={START_DAYNUM}&desc={DESC}&language=<?=$language;?>&type=click"));
                            //so.addVariable("chart_data", encodeURIComponent('{CHARTDATAXML}'));
                            sofull2.write("flashcontentfull2");
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
<!-- END DYNAMIC BLOCK: is_ct -->	
<TABLE WIDTH="100%" BORDER="0" BGCOLOR="#CCCCCC" CELLPADDING="1" CELLSPACING="0">     
<TR>
   <TD>      
      <TABLE WIDTH="100%" BORDER="0" BGCOLOR="#FFFFFF" CELLSPACING="0" cellpadding='0'>			
        <tr> 
          <td class="formmezo" colspan="4" align="center">{t_date}</td>
          <td class="formmezo" width="145" align="center">{t_dclick}</td>
          <td class="formmezo" width="145" align="center">{t_click}</td>
          <td class="formmezo" width="145" align="center">{t_ratio}</td>          
        </tr>
        <tr> 
          <td colspan="8"><img src="images/spacer.gif" width="50" height="1"></td>
        </tr>
<!-- BEGIN DYNAMIC BLOCK: list_row -->			
        {YEARSEP}{MONTHSEP}{WEEKSEP}{DAYSEP}
        <tr> 
          <td class="{YEARSTYLE}" width="32" align="center"><span class="font1">
          <a href=clickthrough.php?group_id={GROUP_ID}&message_id={MESSAGE_ID}&lyear={YEAR}>{YEARNAME}</a>&nbsp;</td>
          <td class="{MONTHSTYLE}" width="90" align="center"><span class="font1">
          <a href=clickthrough.php?group_id={GROUP_ID}&message_id={MESSAGE_ID}&lyear={YEAR}&lmonth={MONTH}>{MONTHNAME}</a>&nbsp;</td> 
          <td class="{DAYSTYLE}" width="105" align="left"><span class="font1">
          <a href=clickthrough.php?group_id={GROUP_ID}&message_id={MESSAGE_ID}&lyear={YEAR}&lmonth={MONTH}&lday={DAY}>{DAYNAME}</a>
          </span><span class="{HETVEGE}">&nbsp;{SUBPERIOD_B}&nbsp;</span></td>
          <td class="{TD_STYLE}" width="40" align="left"><span class="font1">
          <span class=font1>&nbsp;{HOURS}</span></td>
          <td class="{TD_STYLE}" width="145" align="right"><span class="font1">{DCLICKNUM}&nbsp;</span></td>
          <td class="{TD_STYLE}" width="145" align="right"><span class="font1">{CLICKNUM}&nbsp;</span></td>
          <td class="{TD_STYLE}" width="145" align="right"><span class="font1">{CLICKNUMFB}&nbsp;</span></td>          
        </tr>
<!-- END DYNAMIC BLOCK: list_row -->	
        <tr> 
          <td colspan="8"><img src="images/spacer.gif" width="50" height="1"></td>
        </tr>
        <tr> 
          <td class="bggray" colspan="4" align="center"><span class="font1">{total}</span></td>
          <td class="bggray" width="145" align="right"><span class="font1">{DCLICKNUM_SUM}&nbsp;</span></td>
          <td class="bggray" width="145" align="right"><span class="font1">{CLICKNUM_SUM}&nbsp;</span></td>
          <td class="bggray" width="145" align="right"><span class="font1">{CLICKNUMFB_SUM}&nbsp;</span></td>          
        </tr>
      </table>
    </td>
  </tr>
</table>

