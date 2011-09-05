<?php

    include "../../auth.php";

    include "../../cookie_auth.php";  
    include "../../common.php";

    $plusdot="../.";
    $language = get_http("language","");
    if (empty($language)) $language="hu";
    include "../../lang/$language/statistics.lang";

    $type_id = get_http("type",0);
    
    $graphUnit = $word["title".$type_id."3"];
    $graphTitle = $graphUnit . " " . $word["title".$type_id."4"];
    $graphMeasure = $word["title".$type_id."2"];

    $cache_id=get_http("cache_id",0);

    $bars1=array();
    $bars2=array();

    $start_daynum=get_http("start_daynum",0);
    $end_daynum=get_http("end_daynum", 0);

    $res=mysql_query("SELECT FROM_DAYS(".$start_daynum."), FROM_DAYS(".$end_daynum.");");
    if ($res && mysql_num_rows($res)) {
       $startdate=mysql_result($res,0,0);
       $enddate=mysql_result($res,0,1);
    }

    if ($end_daynum-$start_daynum<=1) {
       $view_mode="hours";
       $step=3600;
    }
    else {
       $view_mode="days";
       $step=86400;
    }

    /*$res=mysql_query("select ordnum from stat_cache where id='$cache_id'");
    if ($res && mysql_num_rows($res)) {
       $bars1=explode(",",mysql_result($res,0,0));
    }

    $datanum=count($bars1);

    $labelFreq = ceil($datanum/30);
    */

    header ("Content-Type:text/xml");
    $csvPath = "";

    foreach ($_GET as $key=>$value) {
        if (!empty($csvPath)) $csvPath .= "&";
        $csvPath .= $key."=".$value;
    }

?><settings>
  <margins>0</margins>                                                   
  <redraw>true</redraw>
  <number_format>  
    <letters>
       <letter number="1000">K</letter>
       <letter number="1000000">M</letter>
       <letter number="1000000000">B</letter>
    </letters>      
  </number_format>
  
  <data_sets> 
    <data_set did="0">
       <title><?=$graphTitle;?></title>
       <short>MAXIMA</short>
       <color>7f8da9</color>
       <file_name><?=$_MX_var->baseUrl;?>/amcharts/maxima/csv.php?<?=$csvPath;?></file_name>
       <csv>
         <reverse></reverse>
         <date_format>YYYY-MM-DD</date_format>
         <columns>
           <column>date</column>
           <column>index1</column>
           <column>index2</column>
         </columns>
       </csv>

    </data_set>
  </data_sets>

  <charts>
  	<chart cid="0">
  		<height>60</height>
  		<title><?=$graphUnit;?></title>
      <border_color>#CCCCCC</border_color>
      <border_alpha>100</border_alpha>
     
      <values>
        <x>
          <bg_color>#FAC303</bg_color>
        </x>        
      </values>
      <legend>
        <show_date>true</show_date>
      </legend>

      <column_width>100</column_width>

      <events>
        <color>fac622</color>
      </events>

  		<graphs>
  			<graph gid="0">
  			  <title><?=$graphUnit;?></title>
          <color>#70DE05</color>
          <fill_alpha>60</fill_alpha>  			
  				<data_sources>
  				  <close>index2</close>
          </data_sources>
          <width>3</width>

  				<bullet>round_outline</bullet>
          <smoothed>false</smoothed>

  		    <legend>
            <date key="true" title="true"><![CDATA[{close}]]></date>
            <period key="true" title="true"><![CDATA[<?=$word['open'];?>: <b>{open}</b> <?=$word['close'];?>: <b>{close}</b>]]></period>
          </legend>         
  			</graph>     		
  			<graph gid="1">
  			  <title><?=$graphUnit;?> (<?=$word['smoothed'];?>)</title>
  				<data_sources>
  				  <close>index1</close>
          </data_sources>
          <width>3</width>
  				<bullet>round_outline</bullet>
          <smoothed>true</smoothed>

  		    <legend>
            <date key="true" title="true"><![CDATA[{close}]]></date>
            <period key="true" title="true"><![CDATA[<?=$word['open'];?>: <b>{open}</b> <?=$word['close'];?>: <b>{close}</b>]]></period>
          </legend>             
  			</graph>  
      			
  		</graphs>
  	</chart>

  </charts>

  <data_set_selector>
    <enabled>false</enabled>
  </data_set_selector>
  
  <period_selector>
		<periods>		
      <period type="DD" count="10">10D</period>
    	<period selected="true" type="MM" count="1">1M</period>
    	<period type="MM" count="3">3M</period>
    	<period type="YYYY" count="1">1Y</period>
    	<period type="YTD" count="0">YTD</period>
    	<period type="MAX">MAX</period>
		</periods>
		
		<periods_title><?=$word['zoom'];?>:</periods_title>
		<custom_period_title><?=$word['customperiod'];?>:</custom_period_title> 
  </period_selector>

  <header>
    <enabled>false</enabled>
  </header>

  <scroller>
    <graph_data_source>index2</graph_data_source>
    <resize_button_style>dragger</resize_button_style>
    <playback>
      <enabled>true</enabled>
      <speed>3</speed>
    </playback>
  </scroller>
</settings>
