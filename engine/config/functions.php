<?php

function getTextPartByTextLength( $sString, $iLength ){
	if( strlen( $sString ) > 2 ){
		$sText = "";
		$aArr = array();
		$aArr = explode( " ", $sString );
		foreach( $aArr as $textPart ){
			if( strlen( $sText . $textPart ) > $iLength ){
				return $sText;
			} else {
				$sText .= " " . $textPart;
			}
		}
		return $sText;
	} else {
		return "";
	}
	
}

?>