<script>
var fCat = new Array();
{if $fixed_categories[0][0].cat.cat_type == 1}
	fCat['{$fixed_categories[0][0].id}'] = '{$fixed_categories[0][0].cat.cat_rss}';
{/if}
{if $fixed_categories[1][0].cat.cat_type == 1}
	fCat['{$fixed_categories[1][0].id}'] = '{$fixed_categories[1][0].cat.cat_rss}';
{/if}

{if $fixed_categories[1][1].cat.cat_type == 1}
	fCat['{$fixed_categories[1][1].id}'] = '{$fixed_categories[1][1].cat.cat_rss}';
{/if}
{if $fixed_categories[1][2].cat.cat_type == 1}
	fCat['{$fixed_categories[1][2].id}'] = '{$fixed_categories[1][2].cat.cat_rss}';
{/if}
{if $fixed_categories[1][3].cat.cat_type == 1}
	fCat['{$fixed_categories[1][3].id}'] = '{$fixed_categories[1][3].cat.cat_rss}';
{/if}
{if $fixed_categories[1][4].cat.cat_type == 1}
	fCat['{$fixed_categories[1][4].id}'] = '{$fixed_categories[1][4].cat.cat_rss}';
{/if}

var maxBoxID = {$max_box_id|default:0};
var components = new Array();
{section name=i  loop=$struc}	
	var box = new Array();
	{section name=j loop=$struc[i]}						
		box[{$smarty.section.j.index}] = {literal}{{/literal}
			'component' : 'comp_{$page_id}_{$smarty.section.i.index}_{$smarty.section.j.index}',
			'title'     : '{$struc[i][j].title}', 
			'feed'      : '{$struc[i][j].feed_encoded}',
			'closed'    : '{$struc[i][j].closed}', 
			'items_nr'  : '{$struc[i][j].items_nr}', 
			'color'  : '{$struc[i][j].color}', 
			'type'  : '{$struc[i][j].type}',
			'editable' : '{$struc[i][j].editable}',
			'moveable' : '{$struc[i][j].moveable}', 
			'closeable' : '{$struc[i][j].closeable}'
		{literal}}{/literal};
		
	{/section}		
	components[{$smarty.section.i.index}] = box;
{/section}
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" id="main_content" style="margin:5px 0 0 0;padding:0 0 5px 0;">
		  <tr>
			{counter start=-1 skip=1 print=false assing=$boxnr}
			{section name=i loop=$struc}				
				
				{if $smarty.section.i.index == 0}		
					<td class="container" valign="top" width="33%">
				{elseif $smarty.section.i.index == 1}
					<td class="container" valign="top" width="33%" align="center">
				{else}
					<td class="container" valign="top" width="33%" align="right">
				{/if}
						 {if $smarty.section.i.index == 1}
						 	{if $fixed_categories[0][0] != ''}

                                <div class="topboxcont"><div class="bottombox"><div class="leftbox"><div class="rightbox"><div class="blbox"><div class="brbox"><div class="tlbox"><div class="trbox">

										<div class="head">											
											<h1><img src="{if $fixed_categories[0][0].cat.cat_favicon}{$fixed_categories[0][0].cat.cat_favicon}{else}i/nfi.gif{/if}" style="margin-top:-5px;width:16px; height:16px;" align="absmiddle" /> {$fixed_categories[0][0].cat.cat_name}</h1>
										</div>										
										<div class="content" id="fc_{$page_id}_{$fixed_categories[0][0].id}">
											{if $fixed_categories[0][0].cat.cat_type == 2}
												<div style="padding:3px;">{$fixed_categories[0][0].cat.cat_html}</div>												
												{if $fixed_categories[0][0].cat.cat_links !=''}
													<ul>
														{section name=k loop=$fixed_categories[0][0].cat.cat_links}
															<li><a href="http://{$fixed_categories[0][0].cat.cat_links[k].link_url}" title="{$fixed_categories[0][0].cat.cat_links[k].link_title}">{$fixed_categories[0][0].cat.cat_links[k].link_name}</a></li>
														{/section}
													</ul>
												{/if}
											{elseif $fixed_categories[0][0].cat.cat_type == 1}	
												Bet&ouml;lt&eacute;s alatt...
											{/if}
											
										</div>

</div></div></div></div></div></div></div></div>


							{/if}
						 	{if $fixed_categories[0][1] != ''}
                                <div class="topboxcont"><div class="bottombox"><div class="leftbox"><div class="rightbox"><div class="blbox"><div class="brbox"><div class="tlbox"><div class="trbox">
										<div class="head">											
											<h1><img src="{if $fixed_categories[0][1].cat.cat_favicon}{$fixed_categories[0][1].cat.cat_favicon}{else}i/nfi.gif{/if}" style="margin-top:-5px;width:16px; height:16px;" align="absmiddle" /> {$fixed_categories[0][1].cat.cat_name}</h1>
										</div>										
										<div class="content" id="fc_{$page_id}_{$fixed_categories[0][1].id}">
											{if $fixed_categories[0][1].cat.cat_type == 2}
												<div style="padding:3px;">{$fixed_categories[0][1].cat.cat_html}</div>												
												{if $fixed_categories[0][1].cat.cat_links !=''}
													<ul>
														{section name=k loop=$fixed_categories[0][1].cat.cat_links}
															<li><a href="http://{$fixed_categories[0][1].cat.cat_links[k].link_url}" title="{$fixed_categories[0][1].cat.cat_links[k].link_title}">{$fixed_categories[0][1].cat.cat_links[k].link_name}</a></li>
														{/section}
													</ul>
												{/if}
											{elseif $fixed_categories[0][1].cat.cat_type == 1}	
												Bet&ouml;lt&eacute;s alatt...
											{/if}
											
										</div>
</div></div></div></div></div></div></div></div>
							{/if}
						 	{if $fixed_categories[0][2] != ''}
                                <div class="topboxcont"><div class="bottombox"><div class="leftbox"><div class="rightbox"><div class="blbox"><div class="brbox"><div class="tlbox"><div class="trbox">
										<div class="head">											
											<h1><img src="{if $fixed_categories[0][2].cat.cat_favicon}{$fixed_categories[0][2].cat.cat_favicon}{else}i/nfi.gif{/if}" style="margin-top:-5px;width:16px; height:16px;" align="absmiddle" /> {$fixed_categories[0][2].cat.cat_name}</h1>
										</div>										
										<div class="content" id="fc_{$page_id}_{$fixed_categories[0][2].id}">
											{if $fixed_categories[0][2].cat.cat_type == 2}
												<div style="padding:3px;">{$fixed_categories[0][2].cat.cat_html}</div>												
												{if $fixed_categories[0][2].cat.cat_links !=''}
													<ul>
														{section name=k loop=$fixed_categories[0][2].cat.cat_links}
															<li><a href="http://{$fixed_categories[0][2].cat.cat_links[k].link_url}" title="{$fixed_categories[0][2].cat.cat_links[k].link_title}">{$fixed_categories[0][2].cat.cat_links[k].link_name}</a></li>
														{/section}
													</ul>
												{/if}
											{elseif $fixed_categories[0][2].cat.cat_type == 1}	
												Bet&ouml;lt&eacute;s alatt...
											{/if}
											
										</div>
</div></div></div></div></div></div></div></div>
							{/if}
						 {elseif $smarty.section.i.index == 2}
						 	{if $fixed_categories[1][0] != ''}

<div class="topboxcont"><div class="bottombox"><div class="leftbox"><div class="rightbox"><div class="blbox"><div class="brbox"><div class="tlbox"><div class="trbox">

										<div class="head">											
											<h1><img src="{if $fixed_categories[1][0].cat.cat_favicon}{$fixed_categories[1][0].cat.cat_favicon}{else}i/nfi.gif{/if}" style="margin-top:-5px;width:16px; height:16px;" align="absmiddle" /> {$fixed_categories[1][0].cat.cat_name}</h1>
										</div>										
										<div class="content" id="fc_{$page_id}_{$fixed_categories[1][0].id}">
											{if $fixed_categories[1][0].cat.cat_type == 2}
												<div style="padding:3px;">{$fixed_categories[1][0].cat.cat_html}</div>											
												{if $fixed_categories[1][0].cat.cat_links !=''}
													<ul>
														{section name=k loop=$fixed_categories[1][0].cat.cat_links}
															<li><a href="http://{$fixed_categories[1][0].cat.cat_links[k].link_url}" title="{$fixed_categories[1][0].cat.cat_links[k].link_title}">{$fixed_categories[1][0].cat.cat_links[k].link_name}</a></li>
														{/section}
													</ul>
												{/if}
											{elseif $fixed_categories[1][0].cat.cat_type == 1}	
												Bet&ouml;lt&eacute;s alatt...	
											{/if}

										</div>
</div></div></div></div></div></div></div></div>
							{/if}
							
							{if $fixed_categories[1][1].cat.cat_name != ''}								
										
    <div class="topboxcont"><div class="bottombox"><div class="leftbox"><div class="rightbox"><div class="blbox"><div class="brbox"><div class="tlbox"><div class="trbox">
                                        
                                        <div class="head">											
											<h1><img src="{if $fixed_categories[1][1].cat.cat_favicon}{$fixed_categories[1][1].cat.cat_favicon}{else}i/nfi.gif{/if}" style="margin-top:-5px;width:16px; height:16px;" align="absmiddle" /> {$fixed_categories[1][1].cat.cat_name}</h1>
										</div>										
										<div class="content" id="fc_{$page_id}_{$fixed_categories[1][1].id}">
											{if $fixed_categories[1][1].cat.cat_type == 2}
												<div style="padding:3px;">{$fixed_categories[1][1].cat.cat_html}</div>												
												{if $fixed_categories[1][1].cat.cat_links !=''}
													<ul>
														{section name=k loop=$fixed_categories[1][1].cat.cat_links}
															<li><a href="http://{$fixed_categories[1][1].cat.cat_links[k].link_url}" title="{$fixed_categories[1][1].cat.cat_links[k].link_title}">{$fixed_categories[1][1].cat.cat_links[k].link_name}</a></li>
														{/section}
													</ul>
												{/if}
											{elseif $fixed_categories[1][1].cat.cat_type == 1}	
												Bet&ouml;lt&eacute;s alatt...	
											{/if}											
										</div>
    </div></div></div></div></div></div></div></div>
							
                            
                            
                            {/if}
							{if $fixed_categories[1][2].cat.cat_name != ''}								

    <div class="topboxcont"><div class="bottombox"><div class="leftbox"><div class="rightbox"><div class="blbox"><div class="brbox"><div class="tlbox"><div class="trbox">
										
                                        
                                        <div class="head">											
											<h1><img src="{if $fixed_categories[1][2].cat.cat_favicon}{$fixed_categories[1][2].cat.cat_favicon}{else}i/nfi.gif{/if}" style="margin-top:-5px;width:16px; height:16px;" align="absmiddle" /> {$fixed_categories[1][2].cat.cat_name}</h1>
										</div>										
										<div class="content" id="fc_{$page_id}_{$fixed_categories[1][2].id}">
											{if $fixed_categories[1][2].cat.cat_type == 2}
												<div style="padding:3px;">{$fixed_categories[1][2].cat.cat_html}</div>												
												{if $fixed_categories[1][2].cat.cat_links !=''}
													<ul>
														{section name=k loop=$fixed_categories[1][2].cat.cat_links}
															<li><a href="http://{$fixed_categories[1][2].cat.cat_links[k].link_url}" title="{$fixed_categories[1][2].cat.cat_links[k].link_title}">{$fixed_categories[1][2].cat.cat_links[k].link_name}</a></li>
														{/section}
													</ul>
												{/if}
											{elseif $fixed_categories[1][2].cat.cat_type == 1}	
												Bet&ouml;lt&eacute;s alatt...	
											{/if}											
										</div>

    </div></div></div></div></div></div></div></div>

							{/if}
							{if $fixed_categories[1][3].cat.cat_name != ''}								

    <div class="topboxcont"><div class="bottombox"><div class="leftbox"><div class="rightbox"><div class="blbox"><div class="brbox"><div class="tlbox"><div class="trbox">

										<div class="head">											
											<h1><img src="{if $fixed_categories[1][3].cat.cat_favicon}{$fixed_categories[1][3].cat.cat_favicon}{else}i/nfi.gif{/if}" style="margin-top:-5px;width:16px; height:16px;" align="absmiddle" /> {$fixed_categories[1][3].cat.cat_name}</h1>
										</div>										
										<div class="content" id="fc_{$page_id}_{$fixed_categories[1][3].id}">
											{if $fixed_categories[1][3].cat.cat_type == 2}
												<div style="padding:3px;">{$fixed_categories[1][3].cat.cat_html}</div>												
												{if $fixed_categories[1][3].cat.cat_links !=''}
													<ul>
														{section name=k loop=$fixed_categories[1][3].cat.cat_links}
															<li><a href="http://{$fixed_categories[1][3].cat.cat_links[k].link_url}" title="{$fixed_categories[1][3].cat.cat_links[k].link_title}">{$fixed_categories[1][3].cat.cat_links[k].link_name}</a></li>
														{/section}
													</ul>
												{/if}
											{elseif $fixed_categories[1][3].cat.cat_type == 1}	
												Bet&ouml;lt&eacute;s alatt...	
											{/if}											
										</div>
    
    </div></div></div></div></div></div></div></div>
                                        

							{/if}
							{if $fixed_categories[1][4].cat.cat_name != ''}								
    
    <div class="topboxcont"><div class="bottombox"><div class="leftbox"><div class="rightbox"><div class="blbox"><div class="brbox"><div class="tlbox"><div class="trbox">

										<div class="head">											
											<h1><img src="{if $fixed_categories[1][4].cat.cat_favicon}{$fixed_categories[1][4].cat.cat_favicon}{else}i/nfi.gif{/if}" style="margin-top:-5px;width:16px; height:16px;" align="absmiddle" /> {$fixed_categories[1][4].cat.cat_name}</h1>
										</div>										
										<div class="content" id="fc_{$page_id}_{$fixed_categories[1][4].id}">
											{if $fixed_categories[1][4].cat.cat_type == 2}
												<div style="padding:3px;">{$fixed_categories[1][4].cat.cat_html}</div>												
												{if $fixed_categories[1][4].cat.cat_links !=''}
													<ul>
														{section name=k loop=$fixed_categories[1][4].cat.cat_links}
															<li><a href="http://{$fixed_categories[1][4].cat.cat_links[k].link_url}" title="{$fixed_categories[1][4].cat.cat_links[k].link_title}">{$fixed_categories[1][4].cat.cat_links[k].link_name}</a></li>
														{/section}
													</ul>
												{/if}
											{elseif $fixed_categories[1][4].cat.cat_type == 1}	
												Bet&ouml;lt&eacute;s alatt...	
											{/if}											
										</div>
</div></div></div></div></div></div></div></div>
							{/if}
						{/if}
					<div id="{$page_id}_{counter}">						
					{section name=j loop=$struc[i]}						
						{if $struc[i][j].type == 1}
            <div class="box" id="comp_{$page_id}_{$smarty.section.i.index}_{$smarty.section.j.index}" alt="{$struc[i][j].bid}" >
								<div class="head" id="rss_box_{$struc[i][j].title}">
									<div class="edit"><a href="#"><img align="top" src="i/refresh.gif"></a>{if $struc[i][j].editable == 1} <a href="#">Szerkeszt</a>{else} <a href="#"></a>{/if}{if $struc[i][j].closeable == 1}<a href="#"><img src="i/close.gif" align="top" /></a>{else}{/if}</div>
									{if $struc[i][j].color == 0}<h1 style="background:#FFFFFF;">{elseif $struc[i][j].color == 1}<h1 style="background:#FFFFE0;">{elseif $struc[i][j].color == 2}<h1>{elseif $struc[i][j].color == 3}<h1 style="background:#FFE0E1;">{elseif $struc[i][j].color == 4}<h1 style="background:#EFF5FF;">{elseif $struc[i][j].color == 5}<h1 style="background:#FEE8BD;">{/if}<a href="#"><img src="{if $struc[i][j].feed_favicon != ''}{$struc[i][j].feed_favicon}{else}i/nfi.gif{/if}" style="margin-top:-5px;width:16px; height:16px;" align="absmiddle" /></a> <a href="{$struc[i][j].agency_url}" target="_blank">{$struc[i][j].title}</a></h1>
								</div>
								<div class="editContent">
									<table border="0" cellspacing="0" cellpadding="2">
									  <tr>
										<td>C&iacute;m:</td>
										<td><input type="text" name="title" value="{$struc[i][j].title}" /></td>
									  </tr>
									  <tr>
										<td>Sz&iacute;n:</td>
										<td>
											<div id="cwhite"></div>
											<div id="cyellow"></div>
											<div id="cgreen"></div>
											<div id="cpink"></div>
											<div id="cblue"></div>
											<div id="corange"></div>
										</td>
									  </tr>
									  <tr>
										<td>Forr&aacute;s:</td>
										<td><input type="text" name="feed" value="{$struc[i][j].feed}" /></td>
									  </tr>
									  <tr>
										<td>Elemek:</td>
										<td>
											<select name="items">
												{section name=l loop=$items}
													{if $items[l] == $struc[i][j].items_nr}
														<option value="{$items[l]}" selected>{$items[l]}</option>
													{else}
														<option value="{$items[l]}">{$items[l]}</option>
													{/if}
													
												{/section}
											</select>
										</td>
									  </tr>									  
									  <tr>
										<td colspan="2"><input type="button" value="Elment" /></td>
									  </tr>
									</table>		
								</div>
								{if $struc[i][j].closed == "1"}
								<div class="content" style="display:none; ">
								{else}
								<div class="content">
								{/if}
									Bet&ouml;lt&eacute;s alatt...
								</div>
                            </div>
                        </div>
						{elseif $struc[i][j].type == 2}						
						<div class="box" id="comp_{$page_id}_{$smarty.section.i.index}_{$smarty.section.j.index}" alt="{$struc[i][j].bid}">
							{include file="webnote.html" wn_id=$struc[i][j].rid}
						</div>
						{elseif $struc[i][j].type == 3}
						<div class="box" id="comp_{$page_id}_{$smarty.section.i.index}_{$smarty.section.j.index}" alt="{$struc[i][j].bid}">
							{include file="search_box.html"}
						</div>			
						{/if}
					{/section}
					</div>
					</td>
			{sectionelse}			
				<td class="container" valign="top" width="33%"><div id="{$page_id}_{counter}"></div></td>
				<td class="container" valign="top" width="33%" align="center"><div id="{$page_id}_{counter}"></div></td>
				<td class="container" valign="top" width="33%" align="right"><div id="{$page_id}_{counter}"></div></td>	
			{/section}
			{if $smarty.section.i.max== 1}
				<td class="container" valign="top" width="33%" align="center"><div id="{$page_id}_{counter}">-</div></td>
				<td class="container" valign="top" width="33%" align="right"><div id="{$page_id}_{counter}">-</div></td>	
			{elseif  $smarty.section.i.max == 2}
				<td class="container" valign="top" width="33%" align="right"><div id="{$page_id}_{counter}"></div></td>
			{/if}
	</tr>
</table>
