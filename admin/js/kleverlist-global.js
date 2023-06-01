(function( $ ) {
	'use strict';

	$( document ).on( 'submit', '#kleverlist_global_settings', function( e ){		
		e.preventDefault();
		KleverListGlobalSettings();
	});

	function KleverListGlobalSettings()
	{
		const loader = document.getElementById('global_loader');
		const formInput =  "form#kleverlist_global_settings :input";

		let user_resubscribe = ( $("#kleverlist_user_resubscribe").prop('checked') == true ) ? '1' : '0';
		
		let sendy_list_id = $('#global_list').val();
		let	responseClass = '.kleverlist-gloabal-response';
		let data = null;
		
		
		if( kleverlist_object.is_kleverlist_premium !== 'yes' ){
			data = {
				'action': 'kleverlist_global_settings',
				'global_nonce': kleverlist_object.nonce,
				'sendy_list_id': sendy_list_id,										
				'user_resubscribe': user_resubscribe,				
			};
		}		
		
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