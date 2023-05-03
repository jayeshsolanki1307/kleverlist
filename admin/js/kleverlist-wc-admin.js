(function( $ ) {
	'use strict';	
	$( document ).ready( function() {	
        // Page load checkbox cheked check
        if( $( '#spi' ).is( ':checked' ) ) {            
            $( '.special_product_list_field' ).css('display', 'none' ).removeClass( 'hidden' ).show();
        }

        // On chceckbox checked check	
        $( document ).on( 'change','#spi', function(e){
            e.preventDefault();            
            if( $( this ).is( ':checked' )) {
                $( '.special_product_list_field' ).css('display', 'none' ).removeClass( 'hidden' ).show();
            } else if ( ! $( this) .is( ':checked' ) && $( '.special_product_list_field' ).css( 'display' ) !== 'none' ) {
                $( '.special_product_list_field' ).hide();
            }
        });
	});
})( jQuery );
