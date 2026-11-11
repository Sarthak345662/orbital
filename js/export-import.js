( function( $ ) {

	var OrbitalEI = {

		init: function()
		{
			$( 'input[name=orbital-ei-export-button]' ).on( 'click', OrbitalEI._export );
			$( 'input[name=orbital-ei-import-button]' ).on( 'click', OrbitalEI._import );
		},

		_export: function()
		{
			window.location.href = orbitaleiConfig.customizerURL + '?orbital-ei-export=' + orbitaleiConfig.exportNonce;
		},

		_import: function()
		{
			var win			= $( window ),
				body		= $( 'body' ),
				form		= $( '<form class="orbital-ei-form" method="POST" enctype="multipart/form-data"></form>' ),
				controls	= $( '.orbital-ei-import-controls' ),
				file		= $( 'input[name=orbital-ei-import-file]' ),
				message		= $( '.orbital-ei-uploading' );

			if ( '' == file.val() ) {
				alert( orbitaleil10n.emptyImport );
			}
			else {
				win.off( 'beforeunload' );
				body.append( form );
				form.append( controls );
				message.show();
				form.submit();
			}
		}
	};

	$( OrbitalEI.init );

})( jQuery );