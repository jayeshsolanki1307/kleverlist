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
		let user_resubscribe = ( $("#mapping_user_resubscribe").prop('checked') == true ) ? '1' : '0';
		
		// Pro Featured Start	
		let firstname = ( $("#mapping_user_firstname").prop('checked') == true ) ? '1' : '0';				
		let lastname  = ( $("#mapping_user_lastname").prop('checked') == true ) ? '1' : '0';				
		let username  = ( $("#mapping_user_username").prop('checked') == true ) ? '1' : '0';				
		let companyname  = ( $("#mapping_user_company_name").prop('checked') == true ) ? '1' : '0';				
		let country  = ( $("#mapping_user_country").prop('checked') == true ) ? '1' : '0';				
		let address1  = ( $("#mapping_user_address_line_1").prop('checked') == true ) ? '1' : '0';				
		let address2  = ( $("#mapping_user_address_line_2").prop('checked') == true ) ? '1' : '0';				
		let town_city  = ( $("#mapping_user_town_city").prop('checked') == true ) ? '1' : '0';				
		let county_district  = ( $("#mapping_user_province_county_district").prop('checked') == true ) ? '1' : '0';				
		let postcode  = ( $("#mapping_user_postcode").prop('checked') == true ) ? '1' : '0';				
		let phone  = ( $("#mapping_user_phone").prop('checked') == true ) ? '1' : '0';				
		// Pro Featured End	
				
		let mapping_user_email = ( $("#mapping_user_email").prop('checked') == true ) ? 'yes' : 'no';
		let //mapping_list_id = $('#mapping_list').val(),
			mapping_integration_type = $( '#mapping_integration_type' ).val(),
			responseClass = '.kleverlist-response',
			data = {
				'action': 'kleverlist_mapping_settings',
				'_nonce_': kleverlist_object.nonce,
				//'mapping_list_id': mapping_list_id,
				'mapping_integration_type': mapping_integration_type,
				'mapping_user_email': mapping_user_email,
				'mapping_user_fullname': user_fullname,
				'mapping_user_resubscribe': user_resubscribe,
				'mapping_user_firstname': firstname,
				'mapping_user_lastname': lastname,
				'mapping_user_username': username,
				'mapping_user_company_name': companyname,
				'mapping_user_country': country,
				'mapping_user_address_line_1': address1,
				'mapping_user_address_line_2': address2,
				'mapping_user_town_city': town_city,
				'mapping_user_province_county_district': county_district,
				'mapping_user_postcode': postcode,
				'mapping_user_phone': phone,
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
							//remove_btn.disabled = false;				
							//dropdownInput.disabled = false;				
							//generate_list_btn.disabled = false;				
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
})( jQuery );
