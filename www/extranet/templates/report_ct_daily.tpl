<TABLE WIDTH="550" BORDER="0" CELLSPACING="0" CELLPADDING="0">
<TR ALIGN="left" VALIGN="middle"> 
<TD HEIGHT="30"><span class='szovegvastag'>&nbsp;</span><br>
</TD>
</TR>
<tr>
<td width='100%'>
<table width=100% border=0 cellspacing=0 cellpadding=0>
<tr>
<td width='100%'><span class='szovegvastag'>{TPART1}<br>{TPART2}<br>{TPART3}</span></td>
</tr>
</table>
</td>
</tr>
<!-- BEGIN DYNAMIC BLOCK: is_ct -->			
  <TR>
<TD class=MENUBORDER width='100%'>
<TABLE cellSpacing=1 cellPadding=1 width='100%' border=0>
<tr>
<td class=bgkiemelt2 align='center'><span class=szovegvastag>{ct_url}</span></td>
<td class=bgkiemelt2 align='center'><span class=szovegvastag>{ct_mailnum}</span></td>
<td class=bgkiemelt2 align='center'><span class=szovegvastag>{ct_clicknum}</span></td>
<td class=bgkiemelt2 align='center'><span class=szovegvastag>{ct_clicknum_dist}</span></td>
<td class=bgkiemelt2 align='center'><span class=szovegvastag>{ct_click_first}</span></td>
<td class=bgkiemelt2 align='center'><span class=szovegvastag>{ct_click_last}</span></td>
</tr>
<!-- BEGIN DYNAMIC BLOCK: url_list -->			
<tr>
<td valign='top' align='left' class={RSTYLE}>
<span class='szoveg'>{URL}</span></td>
<td valign='top' align='left' class={RSTYLE}>
<span class='szoveg'>{MAILS}</span></td>
<td valign='top' align='left' class={RSTYLE}>
<span class='szoveg'>{CLICKNUM}</span></td>
<td valign='top' align='left' class={RSTYLE}>
<span class='szoveg'>{CLICKNUM_DIST}</span></td>
<td valign='top' align='left' class={RSTYLE}>
<span class='szoveg'>{CLICK_FIRST}</span></td>
<td valign='top' align='left' class={RSTYLE}>
<span class='szoveg'>{CLICK_LAST}</span></td>
</tr>
<!-- END DYNAMIC BLOCK: url_list -->			
<tr>
<td valign='top' align='left' class=bggray>
<span class='szovegvastag'>{SURL}</span></td>
<td valign='top' align='left' class=bggray>
<span class='szovegvastag'>{SMAILS}</span></td>
<td valign='top' align='left' class=bggray>
<span class='szovegvastag'>{SCLICKNUM}</span></td>
<td valign='top' align='left' class=bggray>
<span class='szovegvastag'>{SCLICKNUM_DIST}</span></td>
<td valign='top' align='left' class=bggray>
<span class='szovegvastag'>{SCLICK_FIRST}</span></td>
<td valign='top' align='left' class=bggray>
<span class='szovegvastag'>{SCLICK_LAST}</span></td>
</tr>
</table>
</td>
</tr>
<TR ALIGN="left" VALIGN="middle"> 
<TD HEIGHT="30"><span class='szovegvastag'>&nbsp;</span><br>
</TD>
</TR>
<!-- END DYNAMIC BLOCK: is_ct -->			
<!-- BEGIN DYNAMIC BLOCK: is_1x1 -->			
<tr>
<TD class='MENUBORDER' width='100%'>
<TABLE cellSpacing=1 cellPadding=1 width='100%' border=0>
<tr>
<td class=bgkiemelt2 align='left' width='50%'><span class=szovegvastag>{all1x1}: {T1X1SUM}&nbsp;</span></td>
<td class=bgkiemelt2 align='left' width='50%'><span class=szovegvastag>{all1x1dist}: {DT1X1SUM}</span></td>
</tr>
</table>
</td>
</tr>
<TR ALIGN="left" VALIGN="middle"> 
<TD HEIGHT="30"><span class='szovegvastag'>&nbsp;</span><br>
</TD>
</TR>
<!-- END DYNAMIC BLOCK: is_1x1 -->			
<TR ALIGN="left" VALIGN="middle"> 
<TD HEIGHT="37"><span class='szovegvastag'>&nbsp;{ct_dist}:</span><br>
</TD>
</TR>
</TABLE>
<TABLE WIDTH="550" BORDER="0" BGCOLOR="#CCCCCC" CELLPADDING="1" CELLSPACING="0">     
<TR>
   <TD>      
      <TABLE WIDTH="550" BORDER="0" BGCOLOR="#FFFFFF" CELLSPACING="0" cellpadding='0'>			
                <tr> 
                  <td class="formmezo" colspan="4" align="center">{t_date}</td>
                  <td class="formmezo" width="87" align="center">{t_d1x1}</td>
                  <td class="formmezo" width="87" align="center">{t_1x1}</td>
                  <td class="formmezo" width="87" align="center">{t_dclick}</td>
                  <td class="formmezo" width="87" align="center">{t_click}</td>
                </tr>
                <tr> 
                  <td colspan="8"><img src="images/spacer.gif" width="50" height="1"></td>
                </tr>
<!-- BEGIN DYNAMIC BLOCK: list_row -->			
                {YEARSEP}{MONTHSEP}{WEEKSEP}{DAYSEP}
                <tr> 
                  <td class="{YEARSTYLE}" width="32" align="center"><span class="font1">
				  {YEARNAME}&nbsp;</td>
                  <td class="{MONTHSTYLE}" width="90" align="center"><span class="font1">
				  {MONTHNAME}&nbsp;</td> 
                  <td class="{DAYSTYLE}" width="105" align="left"><span class="font1">
				  {DAYNAME}
				  </span><span class="{HETVEGE}">&nbsp;{SUBPERIOD_B}&nbsp;</span></td>
                  <td class="{TD_STYLE}" width="40" align="left"><span class="font1">
				  <span class=font1>&nbsp;{HOURS}</span></td>
                  <td class="{TD_STYLE}" width="87" align="right"><span class="font1">{DT1X1}&nbsp;</span></td>
                  <td class="{TD_STYLE}" width="87" align="right"><span class="font1">{T1X1}&nbsp;</span></td>
                  <td class="{TD_STYLE}" width="87" align="right"><span class="font1">{DCLICKNUM}&nbsp;</span></td>
                  <td class="{TD_STYLE}" width="87" align="right"><span class="font1">{CLICKNUM}&nbsp;</span></td>
                </tr>
<!-- END DYNAMIC BLOCK: list_row -->	
                <tr> 
                  <td colspan="8"><img src="images/spacer.gif" width="50" height="1"></td>
                </tr>
                <tr> 
                  <td class="bggray" colspan="4" align="center"><span class="font1">{total}</span></td>
                  <td class="bggray" width="87" align="right"><span class="font1">{DT1X1SUM}&nbsp;</span></td>
                  <td class="bggray" width="87" align="right"><span class="font1">{T1X1SUM}&nbsp;</span></td>
                  <td class="bggray" width="87" align="right"><span class="font1">{DCLICKNUM_SUM}&nbsp;</span></td>
                  <td class="bggray" width="87" align="right"><span class="font1">{CLICKNUM_SUM}&nbsp;</span></td>
                </tr>
              </table>
<!-- BEGIN DYNAMIC BLOCK: is_ct -->			
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td class="bggray" align="center" valign="center" width=100%>
       <img src="images/spacer.gif" width="4" height="3">
                  </td>
                </tr>
                <tr> 
                  <td class="bggray" align="center" valign="center" width=100%>
       <img src="grafnew/bars.php?id={ID}&cache_id={CACHE_ID}&end_daynum={END_DAYNUM}&start_daynum={START_DAYNUM}&desc=11&shrink=50&language={LANGUAGE}" align="center" valign="center">
                  </td>
                </tr>
                <tr> 
                  <td class="bggray" align="center" valign="center" width=100%>
       <img src="images/spacer.gif" width="4" height="3">
                  </td>
                </tr>
              </table>
<!-- END DYNAMIC BLOCK: is_ct -->			
<!-- BEGIN DYNAMIC BLOCK: is_1x1 -->			
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td class="bggray" align="center" valign="center" width=100%>
       <img src="images/spacer.gif" width="4" height="3">
                  </td>
                </tr>
                <tr> 
                  <td class="bggray" align="center" valign="center" width=100%>
       <img src="grafnew/bars.php?id={ID}&cache_id={CACHE_ID2}&end_daynum={END_DAYNUM}&start_daynum={START_DAYNUM}&desc=12&shrink=50&language={LANGUAGE}" align="center" valign="center">
                  </td>
                </tr>
                <tr> 
                  <td class="bggray" align="center" valign="center" width=100%>
       <img src="images/spacer.gif" width="4" height="3">
                  </td>
                </tr>
              </table>
<!-- END DYNAMIC BLOCK: is_1x1 -->			
    </td>
  </tr>
</table>

