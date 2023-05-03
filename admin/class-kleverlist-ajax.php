<?php
if ( ! defined( 'KLEVERLIST_PLUGIN_DIR' ) ) {
    die;
}
if( !class_exists( 'Kleverlist_Ajax' ) )
{
	class Kleverlist_Ajax {

		private $plugin_name;

		private $version;

		private $screen_ids;

		protected $required_plugins = [];
		
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version = $version;
			
			/** Global Settings Call **/
			add_action( 'wp_ajax_kleverlist_global_settings', array( $this, 'kleverlist_global_settings_handle' ) );
		}

		/**
		 * Global Settings Callback
		 */
		public function kleverlist_global_settings_handle(){
			$response_arr = array();
			if (
				isset( $_REQUEST['sendy_list_id'] ) && !empty( $_REQUEST['sendy_list_id'] ) && 			
				wp_verify_nonce( $_REQUEST['global_nonce'], 'kleverlist_ajax_nonce' ) 
			) {
				
				$sendy_list_id = $_REQUEST['sendy_list_id'];						
				update_option( 'kleverlist_global_sendy_list_id', $sendy_list_id );

				// User Resubscribe			
				if( isset( $_REQUEST['user_resubscribe'] ) && $_POST["user_resubscribe"] !='' ){
					$resubscribe = $_REQUEST['user_resubscribe'];
					update_option( 'kleverlist_global_resubscribe', $resubscribe );
				}	

				// Active All Products	
				if( isset( $_REQUEST['active_all_products'] ) && $_POST["active_all_products"] !='' ){
					$all_products = $_REQUEST['active_all_products'];
					update_option( 'kleverlist_global_active_all_products', $all_products );
				}			
				
				$response_arr = array(
					'status' => 1,
					'message' => __( 'Setting Saved Successfully', 'kleverlist' )
				);	
				
			}else{
				
				if ( isset( $_REQUEST['sendy_list_id'] ) && empty( $_REQUEST['sendy_list_id'] ) ) {
					$response_arr = array(
						'status' => 0,
						'message' => __( 'Please Choose your default list', 'kleverlist' )
					);	
				}	
			}
			wp_send_json( $response_arr );
			die();
		}

		
	}
}