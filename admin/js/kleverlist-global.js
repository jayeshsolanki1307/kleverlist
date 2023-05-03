(function( $ ) {
	'use strict';

	$( document ).on( 'submit', '#kleverlist_global_settings', function( e ){		
		e.preventDefault();
		KleverListGlobalSettings();
	});

	function KleverListGlobalSettings()
	{
		console.log( kleverlist_object.ajax_url );
		console.log( kleverlist_object.nonce );
		const loader = document.getElementById('loader');
		const formInput =  "form#kleverlist_global_settings :input";
		
		let user_resubscribe = ( $("#kleverlist_user_resubscribe").prop('checked') == true ) ? '1' : '0';
		let active_all_products = ( $("#klerverlist_active_all_products").prop('checked') == true ) ? '1' : '0';

		let sendy_list_id = $('#global_list').val(),
			domain_name = $( '#domain_name' ).val(),
			responseClass = '.kleverlist-gloabal-response',
			data = {
				'action': 'kleverlist_global_settings',
				'global_nonce': kleverlist_object.nonce,
				'sendy_list_id': sendy_list_id,				
				'user_resubscribe': user_resubscribe,				
				'active_all_products': active_all_products,				
			};
		
		loader.classList.remove('hidden');
		
		$( formInput ).each( function(){
			$( this ).attr( "disabled", "disabled" );
		});

		$.ajax({
			type: "post",
			url: kleverlist_object.ajax_url,
			data: data,
			success: function ( response ) {
				if( response!='' ){
					$( responseClass ).show();
					$( responseClass ).html('');
					
					if( response.status ){
						$( responseClass ).addClass('success');						
						$( responseClass ).html( response.message ); 												
					}else{
						$( responseClass ).addClass('error');
						$( responseClass ).html( response.message ); 
					}

					setTimeout( function () {
						if( response.status ){
							location.reload();
						}

						$( responseClass ).removeClass('error');
						$( responseClass ).removeClass('success');
						$( responseClass ).html('');
						$( responseClass ).hide();
						
						loader.classList.add('hidden');
						$( formInput ).each( function(){
							$( this ).prop("disabled", false);
						});

					}, 2000 );
				}
			}
		});
	}
})( jQuery );