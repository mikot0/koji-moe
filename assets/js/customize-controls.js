( function( $ ) {

	$( document ).on( 'change', '.customize-control-checkbox-multiple input[type="checkbox"]', function() {

		checkbox_values = $( this ).parents( '.customize-control' ).find( 'input[type="checkbox"]:checked' ).map(
			function() {
				return this.value;
			}
		).get().join( ',' );

		if ( ! checkbox_values ) {
			checkbox_values = 'empty';
		}

		$( this ).parents( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );

	} );

} )( jQuery );