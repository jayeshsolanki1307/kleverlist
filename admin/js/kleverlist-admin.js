(function( $ ) {
	'use strict';
	/*** API Verification Code Start ***/
	$( document ).ready( function() {
		let allServiceCheckbox    = '.kleverlist-checkbox',
			settings_input_fields_class = '.settings-input-section';

		$( document ).on( 'change', allServiceCheckbox, function() {

			$( settings_input_fields_class ).removeClass('hide_setting_input');
			$( settings_input_fields_class ).removeClass('show_setting_input');
			
			// Unchecked other checkboxes
			$( allServiceCheckbox ).not( this ).prop( 'checked', false ); 
			if( this.checked ) {								
				$( settings_input_fields_class ).removeClass('hide_setting_input');
				$( settings_input_fields_class ).addClass('show_setting_input');
			}else{								
				$( settings_input_fields_class ).removeClass('show_setting_input');
				$( settings_input_fields_class ).addClass('hide_setting_input');
			}
		});
	});
	
	$( document ).on( 'submit', '#kleverlist_settings', function( e ){		
		e.preventDefault();
		connectSendyAPI();
	});

	function connectSendyAPI()
	{
		const loader = document.getElementById('loader');
		const submit_button = document.getElementById('settings_submit_button');
		const service_api_key = document.getElementById('service_api_key');
		const service_domain_name = document.getElementById('domain_name');
		let service_name = $('input[name="kleverlist_service[]"]:checked').val();
		
		let api_key = $( '#service_api_key' ).val(),
			domain_name = $( '#domain_name' ).val(),
			responseClass = '.kleverlist-response',
			data = {
				'action': 'kleverlist_settings',
				'nonce': kleverlist_object.nonce,
				'api_key': api_key,
				'domain_name': domain_name,
				'service_name': service_name,
			};
		
		loader.classList.remove('hidden');
		submit_button.disabled = true;
		service_api_key.disabled = true;
		service_domain_name.disabled = true;
		$.ajax({
			type: "post",
			url: kleverlist_object.ajax_url,
			data: data,
			success: function ( response ) {
				if( response!='' ){
					$( responseClass ).show();
					$( responseClass ).html('');
					$( responseClass ).removeClass('error');
					$( responseClass ).removeClass('success');
					if( response.status ){
						$( responseClass ).addClass('success');						
						$( responseClass ).html( response.message ); 												
					}else{
						$( responseClass ).addClass('error');
						$( responseClass ).html( response.message ); 
					}

					setTimeout( function () {
						loader.classList.add('hidden');
                        submit_button.disabled = false;
                        service_api_key.disabled = false;
                        service_domain_name.disabled = false;
                        $( responseClass ).hide();

                        if( response.status ){							
							location.reload();
						}
					}, 2000 );
				}
			}
		});
	}
	/*** API Verification Code End ***/

	/*** Generate List Code Start ***/	
	$( document ).on( 'submit', '#kleverlist_brands_settings', function( e ){		
		e.preventDefault();
		generateSendyList();
	});

	function generateSendyList(){		 
		const loader = document.getElementById('brand_loader');
		const dropdownInput = document.getElementById("sendy_brands");
		const generate_list_btn = document.getElementById("generate_lists");
		const remove_btn = document.getElementById("kleverlist_remove_settings");

		let responseClass = '.kleverlist-response-brands';
		let brand_id = $('#sendy_brands').val(),
			data = {
				'action': 'kleverlist_generate_lists',
				'_nonce': kleverlist_object.nonce,
				'brand_id': brand_id,
			};
		
			loader.classList.remove('hidden');
			dropdownInput.disabled = true;
			generate_list_btn.disabled = true;
			remove_btn.disabled = true;
			$.ajax({
				type: "post",
				url: kleverlist_object.ajax_url,
				data: data,
				success: function ( response ) {
					if( response!='' ){
						$( responseClass ).show();
						$( responseClass ).html('');
						$( responseClass ).removeClass('error');
						$( responseClass ).removeClass('success');
						if( response.status ){
							$( responseClass ).addClass('success');
							$( responseClass ).html( response.message ); 	
												
						}else{
							$( responseClass ).addClass('error');
							$( responseClass ).html( response.message ); 
						}

						setTimeout( function () {
							$( responseClass ).hide();
							
							loader.classList.add('hidden');
							dropdownInput.disabled = false;
							generate_list_btn.disabled = false;
							remove_btn.disabled = false;

							if( response.status ){
								location.reload();
							}
						}, 2000 );
					}
				}
			});

	}
	/*** Generate List Code End ***/
	
	/*** Mapping Form Save Code Start ***/
	$( document ).on( 'submit', '#kleverlist_mapping_settings', function( e ){
		e.preventDefault();
		mappingList();
	});

	function mappingList(){
		const loader = document.getElementById('loader');
		const formInput =  "form#kleverlist_mapping_settings :input";
		
		let user_fullname = ( $("#mapping_user_fullname").prop('checked') == true ) ? '1' : '0';		
				
		let mapping_user_email = ( $("#mapping_user_email").prop('checked') == true ) ? 'yes' : 'no';
		let responseClass = '.kleverlist-response';
		let data = null;
				
		if( kleverlist_object.is_kleverlist_premium !== 'yes' ){
			data = {
				'action': 'kleverlist_mapping_settings',
				'_nonce_': kleverlist_object.nonce,
				'mapping_user_email': mapping_user_email,
				'mapping_user_fullname': user_fullname,				
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
					$( responseClass ).removeClass('error');
					$( responseClass ).removeClass('success');
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
	/*** Mapping Form Save Code End ***/

	/*** Remove Button Code Start ***/
	$( document ).on( 'click', '#kleverlist_remove_settings', function( e ){
		e.preventDefault();
		removeApiInfo();
	});

	function removeApiInfo(){
		if( confirm('Are you sure you want to remove') ){
			const loader = document.getElementById('loader');
			const remove_btn = document.getElementById("kleverlist_remove_settings");
			const dropdownInput = document.getElementById("sendy_brands");
			const generate_list_btn = document.getElementById("generate_lists");

			let data = {
				'action': 'kleverlist_remove_api_info',
				'__nonce': kleverlist_object.nonce,				
			};
			loader.classList.remove('hidden');
			remove_btn.disabled = true;
			dropdownInput.disabled = true;
			generate_list_btn.disabled = true;
			
			$.ajax({
				type: "post",
				url: kleverlist_object.ajax_url,
				data: data,
				success: function ( response ) {
					if( response!='' ){
						if( response.status ){			
							loader.classList.add('hidden');			
							location.reload();
						}
					}
				}
			});
		}
	}
	/*** Remove Button Code End ***/

	/*** Sendy Option Hide / Show Code Start ***/
	$( document ).on( 'change','#mapping_integration_type', function(e){
		e.preventDefault();       
		if( this.value == "sendy" ){
			console.log( "type ===", this.value );
			$('.kleverlist-sendy-integration-section').removeClass("hide-block");
			$('.kleverlist-sendy-integration-section').addClass("show-block");
		} else {
			console.log( "type ===", this.value );
			$('.kleverlist-sendy-integration-section').removeClass("show-block");
			$('.kleverlist-sendy-integration-section').addClass("hide-block");
		}
	});
	/*** Sendy Option Hide / Show Code End ***/

	$(document).ready(function() {		
		$( document ).on( 'click', '.kleverlist-premium-btn', function( e ){
			e.preventDefault();
			$('#kleverlist-notice-popup').fadeOut(500);
		});	

		$( document ).on( 'click', '.kleverlist-free-plan', function( e ){
			e.preventDefault();			
			$('#kleverlist-notice-popup').show();
		});		
	});

})( jQuery );
