(function( $ ) {
	'use strict';	
	$( document ).ready( function() {	
        /************ Subscribe to a list ************/
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

        // Set Default list selected if product has not selected any list
        $( "#special_product_list option" ).each( function (i,v) {
            if( kleverlist_wc_object.defualt_pro_list!='' ){                
                $(this).removeAttr('selected'); 
                if( this.value === kleverlist_wc_object.defualt_pro_list ){
                    $(this).attr('selected','selected');
                }
            }
        }); 

        // Check if the element has a specific class
        if ( $( '.kleverlist-pro-featured-unsubscribe' ).hasClass( 'kleverlist-free-plan' ) ) {
            // Uncheck the checkbox
            $( '#unsubscribe_product' ).prop( 'checked', false );
        }

        $( document ).on( 'click', '.kleverlist-free-plan', function( e ){
            e.preventDefault();         
            $('#kleverlist-notice-popup').show();
            $( '#wp-content-wrap' ).css({'opacity':'0.5'});
        });

        $( document ).on( 'click', '.kleverlist-premium-btn', function( e ){
            e.preventDefault();
            $('#kleverlist-notice-popup').hide();
            $( '#wp-content-wrap' ).css({'opacity':'1'});
        }); 

               
        
	});
})( jQuery );
