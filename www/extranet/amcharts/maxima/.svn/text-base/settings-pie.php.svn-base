<?php

    include "../../auth.php";

    include "../../cookie_auth.php";  
    include "../../common.php";

    $plusdot="../.";
    $language = get_http("language","");
    $isDemog = get_http("demog","");
    $isMatrix = get_http("matrix","");


    if (empty($language)) $language="hu";
    include "../../lang/$language/statistics.lang";
    include "../../lang/$language/form.lang";

    header ("Content-Type:text/xml");
    echo '<?xml version="1.0" encoding="UTF-8"?>';

?><settings> 
  <font>Tahoma</font>                  
  <pie>
    <?php if ($isMatrix!="true") { ?>
    <inner_radius>40</inner_radius>    
    <?php } else { ?>
    <inner_radius>20</inner_radius>   
    <?php } ?>
    <height>20</height>                
    <angle>30</angle>
    <gradient>radial</gradient>                  
    <gradient_ratio>-50,0,0,-50</gradient_ratio>    
  </pie>

  
  <animation>
    <start_time>2</start_time>         
    <start_effect>bounce</start_effect>
    <pull_out_time>1.5</pull_out_time> 
    <pull_out_effect>Bounce</pull_out_effect>
    <pull_out_only_one>true</pull_out_only_one>       
    
  </animation>
  
  <data_labels>

    <show>
       <![CDATA[{title}: {percents}%]]>        
    </show>
    <line_color>#000000</line_color>           
    <line_alpha>15</line_alpha>                
    <hide_labels_percent>3</hide_labels_percent>                                       
  </data_labels>
  
  <balloon>                                     
    <show>
       <![CDATA[{title}: {value} <?php if ($isDemog!="true") { ?><?=$word['form_chart_measure'];?><?php } else { ?><?=$word['demog_info'];?><?php } ?> ({percents}%)]]>  
    </show>

  </balloon>
    
  <legend>                     
    <enabled>false</enabled>   
  </legend>    
 
  <?php if ($isDemog!="true") { ?>
  <labels>                     
    <label>
      <x>0</x>                 
      <y>40</y>                
      <align>center</align>      
      <text_size>12</text_size> 
      <text>                    
        <![CDATA[<b><?=$word['form_chart_title'];?></b>]]>
      </text>        
    </label>
  </labels>
  <?php } ?>

</settings>

