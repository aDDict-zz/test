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

    header ("Content-Type:text/xml");
    echo '<?xml version="1.0" encoding="UTF-8"?>';

?><settings> 
  <font>Tahoma</font>                
  <depth>20</depth>                  
  <angle>25</angle>                  
  
  <column>
    <type>100% stacked</type>        
    <width>70</width>                
    <grow_time>2</grow_time>         
    <grow_effect>strong</grow_effect>    
    <alpha>60</alpha>                
    <data_labels>
     <![CDATA[{percents}%]]>        
    </data_labels>
    <data_labels_text_color>#FFFFFF</data_labels_text_color> 
    <balloon_text>          
      <![CDATA[{series}: {percents}% {title}</b>]]> 
    </balloon_text>    
  </column>
       
  <plot_area>              
    <margins>              
      <left>0</left>        
      <top>40</top>         
      <right>140</right>   
      <bottom>40</bottom>  
    </margins>
  </plot_area>
  
  <grid>                   
    <category>                                                                  
      <alpha>0</alpha>     
    </category>
    <value>          
      <alpha>0</alpha>
    </value>
  </grid>
  
  <values>
    <category>
        <enabled>true</enabled>
        <inside>true</inside>
        <rotate>90</rotate>
    </category>
    <value>           
      <enabled>false</enabled>    
    </value>
  </values>
  
  <axes>               
    <category>         
      <alpha>0</alpha>
    </category>
    <value>            
      <alpha>0</alpha>
    </value>
  </axes>  
  
  <legend>                       
    <enabled>true</enabled>      
    <x>800</x>                   
    <y>70</y>                    
    <width>120</width>  
    <reverse_order>true</reverse_order>         
  </legend>

  <guides>
    <guide>
    <behind>true</behind>
    </guide>
  </guides>
</settings>
