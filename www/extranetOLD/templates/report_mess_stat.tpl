<TABLE WIDTH="550" BORDER="0" CELLSPACING="0" CELLPADDING="0">
<TR ALIGN="left" VALIGN="middle"> 
<TD HEIGHT="37"><span class='szovegvastag'>&nbsp;</span><br>
</TD>
</TR>
<TR ALIGN="left" VALIGN="middle"> 
<TD HEIGHT="37"><span class='szovegvastag'>&nbsp;{mess_sent}<br>{GTITLE} {group_lower}<br></span>
</TD>
</TR>
</TABLE>
<TABLE WIDTH="550" BORDER="0" BGCOLOR="#CCCCCC" CELLPADDING="1" CELLSPACING="0">     
<TR>
   <TD>      
      <TABLE WIDTH="100%" BORDER="0" BGCOLOR="#FFFFFF" CELLSPACING="0" cellpadding='0'>			
                <tr> 
                  <td class="formmezo" colspan="4" align="center"><span class="alcim">{t_date}</span></td>
                  <td class="formmezo" width="137" align="center"><span class="alcim">{t_messages}</span></td> 
                  <td class="formmezo" width="137" align="center"><span class="alcim">{t_copies}</span></td> 
                </tr>
                <tr> 
                  <td colspan="6"><img src="images/spacer.gif" width="50" height="1"></td>
                </tr>
<!-- BEGIN DYNAMIC BLOCK: list_row -->			
                {YEARSEP}{MONTHSEP}{WEEKSEP}{DAYSEP}
                <tr> 
                  <td class="{YEARSTYLE}" width="40" align="center"><span class="font1">
				  {YEARNAME}&nbsp;</td>
                  <td class="{MONTHSTYLE}" width="87" align="center"><span class="font1">
				  {MONTHNAME}&nbsp;</td> 
                  <td class="{DAYSTYLE}" width="105" align="left"><span class="font1">
				  {DAYNAME}
				  </span><span class="{HETVEGE}">&nbsp;{SUBPERIOD_B}&nbsp;</span></td>
                  <td class="{TD_STYLE}" width="44" align="left"><span class="font1">
				  <span class=font1>&nbsp;{HOURS}</span></td>
                 <td class="{TD_STYLE}" width="137" align="right"><span class="font1">{SUBSNUM}&nbsp;</span></td>
                 <td class="{TD_STYLE}" width="137" align="right"><span class="font1">{ALLNUM}&nbsp;</span></td>
                </tr>
<!-- END DYNAMIC BLOCK: list_row -->	
                <tr> 
                  <td colspan="6"><img src="images/spacer.gif" width="50" height="1"></td>
                </tr>
                <tr> 
                  <td class="bggray" colspan="4" align="center"><span class="font1">{total}</span></td>
                  <td class="bggray" width="137" align="right"><span class="font1">{SUBSNUM_SUM}&nbsp;</span></td>
                  <td class="bggray" width="137" align="right"><span class="font1">{ALLNUM_SUM}&nbsp;</span></td>
                </tr>
              </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td class="bggray" align="center" valign="center" width=100%>
       <img src="images/spacer.gif" width="4" height="3">
                  </td>
                </tr>
                <tr> 
                  <td class="bggray" align="center" valign="center" width=100%>
       <img src="grafnew/bars.php?id={ID}&cache_id={CACHE_ID}&end_daynum={END_DAYNUM}&start_daynum={START_DAYNUM}&desc=2&shrink=50&language={language}" align="center" valign="center">
                  </td>
                </tr>
                <tr> 
                  <td class="bggray" align="center" valign="center" width=100%>
       <img src="images/spacer.gif" width="4" height="3">
                  </td>
                </tr>
              </table>
    </td>
  </tr>
</table>
