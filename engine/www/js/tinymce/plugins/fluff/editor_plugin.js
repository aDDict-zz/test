(function() {
	tinymce.create( 'tinymce.plugins.fluffPlugin', {
		init : function( ed, url ) { 
			ed.addCommand('mcefluffCommand', function() {
				jQuery.post( 
					"index.php?ajax=getGallerySelectForTmce", 
					getGallerySelectAjaxOnComplete,
					"text"
				);
				return true;
			});
			ed.addButton('fluff', {
				title : 'fluff.desc',
				cmd : 'mcefluffCommand',
				image : url + '/img/fluff.gif'
			});
		}
	});
	tinymce.PluginManager.add( 'fluff', tinymce.plugins.fluffPlugin );
})();

var getGallerySelectAjaxOnComplete = function( resp ){
	jQuery.unblockUI();
	jQuery('#tMceGalleries').html( resp );
	jQuery("#tMceGalleries").dialog( "open" );
}

jQuery( function(){
	jQuery(function() {
		jQuery("#tMceGalleries").dialog({
			autoOpen: false,
			height: 500,
			width: 700,
			modal: true
		});
	});
	//jQuery('#tMceGalleries').dialog( 'option', 'resizable', true );
} );

var insertImage = function( obj ){ 
	var thisHtml = "<div class='ImageContainer'><img src='" + jQuery( obj ).attr( "rel" ) + "'  class='' /></div>";
	jQuery("#tMceGalleries").dialog( "close" );
	tinyMCE.execInstanceCommand( "tinyMCEcontent", "mceInsertContent", false, thisHtml );
}

