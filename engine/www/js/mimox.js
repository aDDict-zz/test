var inputCache = "";

addLoadEvent( init );

function init(){
	timer.addToDEPO( { "function" : inputCheck, "interval" : 150 } );
}

var inputCheck = function(){
	var thisInput = $( "#mimoxId" ).val();
	if( inputCache != thisInput && thisInput != "" ){
		/** ajax */
		var qString = "f=ajaxMimox&thisString=" + thisInput;
		$.post(
			"/ajax",
			qString,
			inputCheckOnComplete,
			"text"
		);
		inputCache = thisInput;
	}
}
var inputCheckOnComplete = function( resp ){
	var thisObject = eval('(' + resp + ')');
	if( thisObject.length > 0 ){
		addLayer( thisObject );
	}
}
var addLayer = function( obj ){
	var thisHtml = "";
	var thisResults = "";
	for( var i in obj ){
		thisResults += '<span onclick="addElement( this );" rel="' + unescape( obj[ i ] ) + '">' + unescape( obj[ i ] ) + '</span><br />';
	}
	thisHtml = '<div id="newLayer">' + thisResults + '</div>';
	$( "#newLayer" ).remove();
	$( "#domContainer" ).append( thisHtml );
}
var addElement = function( obj ){
	var newChild = '<li>' + $( "#mimoxId" ).val() + '<ul>' + $( obj ).attr( "rel" ) + '<span onclick="removeElement( this );">  Remove</span></ul>' + '</li></li>';
	$( "#thisList" ).append( newChild );
}
var removeElement = function( obj ){
	var thisParent = $( obj ).parent();
	var thatParent = $( thisParent ).parent();
	$( thisParent ).remove();
	$( thatParent ).remove();
}



/** timer */
var timer = new function(){
	this.constructor = null;
	this.DEPO = new Array();
	this.CASH = 0;
	this.addToDEPO = _addToDEPO;
	this.counter = _counter;
	this.listener = _listener;
	this.deleteFromDEPO = _deleteFromDEPO;
	this.stopListener = _stopListener;
	this.intervalId;
	function _counter(){
		timer.intervalId = setInterval( this.listener, 4 );
	}
	function _listener(){
		if( timer.CASH == 9999 ){
			timer.CASH = 0;
		} else {
			timer.CASH++;
		}
		var thisRemainder;
		var thisFunction;
		var thisObject;
		var objLength = timer.DEPO.length;
		for( var i = 0; i < objLength; i++ ){
			thisRemainder = timer.CASH % timer.DEPO[ i ][ "interval" ];
			if( thisRemainder == 0 ){
				thisFunction = timer.DEPO[ i ][ "function" ];
				thisFunction();
			}
		}

	}
	function _stopListener(){
		clearInterval( timer.intervalId );
	}
	function _addToDEPO( thisArray ){
		if( timer.DEPO.length == 0 ){
			this.counter();
		}
		var listener = 0;
		var objLength = timer.DEPO.length;
		for( var i = 0; i < objLength; i++ ){
			if( timer.DEPO[ i ][ "function" ] == thisArray[ "function" ] ){
				listener++; break;
			}
		}
		if( listener == 0 ){
			this.DEPO[ this.DEPO.length] = {
				"function" : thisArray[ 'function' ],
				"interval" : thisArray[ 'interval' ]
			};
		}
	}
	function _deleteFromDEPO( thisFunction ){
		var objLength = timer.DEPO.length - 1;
		for( var i = objLength; i >= 0; i-- ){
			if( timer.DEPO[ i ][ "function" ] == thisFunction ){
				timer.DEPO.splice( i, 1 );
			}
		}
		if( timer.DEPO.length == 0 ){
			timer.stopListener();
		}
	}
}


/** addLoadEvent **/
function addLoadEvent( func ) {
	var oldonload = window.onload;
	if (typeof window.onload != 'function') {
		window.onload = func;
	} else {
		window.onload = function() {
			if (oldonload) {
				oldonload();
			}
			func();
		}
	}
}

