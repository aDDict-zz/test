<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
<TR ALIGN="left" VALIGN="middle"> 
<TD HEIGHT="37"><span class='szovegvastag'>&nbsp;{DOCTITLE}</span><br>
</TD>
<!--<td align='right'>&nbsp;<a class='felso' href='subs_stat.php?multiid={MULTIID}&aff={AFF}&type={TYPE}&report=1' target='_blank'>{html_report}</a></td>-->
</TR>
</TABLE>

{DATEGEN}

<!-- BEGIN DYNAMIC BLOCK: affs -->
<TABLE WIDTH="100%" BORDER="0" class="bordertable" CELLPADDING="1" CELLSPACING="0">     
<TR>
   <TD>      
      <TABLE WIDTH="100%" BORDER="0" BGCOLOR="#FFFFFF" CELLSPACING="0">     
        <TR BGCOLOR="#FFFFFF" ALIGN="left" VALIGN="bottom">      
	              <form action="{FORM_ACTION}">
                  {AFF_HIDDEN_PARTS}
                  <td align="left" width="613">
                  <span class="szoveg">{aff_filt}&nbsp;
                  <select name="aff" onchange="this.form.submit();">
				     <option value='0'>{aff_all}</option>  
                     {AFFLIST}
                  </select>
                  </span>
                  </td>
		          </form>
        </tr>    
      </table>     
    </td>     
  </tr>     
</table>     
<!-- END DYNAMIC BLOCK: affs -->

<TABLE WIDTH="100%" BORDER="0" BGCOLOR="#CCCCCC" CELLPADDING="1" CELLSPACING="0">     
<TR>
   <TD>      
      <TABLE WIDTH="100%" BORDER="0" BGCOLOR="#FFFFFF" CELLSPACING="0" cellpadding='0'>			
                <tr> 
                  <td class="formmezo" colspan="4" align="center"><span class="alcim">{t_date}</span></td>
                  <td class="formmezo" width="110" align="center"><span class="alcim">{sgs_all}</span></td> 
                  <td class="formmezo" width="110" align="center"><span class="alcim">{sgs_no}</span></td> 
                  <td class="formmezo" width="110" align="center"><span class="alcim">{sgs_yes}</span></td> 
                  <td class="formmezo" width="164" align="center"><span class="alcim">{sgs_avg}</span></td> 
                </tr>
                <tr> 
                  <td colspan="8"><img src="images/spacer.gif" width="50" height="1"></td>
                </tr>
<!-- BEGIN DYNAMIC BLOCK: list_row -->			
                {YEARSEP}{MONTHSEP}{WEEKSEP}{DAYSEP}
                <tr> 
                  <td class="{YEARSTYLE}" width="40" align="center"><span class="font1">
				  <a href=superstat.php?multiid={MULTIID}&aff={AFF}&type={TYPE}&lyear={YEAR}>{YEARNAME}</a>&nbsp;</td>
                  <td class="{MONTHSTYLE}" width="87" align="center"><span class="font1">
				  <a href=superstat.php?multiid={MULTIID}&aff={AFF}&type={TYPE}&lyear={YEAR}&lmonth={MONTH}>{MONTHNAME}</a>&nbsp;</td> 
                  <td class="{DAYSTYLE}" width="105" align="left"><span class="font1">
				  <a href=superstat.php?multiid={MULTIID}&aff={AFF}&type={TYPE}&lyear={YEAR}&lmonth={MONTH}&lday={DAY}>{DAYNAME}</a>
				  </span><span class="{HETVEGE}">&nbsp;{SUBPERIOD_B}&nbsp;</span></td>
                  <td class="{TD_STYLE}" width="44" align="left"><span class="font1">
				  <span class=font1>&nbsp;{HOURS}</span></td>
                 <td class="{TD_STYLE}" width="110" align="right"><span class="font1">{SALL}&nbsp;</span></td>
                 <td class="{TD_STYLE}" width="110" align="right"><span class="font1">{SNO}&nbsp;</span></td>
                 <td class="{TD_STYLE}" width="110" align="right"><span class="font1">{SYES}&nbsp;</span></td>
                 <td class="{TD_STYLE}" width="164" align="right"><span class="font1">{SAVG}&nbsp;</span></td>
                </tr>
<!-- END DYNAMIC BLOCK: list_row -->	
                <tr> 
                  <td colspan="8"><img src="images/spacer.gif" width="50" height="1"></td>
                </tr>
                <tr> 
                  <td class="bggray" colspan="4" align="center"><span class="font1">{SUMTEXT}</span></td>
                  <td class="bggray" width="110" align="right"><span class="font1">{SALLSUM}&nbsp;</span></td>
                  <td class="bggray" width="110" align="right"><span class="font1">{SNOSUM}&nbsp;</span></td>
                  <td class="bggray" width="110" align="right"><span class="font1">{SYESSUM}&nbsp;</span></td>
                  <td class="bggray" width="164" align="right"><span class="font1">{SAVGSUM}&nbsp;</span></td>
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
       <img src="grafnew/bars.php?id={ID}&cache_id={CACHE_ID}&end_daynum={END_DAYNUM}&start_daynum={START_DAYNUM}&desc={DESC}&language={language}" align="center" valign="center">
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
