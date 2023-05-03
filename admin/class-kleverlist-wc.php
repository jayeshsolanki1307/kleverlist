<?php
if ( ! defined( 'KLEVERLIST_PLUGIN_DIR' ) ) {
    die;
}

if( !class_exists( 'Kleverlist_WC' ) )
{
    class Kleverlist_WC
    {
        private $plugin_name;

        private $version;

        private $screen_ids;

        protected $required_plugins = [];
    
        public function __construct( $plugin_name, $version ) {

            $this->plugin_name = $plugin_name;
            $this->version = $version;

            $sendy_lists  = get_option( 'kleverlist_sendy_lists', '' );   
            if( !empty( $sendy_lists ) ){
                add_action( 'woocommerce_thankyou', array( $this, 'kleverlist_get_customer_details' ), 10, 1 ); 
            
                add_filter( 'woocommerce_product_data_tabs', array( $this, 'kleverlist_custom_product_tab') , 10, 1 );

                add_action( 'woocommerce_product_data_panels', array( $this, 'kleverlist_wc_custom_product_panels' ) );

                add_action( 'woocommerce_process_product_meta', array( $this,'kleverlist_wc_custom_product_save_fields' ) );
            }
        }
        
        public function kleverlist_custom_product_tab( $tabs ) {
           
            $tabs['kleverlist_wc_custom_tab'] = array(
                'label'   =>  __( 'KleverList', 'kleverlist' ),
                'target'  =>  'kleverlist_wc_custom_product_panels',
                'priority' => 10,
                'class'   => array( 'show_if_sendy' )
            );
            return $tabs;
        }

        public function kleverlist_wc_custom_product_panels() {
            echo '<div id="kleverlist_wc_custom_product_panels" class="panel woocommerce_options_panel hidden">';

                $assign_product = get_post_meta( get_the_ID(), '_special_product', true );
                $description = " ";
                if( $assign_product === "yes" ){
                    $description = __( "The integration will be active, and on “order complete”, the information will be sent to the list." );
                }else{
                    $description = __( "If no lists are selected, the default list specified in “Mapping” will be used.", "kleverlist" );
                }
                woocommerce_wp_checkbox(
                    array(
                        'id'      => 'spi',
                        'value'   => get_post_meta( get_the_ID(), '_special_product', true ),
                        'label'   => 'Assign a list to the product',
                        'desc_tip' => true,
                        'wrapper_class' => "special_product" , 
                        'description' => $description,
                    )
                );
               
                $sendy_lists  = get_option( 'kleverlist_sendy_lists', '' );   
                if( !empty( $sendy_lists ) ){
                    foreach ( $sendy_lists['sendy_api_lists'] as $key => $list ) {
                        $options[$list->id] = $list->name; 
                    }
                }

                woocommerce_wp_select( array(
                    'id'      => 'special_product_list',
                    'label'   => __( 'Choose List', 'woocommerce' ),
                    'options' =>  $options, 
                    'wrapper_class' => 'hidden',
                    'required' => true,
                    'value'   => get_post_meta( get_the_ID(), '_special_product_list', true ),                    
                ) );
                
                $dropdown_description = __( "The customer will be added to the selected list on “Order complete”", "kleverlist" );
                
               echo '<p class="hidden special_product_list_field" style="margin-left:150px;"> ' . $dropdown_description . '</p>';
            echo '</div>';            
        }

        public function kleverlist_wc_custom_product_save_fields( $id ){

            $spi = isset( $_POST[ 'spi' ] ) && 'yes' === $_POST[ 'spi' ] ? 'yes' : 'no';
            update_post_meta( $id, '_special_product', sanitize_text_field( $spi ) );
            
            if( isset( $_POST[ 'special_product_list' ] ) ){
                $special_product_list = sanitize_text_field( $_POST[ 'special_product_list' ] );
                update_post_meta( $id, '_special_product_list', $special_product_list );       
            }        
        }

        public function kleverlist_get_customer_details( $order_id ) {
            if ( ! $order_id )
            return;
            
            // Check If API key and API URL exists or not         
            $kleverlist_service_settings = get_option( 'kleverlist_service_settings','' );            
            if( !empty( $kleverlist_service_settings ) ){                
                if( $kleverlist_service_settings['service_verified'] != KLEVERLIST_SERVICE_VERIFIED ){
                    return;
                }else{
                    $api_url = $kleverlist_service_settings['service_domain_name'];   
                    $api_key = $kleverlist_service_settings['service_api_key'];         
                }  
            }

            // Check if integration type is sendy
            $mapping_settings = get_option( 'kleverlist_mapping_settings', '' );
            if( !empty( $mapping_settings ) ){
                if( $mapping_settings['mapping_integration_type'] !== 'sendy' ){
                    return;
                }
            }

            // Allow code execution only once 
            //if( ! get_post_meta( $order_id, '_thankyou_action_done', true ) ) {

                // Get an instance of the WC_Order object
                $order = wc_get_order( $order_id );

                // Get an order items
                $items = $order->get_items();

                //List IDs
                $list_ids = [];
                $list_id = null;

                foreach ( $items as $item ) {
                    $product_name = $item->get_name();
                    $product_id = $item->get_product_id();
                    $product_variation_id = $item->get_variation_id();

                    $pro_spi  = get_post_meta( $product_id, '_special_product', true );
                    $pro_spl  = get_post_meta( $product_id, '_special_product_list', true );

                    if( $pro_spi === 'yes' && !empty( $pro_spl ) ){
                        $list_id = $pro_spl;
                    }else if( !empty( get_option( 'kleverlist_global_sendy_list_id' ) ) ) {
                        $list_id = get_option( 'kleverlist_global_sendy_list_id' );                    
                    }
                    array_push( $list_ids, $list_id );
                }
                
                $unique_list_ids = array_unique( $list_ids );
                                
                $is_fullname = null; // User Fullname
                if( '1' === get_option( 'mapping_user_fullname' ) ) {
                    $is_fullname = get_option( 'mapping_user_fullname' );
                }

                // Get the Customer ID (User ID)
                $customer_id = $order->get_customer_id(); // Or $order->get_user_id();
                
                // Get the WP_User Object instance
                $user = $order->get_user();

                // Get the Customer billing email
                $billing_email  = $order->get_billing_email();

                // Customer billing information details
                $firstname = $order->get_billing_first_name();
                $lastname  = $order->get_billing_last_name();
                $phone     = $order->get_billing_phone();
                $company   = $order->get_billing_company();
                $address_1 = $order->get_billing_address_1();
                $address_2 = $order->get_billing_address_2();
                $city      = $order->get_billing_city();
                $province  = $order->get_billing_state();
                $postcode  = $order->get_billing_postcode();
                $country   = $order->get_billing_country();

                // Username	
                $user = $order->get_user();	
                $username = $user->user_login;	
                //------ Customer Details Send to Sendy Subscribe Start ------//       
                
                //Check fields
                if( !empty( $billing_email ) )
                {
                    $fullname = $firstname .' '.$lastname;

                    if( !empty( $list_id ) ){
                        foreach ( $unique_list_ids as $key => $listID ) {     
                            // Check Subscription status
                            $subscription_status_postdata = http_build_query(
                                array(
                                    'api_key' => $api_key,
                                    'email'   => $billing_email,
                                    'list_id' => $listID,			
                            	)
                            );
                           
                            $subscription_status_opts = array(
                                'http' => array(
                                    'method'  => 'POST', 
                                    'header'  => 'Content-type: application/x-www-form-urlencoded', 
                                    'content' => $subscription_status_postdata
                                )
                            );

                            $subscription_status_context = stream_context_create( $subscription_status_opts );
                            $subscription_status_result  = file_get_contents( $api_url.'/api/subscribers/subscription-status.php', false, $subscription_status_context );
                           
                            if( $subscription_status_result === 'Unsubscribed' && '0' === get_option( 'kleverlist_global_resubscribe' ) ){
                                continue;
                            }

                            //Subscribe
                            $postdata = http_build_query(
                                array(
                                    'name' => ( !is_null( $is_fullname ) ) ? ( $fullname ) : '',
                                    'email' => $billing_email,
                                    'list' => $listID,
                                    'api_key' => $api_key,
                                    'boolean' => 'true',
                                    'firstname' => ( '1' === get_option( 'mapping_user_firstname' ) ) ? $firstname : '',
                                    'lastname' => ( '1' === get_option( 'mapping_user_lastname' ) ) ? $lastname : '',
                                    'username' => ( '1' === get_option( 'mapping_user_username' ) ) ? $username : '',
                                    'company' => ( '1' === get_option( 'mapping_user_company_name' ) ) ? $company : '',
                                    'country' => ( '1' === get_option( 'mapping_user_country' ) ) ? $country : '',
                                    'address1' => ( '1' === get_option( 'mapping_user_address_line_1' ) ) ? $address_1 : '',
                                    'address2' => ( '1' === get_option( 'mapping_user_address_line_2' ) ) ? $address_2 : '',
                                    'city' => ( '1' === get_option( 'mapping_user_town_city' ) ) ? $city : '',
                                    'district' => ( '1' === get_option( 'mapping_user_province_county_district' ) ) ? $province : '',
                                    'postcode' => ( '1' === get_option( 'mapping_user_town_city' ) ) ? $postcode : '',
                                    'phone' => ( '1' === get_option( 'mapping_user_phone' ) ) ? $phone : '',                           
                                )
                            );

                            $opts = array(
                                'http' => array(
                                    'method'  => 'POST', 
                                    'header'  => 'Content-type: application/x-www-form-urlencoded', 
                                    'content' => $postdata
                                )
                            );
                            $context = stream_context_create( $opts );
                            $result  = file_get_contents( $api_url.'/subscribe', false, $context );
                        }
                    }                    
                }               
                //------ Customer Details Send to Sendy Subscribe End ------//

                //$order->update_meta_data( '_thankyou_action_done', true );
            //}
        }

        /**
         * Register the stylesheets for the wc admin area.
         *
         * @since    1.0.0
         */
        public function wc_enqueue_styles() {
        }

        /**
         * Register the JavaScript for the wc admin area.
         *
         * @since    1.0.0
         */
        public function wc_enqueue_scripts() {
            wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/kleverlist-wc-admin.js', array( 'jquery' ), $this->version, false );
        }
    }
}
