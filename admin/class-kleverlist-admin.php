<?php
if ( ! defined( 'KLEVERLIST_PLUGIN_DIR' ) ) {
    die;
}

class Kleverlist_Admin {

	private $plugin_name;

	private $version;

	private $screen_ids;

	protected $required_plugins = [];
	
	public function __construct( $plugin_name, $version ) {

		$this->required_plugins = [
			[
				'plugin' => 'woocommerce/woocommerce.php',
				'name'   => 'WooCommerce',
				'slug'   => 'woocommerce',
				'class'  => 'WooCommerce',
				'active' => false,
			],			
		];
		
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		/** Check Plugin Requirement **/
		add_action( 'admin_init', array( $this, 'kleverlist_plugin_requirements' ), 1 ); 

		/** Add Screen Filter for plugin screen **/
		add_filter( 'kleverlist_get_screen_ids', array( $this, 'get_screen_ids' ), 10 );

		/** Add Admin Menu Page **/
		add_action( 'admin_menu', array( $this, 'kleverlist_register_settings_page' ) ); 
		
		/** API Settings Call **/
		add_action( 'wp_ajax_kleverlist_settings', array( $this, 'kleverlist_settings_handle' ) );
		
		/** Brand Settings Call **/
		add_action( 'wp_ajax_kleverlist_generate_lists', array( $this, 'kleverlist_generate_lists_handle' ) );

		/** Mapping Settings Call **/
		add_action( 'wp_ajax_kleverlist_mapping_settings', array( $this, 'kleverlist_mapping_settings_handle' ) );

		/** Remove API Settings Call **/
		add_action( 'wp_ajax_kleverlist_remove_api_info', array( $this, 'kleverlist_remove_api_info_handle' ) );
	}

	public function kleverlist_plugin_requirements(){
		if ( ! $this->kleverlist_requirements_met() ) {
			add_action( 'admin_notices', [ $this, 'kleverlist_show_plugin_not_found_notice' ] );
			if ( is_plugin_active( plugin_basename( constant( 'KLEVERLIST_PLUGIN_FILE' ) ) ) ) {
				deactivate_plugins( plugin_basename( constant( 'KLEVERLIST_PLUGIN_FILE' ) ) );
				
				if ( isset( $_GET['activate'] ) ) {
					unset( $_GET['activate'] );
				}
				
				add_action( 'admin_notices', [ $this, 'kleverlist_show_deactivate_notice' ] );
			}
		}
	}

	/** Show required plugins not found message. **/
	public function kleverlist_show_plugin_not_found_notice() {
		$message = __( 'Kleverlist plugin requires the following plugins installed and activated: ', 'kleverlist' );

		$message_parts = [];
		foreach ( $this->required_plugins as $key => $required_plugin ) {
			if ( ! $required_plugin['active'] ) {
				$href = '/wp-admin/plugin-install.php?tab=plugin-information&plugin=';

				$href .= $required_plugin['slug'] . '&TB_iframe=true&width=640&height=500';

				$message_parts[] = '<em><a href="' . $href . '" class="thickbox">' . $required_plugin['name'] . '</a></em>';
			}
		}

		$count = count( $message_parts );
		foreach ( $message_parts as $key => $message_part ) {
			if ( 0 !== $key ) {
				if ( ( ( $count - 1 ) === $key ) ) {
					$message .= ' and ';
				} else {
					$message .= ', ';
				}
			}
			$message .= $message_part;
		}

		$message .= '.';

		$this->admin_notice( $message, 'notice notice-error is-dismissible' );
	}

	/** Show a notice to inform the user that the plugin has been deactivated. **/
	public function kleverlist_show_deactivate_notice() {
		$this->admin_notice( __( 'Kleverlist plugin has been deactivated.', 'kleverlist' ), 'notice notice-info is-dismissible' );
	}

	/** Check if plugin requirements met. **/
	private function kleverlist_requirements_met() {
		$all_active = true;
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		foreach ( $this->required_plugins as $key => $required_plugin ) {
			if ( is_plugin_active( $required_plugin['plugin'] ) ) {
				$this->required_plugins[ $key ]['active'] = true;
			} else {
				$all_active = false;
			}
		}
		return $all_active;
	}	

	private function admin_notice( $message, $class ) {
		?>
		<div class="<?php echo esc_attr( $class ); ?>">
			<p>
				<?php echo wp_kses_post( $message ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Generate List Settings Callback
	 */
	public function kleverlist_generate_lists_handle(){
		$response_arr = array();
		if (
			isset( $_REQUEST['brand_id'] ) && !empty( $_REQUEST['brand_id'] ) && 
			wp_verify_nonce( $_REQUEST['_nonce'], 'kleverlist_ajax_nonce' ) 
		) {

			$kleverlist_service_settings = get_option( 'kleverlist_service_settings' );
			if( !empty( $kleverlist_service_settings ) ){     
				if( $kleverlist_service_settings['service_verified'] === KLEVERLIST_SERVICE_VERIFIED ){
					
					$brand_id = $_REQUEST['brand_id']; 					
					$api_url  = $kleverlist_service_settings['service_domain_name'];   
                    $api_key  = $kleverlist_service_settings['service_api_key'];  
					
					$postdata = http_build_query(
						array(	  
							'api_key' => $api_key,
							'brand_id' => $brand_id,
							'include_hidden' => 'yes'
						)
					);

					$opts =  array( 'http' => array(
						'method'  => 'POST', 
						'header'  => 'Content-type: application/x-www-form-urlencoded', 
						'content' => $postdata
						)
					);

					$context  = stream_context_create( $opts );
					$result   = file_get_contents( $api_url.'/api/lists/get-lists.php', false, $context ) ;
					$response = file_get_contents( $api_url.'/api/lists/get-lists.php', false, $context ) ;

					json_decode( $result );
					
					$lists_option = [];
					$lists_option['sendy_api_brand_id'] = $brand_id;
					$lists_option['sendy_api_lists'] = json_decode( $response );
					
					switch ( json_last_error() ) {
						case JSON_ERROR_NONE:
							$response_arr = array(
								'status' => 1,
								'message' => __( 'Load Lists Successfully', 'kleverlist' )
							);	
							update_option( 'kleverlist_sendy_lists', $lists_option );
							break;			

						case JSON_ERROR_SYNTAX:
							$response_message = ( $result ) ? ( $result ) : ( 'Please verified api details' );
							$response_arr = array(
								'status' => 0,
								'message' => __( $response_message, 'kleverlist' ),
							);	
							break;
					}							
				}
			}
		}else{
			if ( isset( $_REQUEST['brand_id'] ) && empty( $_REQUEST['brand_id'] ) ){
				$response_arr = array(
					'status' => 0,
					'message' => __( 'Please choose brand', 'kleverlist' )
				);
			} else{
				$response_arr = array(
					'status' => 0,
					'message' => __( 'Something wrong, Please try again later', 'kleverlist' )
				);	
			}
		}
		wp_send_json( $response_arr );
		die();
	}

	/**
	 * Dashboard Settings Callback
	 */
	public function kleverlist_settings_handle(){
		$response_arr = array();
		if (
			isset( $_REQUEST['api_key'] ) && !empty( $_REQUEST['api_key'] ) && 
			isset( $_REQUEST['domain_name'] ) && !empty( $_REQUEST['domain_name'] ) && 
			wp_verify_nonce( $_REQUEST['nonce'], 'kleverlist_ajax_nonce' ) 
		) {
			$api_url  = sanitize_text_field( $_REQUEST['domain_name'] ); 
			$api_key  = sanitize_text_field( $_REQUEST['api_key'] ); 
		
			$service_name = sanitize_text_field( $_REQUEST['service_name'] ); 

			if (str_contains($api_url, "https://")) {
			    $api_url = $api_url;
			} else if (str_contains($api_url, "http://")) {
			   $api_url = str_replace("http://", "https://", $api_url);			  
			} else {
				$api_url = "https://".$api_url;
			}
			

			$postdata = http_build_query(
				array(	  
					'api_key' => $api_key,
				)
			);

			$opts =  array( 'http' => array(
				'method'  => 'POST', 
				'header'  => 'Content-type: application/x-www-form-urlencoded', 
				'content' => $postdata
				)
			);
			
			$context  = stream_context_create( $opts );
			$result   = file_get_contents( $api_url.'/api/brands/get-brands.php', false, $context ) ;
			$response = file_get_contents( $api_url.'/api/brands/get-brands.php', false, $context ) ;
			json_decode( $result );
		
			switch ( json_last_error() ) {
				case JSON_ERROR_NONE:
					$response_arr = array(
						'status' => 1,
						'message' => __( 'Verified Successfully', 'kleverlist' )
					);	
					$is_service_verified = KLEVERLIST_SERVICE_VERIFIED;
					$is_service_type = $service_name;
					update_option( 'kleverlist_sendy_brands', json_decode( $response ) );
					break;			

				case JSON_ERROR_SYNTAX:
					$response_message = ( $result ) ? ( $result ) : ( 'Invalid website domain name' );
					$response_arr = array(
						'status' => 0,
						'message' => __( $response_message, 'kleverlist' ),
					);	
					$is_service_type = $service_name;
					$is_service_verified = 'no';
					break;

				default:
					$response_message = ( $result ) ? ( $result ) : ( 'Please enter proper details' );
					$response_arr = array(
						'status' => 0,
						'message' => __( $response_message, 'kleverlist' ),
					);	
					break;
			}

			$option_array = [];
			$option_array['service_verified'] = $is_service_verified;
			$option_array['service_type'] = $is_service_type;
			$option_array['service_api_key'] = $api_key;
			$option_array['service_domain_name'] = $api_url;
			update_option( 'kleverlist_service_settings', $option_array );

		}else{
			if( 
				isset( $_REQUEST['api_key'] ) && empty( $_REQUEST['api_key'] ) ||
				isset( $_REQUEST['domain_name'] ) && empty( $_REQUEST['domain_name'] ) 
			){
				$response_arr = array(
					'status' => 0,
					'message' => __( 'All Input fields required', 'kleverlist' )
				);	
			}elseif ( isset( $_REQUEST['api_key'] ) && empty( $_REQUEST['api_key'] ) ) {
				$response_arr = array(
					'status' => 0,
					'message' => __( 'API Key required', 'kleverlist' )
				);	
			}elseif ( isset( $_REQUEST['domain_name'] ) && empty( $_REQUEST['domain_name'] ) ) {
				$response_arr = array(
					'status' => 0,
					'message' => __( 'Domain name required', 'kleverlist' )
				);	
			}		
		}
		wp_send_json( $response_arr );
		die();
	}

	/**
	 * Mapping Settings Callback
	 */
	public function kleverlist_mapping_settings_handle(){
		$response_arr = array();
		if (
			//isset( $_REQUEST['mapping_list_id'] ) && !empty( $_REQUEST['mapping_list_id'] ) && 
			isset( $_REQUEST['mapping_integration_type'] ) && !empty( $_REQUEST['mapping_integration_type'] ) && 
			isset( $_REQUEST['mapping_user_email'] ) && !empty( $_REQUEST['mapping_user_email'] ) && 			
			wp_verify_nonce( $_REQUEST['_nonce_'], 'kleverlist_ajax_nonce' ) 
		) {
			//$mapping_list_id = $_REQUEST['mapping_list_id'];
			$mapping_integration_type = $_REQUEST['mapping_integration_type'];
			$mapping_user_email = $_REQUEST['mapping_user_email'];
			
			$option_array = [];
			//$option_array['mapping_list_id'] = $mapping_list_id;
			$option_array['mapping_integration_type'] = $mapping_integration_type;
			$option_array['mapping_user_email'] = $mapping_user_email;						
			update_option( 'kleverlist_mapping_settings', $option_array );

			// User Full Name			
			if( isset( $_REQUEST['mapping_user_fullname'] ) && $_POST["mapping_user_fullname"] !='' ){
				$fullname = $_REQUEST['mapping_user_fullname'];
				update_option( 'mapping_user_fullname', $fullname );
			}

			/* Pro featured  field code start */
			// User First Name			
			if( isset( $_REQUEST['mapping_user_firstname'] ) && $_POST["mapping_user_firstname"] !='' ){
				$firstname = $_REQUEST['mapping_user_firstname'];
				update_option( 'mapping_user_firstname', $firstname );
			}

			// User Last Name			
			if( isset( $_REQUEST['mapping_user_lastname'] ) && $_POST["mapping_user_lastname"] !='' ){
				$lastname = $_REQUEST['mapping_user_lastname'];
				update_option( 'mapping_user_lastname', $lastname );
			}

			// User Username			
			if( isset( $_REQUEST['mapping_user_username'] ) && $_POST["mapping_user_username"] !='' ){
				$username = $_REQUEST['mapping_user_username'];
				update_option( 'mapping_user_username', $username );
			}

			// User Company Name			
			if( isset( $_REQUEST['mapping_user_company_name'] ) && $_POST["mapping_user_company_name"] !='' ){
				$company_name = $_REQUEST['mapping_user_company_name'];
				update_option( 'mapping_user_company_name', $company_name );
			}

			// User Country			
			if( isset( $_REQUEST['mapping_user_country'] ) && $_POST["mapping_user_country"] !='' ){
				$country = $_REQUEST['mapping_user_country'];
				update_option( 'mapping_user_country', $country );
			}

			// User Address1			
			if( isset( $_REQUEST['mapping_user_address_line_1'] ) && $_POST["mapping_user_address_line_1"] !='' ){
				$address1 = $_REQUEST['mapping_user_address_line_1'];
				update_option( 'mapping_user_address_line_1', $address1 );
			}

			// User Address2			
			if( isset( $_REQUEST['mapping_user_address_line_2'] ) && $_POST["mapping_user_address_line_2"] !='' ){
				$address2 = $_REQUEST['mapping_user_address_line_2'];
				update_option( 'mapping_user_address_line_2', $address2 );
			}

			// User Town/City			
			if( isset( $_REQUEST['mapping_user_town_city'] ) && $_POST["mapping_user_town_city"] !='' ){
				$town_city = $_REQUEST['mapping_user_town_city'];
				update_option( 'mapping_user_town_city', $town_city );
			}

			// User County District			
			if( isset( $_REQUEST['mapping_user_province_county_district'] ) && $_POST["mapping_user_province_county_district"] !='' ){
				$county_district = $_REQUEST['mapping_user_province_county_district'];
				update_option( 'mapping_user_province_county_district', $county_district );
			}

			// User Postcode/Zip		
			if( isset( $_REQUEST['mapping_user_postcode'] ) && $_POST["mapping_user_postcode"] !='' ){
				$postcode = $_REQUEST['mapping_user_postcode'];
				update_option( 'mapping_user_postcode', $postcode );
			}

			// User Phone	
			if( isset( $_REQUEST['mapping_user_phone'] ) && $_POST["mapping_user_phone"] !='' ){
				$phone = $_REQUEST['mapping_user_phone'];
				update_option( 'mapping_user_phone', $phone );
			}
			/* Pro featured  field code end */
			
			$response_arr = array(
				'status' => 1,
				'message' => __( 'Setting Saved Successfully', 'kleverlist' )
			);	
			
		}else{
			
			/*if( 
				isset( $_REQUEST['mapping_list_id'] ) && empty( $_REQUEST['mapping_list_id'] ) 
			){
				$response_arr = array(
					'status' => 0,
					'message' => __( 'Please Choose your default list', 'kleverlist' )
				);	
			}*/
			if ( isset( $_REQUEST['mapping_integration_type'] ) && empty( $_REQUEST['mapping_integration_type'] ) ) {
				$response_arr = array(
					'status' => 0,
					'message' => __( 'Please Choose your integration type', 'kleverlist' )
				);	
			}elseif ( isset( $_REQUEST['mapping_user_email'] ) && $_REQUEST['mapping_user_email'] === 'no' ) {
				$response_arr = array(
					'status' => 0,
					'message' => __( 'Email is required', 'kleverlist' )
				);	
			}else{
				$response_arr = array(
					'status' => 0,
					'message' => __( 'Something went wrong, Please try again later', 'kleverlist' )
				);
			}		
		}
		wp_send_json( $response_arr );
		die();
	}

	/**
	 * Remove API Settings Callback
	 */
	public function kleverlist_remove_api_info_handle()
	{
		$response_arr = array();
		if ( wp_verify_nonce( $_REQUEST['__nonce'], 'kleverlist_ajax_nonce' ) ) {
			
			// API Configuration details
			delete_option( 'kleverlist_service_settings' );

			// Remove brand details
			delete_option( 'kleverlist_sendy_brands' );
			
			// Remove list details
			delete_option( 'kleverlist_sendy_lists' );

			// Remove mapping settings
			delete_option( 'kleverlist_mapping_settings' );

			$response_arr = array(
				'status' => 1,
				'message' => __( 'API Info removed successfully', 'kleverlist' )
			);	

			wp_send_json( $response_arr );
			die();
		}
	}

	public function kleverlist_admin_notice() {		 
		$class = 'notice notice-error is-dismissible';
		$message = __( 'Woocommerce required for ' . $this->plugin_name . ' plugin.', 'sample-text-domain' );
	
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );		
	}
	
	/**
	 * Add plugin screen function
	 */
	public function get_screen_ids( $screen_ids ) {       
        $screen_ids[] = 'toplevel_page_' . $this->plugin_name . '_dashboard';
		$screen_ids[] = 'kleverlist_page_kleverlist_mapping';		
		$screen_ids[] = 'kleverlist_page_kleverlist_global_settings';
        return $screen_ids;
    }

	/**
	* Register a custom menu page.
	*/
	public function kleverlist_register_settings_page() {
		// Top Level menu
		add_menu_page(
			__( 'KleverList', 'kleverlist' ), #page_title
			'KleverList', #menu_title
			'manage_options', #caapability
			'kleverlist_dashboard', #menu_slug
			array($this, 'kleverlist_dashboards_settings_page'), #callback
			'dashicons-buddicons-pm', #icon_url
			null #position
		);

		// Dashboard Submenu
		add_submenu_page( 
			'kleverlist_dashboard',  #parent_slug
			__( 'Integrations','kleverlist' ), #submenu_page_title
			__( 'Integrations','kleverlist' ), #submenu_title
			'manage_options', #caapability
			'kleverlist_dashboard', #submenu_slug
			[ $this, 'kleverlist_dashboards_settings_page' ] #submenu_callback
		);

		// Mapping Submenu
		add_submenu_page( 
			'kleverlist_dashboard',  #parent_slug
			__( 'Mapping','kleverlist' ), #submenu_page_title
			__( 'Mapping','kleverlist' ), #submenu_title
			'manage_options', #caapability
			'kleverlist_mapping', #submenu_slug
			[ $this, 'kleverlist_mapping_submenu_page' ] #submenu_callback
		);

		// Global Settings Submenu
		add_submenu_page( 
			'kleverlist_dashboard',  #parent_slug
			__( 'Settings','kleverlist' ), #submenu_page_title
			__( 'Settings','kleverlist' ), #submenu_title
			'manage_options', #caapability
			'kleverlist_global_settings', #submenu_slug
			[ $this, 'kleverlist_global_settings_submenu_page' ] #submenu_callback
		);
	}

	/**
	 * Dashboard Menu Callback Function
	*/
	public function kleverlist_dashboards_settings_page() {
		include KLEVERLIST_ROOT_DIR_ADMIN . '/partials/kleverlist-admin-dashboard.php';		
	}
	
	/**
	 * Mapping Submenu Callback Function
	*/
	public function kleverlist_mapping_submenu_page() {				
		include KLEVERLIST_ROOT_DIR_ADMIN . '/partials/kleverlist-admin-mapping.php';		
	}

	/**
	 * Global Settings Submenu Callback Function
	*/
	public function kleverlist_global_settings_submenu_page() {				
		include KLEVERLIST_ROOT_DIR_ADMIN . '/partials/kleverlist-global-settings.php';	
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {		
		$this->screen_ids = apply_filters( 'kleverlist_get_screen_ids',$this->screen_ids) ;				
        if ( in_array( get_current_screen()->id, $this->screen_ids ) ) {

			wp_enqueue_style( $this->plugin_name, KLEVERLIST_PLUGIN_ADMIN_DIR_URL . 'css/kleverlist-admin.css', array(), $this->version, 'all' );

		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$this->screen_ids = apply_filters( 'kleverlist_get_screen_ids',$this->screen_ids) ;				
        if ( in_array( get_current_screen()->id, $this->screen_ids ) ) {

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/kleverlist-admin.js', array( 'jquery' ), $this->version, false );

			wp_enqueue_script( 'global', plugin_dir_url( __FILE__ ) . 'js/kleverlist-global.js', array( 'jquery' ), $this->version, false );

			// kleverlist plugin object
			wp_localize_script( $this->plugin_name, 'kleverlist_object', array(
                'ajax_url'   => admin_url( 'admin-ajax.php' ), 
                'admin_url'  => admin_url(), 
                'nonce' => wp_create_nonce( 'kleverlist_ajax_nonce' ),                
            ));
		}
	}
}