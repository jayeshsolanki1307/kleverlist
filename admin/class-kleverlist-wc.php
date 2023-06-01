<?php

if ( !defined( 'KLEVERLIST_PLUGIN_DIR' ) ) {
    die;
}
if ( !class_exists( 'Kleverlist_WC' ) ) {
    class Kleverlist_WC
    {
        private  $plugin_name ;
        private  $version ;
        private  $screen_ids ;
        private  $privacy_consent = array() ;
        private  $privacy_consent_toggle = null ;
        private  $privacy_consent_input_text = null ;
        protected  $required_plugins = array() ;
        public function __construct( $plugin_name, $version )
        {
            $this->plugin_name = $plugin_name;
            $this->version = $version;
            $sendy_lists = get_option( 'kleverlist_sendy_lists', '' );
            
            if ( !empty($sendy_lists) ) {
                add_action( 'woocommerce_order_status_completed', array( $this, 'kleverlist_get_customer_details' ) );
                add_filter(
                    'woocommerce_product_data_tabs',
                    array( $this, 'kleverlist_custom_product_tab' ),
                    10,
                    1
                );
                add_action( 'woocommerce_product_data_panels', array( $this, 'kleverlist_wc_custom_product_panels' ) );
                add_action( 'woocommerce_process_product_meta', array( $this, 'kleverlist_wc_custom_product_save_fields' ) );
            }
        
        }
        
        public function kleverlist_custom_product_tab( $tabs )
        {
            $tabs['kleverlist_wc_custom_tab'] = array(
                'label'    => __( 'KleverList', 'kleverlist' ),
                'target'   => 'kleverlist_wc_custom_product_panels',
                'priority' => 10,
                'class'    => array( 'show_if_sendy' ),
            );
            return $tabs;
        }
        
        public function kleverlist_wc_custom_product_panels()
        {
            echo  '<div id="kleverlist_wc_custom_product_panels" class="panel woocommerce_options_panel hidden">' ;
            /******** Subscribe list ********/
            $assign_product = get_post_meta( get_the_ID(), '_special_product', true );
            $description = " ";
            
            if ( $assign_product === "yes" ) {
                $description = __( "The integration will be active, and on “order complete”, the information will be sent to the list." );
            } else {
                $description = __( "If enabled, you can subscribe the customer to a list on “order complete.", "kleverlist" );
            }
            
            
            if ( get_option( 'kleverlist_global_active_all_products' ) === '1' && (get_post_meta( get_the_ID(), '_special_product', true ) === 'no' || get_post_meta( get_the_ID(), '_special_product', true ) === '') ) {
                $checkbox_value = 'yes';
            } else {
                $checkbox_value = get_post_meta( get_the_ID(), '_special_product', true );
            }
            
            woocommerce_wp_checkbox( array(
                'id'            => 'spi',
                'value'         => $checkbox_value,
                'label'         => __( 'Subscribe to a list', 'woocommerce' ),
                'desc_tip'      => true,
                'wrapper_class' => "kleverlist_special_product",
                'description'   => $description,
            ) );
            $sendy_lists = get_option( 'kleverlist_sendy_lists', '' );
            if ( !empty($sendy_lists) ) {
                foreach ( $sendy_lists['sendy_api_lists'] as $key => $list ) {
                    $options[$list->id] = $list->name;
                }
            }
            $dropdown_tooltip = __( "Choose your list or keep the default one specified in the “Settings” section", "kleverlist" );
            woocommerce_wp_select( array(
                'id'            => 'special_product_list',
                'label'         => __( 'Choose List', 'woocommerce' ),
                'options'       => $options,
                'wrapper_class' => 'hidden',
                'desc_tip'      => true,
                'required'      => true,
                'description'   => $dropdown_tooltip,
                'value'         => get_post_meta( get_the_ID(), '_special_product_list', true ),
            ) );
            $dropdown_description = __( "The customer will be added to the selected list on “Order complete”", "kleverlist" );
            echo  '<p class="hidden special_product_list_field" style="margin-left:150px;"> ' . $dropdown_description . '</p>' ;
            /******** Subscribe list ********/
            echo  '<div class="kleverlist-pro-featured-unsubscribe ' . KLEVERLIST_PLUGIN_CLASS . '">' ;
            /******** Unsubscribe List Pro Featured ********/
            $unsubscribe_checkbox_description = __( "If enabled, you can unsubscribe the customer from a list on “order complete”", "kleverlist" );
            $unsubscribe_checkbox_value = get_post_meta( get_the_ID(), '_unsubscribe_product', true );
            woocommerce_wp_checkbox( array(
                'id'            => 'unsubscribe_product',
                'value'         => $unsubscribe_checkbox_value,
                'label'         => __( 'Unsubscribe from a list', 'woocommerce' ),
                'desc_tip'      => true,
                'wrapper_class' => "kleverlist_unsubscribe_product",
                'description'   => $unsubscribe_checkbox_description,
            ) );
            $unsubscribe_options = [];
            $sendy_lists = get_option( 'kleverlist_sendy_lists', '' );
            
            if ( !empty($sendy_lists) ) {
                $unsubscribe_options[''] = __( 'Select a list', 'woocommerce' );
                // default value
                foreach ( $sendy_lists['sendy_api_lists'] as $key => $list ) {
                    $unsubscribe_options[$list->id] = $list->name;
                }
            }
            
            $unsubscribe_dropdown_tooltip = __( "Choose your list from the dropdown", "kleverlist" );
            woocommerce_wp_select( array(
                'id'            => 'unsubscribe_product_list',
                'label'         => __( 'Choose a list', 'woocommerce' ),
                'options'       => $unsubscribe_options,
                'wrapper_class' => 'hidden',
                'desc_tip'      => true,
                'required'      => false,
                'description'   => $unsubscribe_dropdown_tooltip,
                'value'         => get_post_meta( get_the_ID(), '_unsubscribe_product_list', true ),
            ) );
            
            if ( KLEVERLIST_PLUGIN_PLAN === 'kle-free' ) {
                echo  '<div class="wc-pro-featured-icon">' ;
                echo  '<img src="' . KLEVERLIST_PLUGIN_ADMIN_DIR_URL . '/images/pro_featured.png">' ;
                echo  '</div>' ;
            }
            
            $unsubscribe_dropdown_description = __( "To ensure proper functionality of this feature, please verify that your email marketing platform does not have any global settings that unsubscribe users from all lists.", "kleverlist" );
            echo  '<p class="hidden unsubscribe_product_list_field" style="margin-left:150px;"> ' . $unsubscribe_dropdown_description . '</p>' ;
            /******** Unsubscribe List Pro Featured ********/
            echo  '</div>' ;
            echo  '</div>' ;
            if ( KLEVERLIST_PLUGIN_PLAN === 'kle-free' ) {
                include KLEVERLIST_ROOT_DIR_ADMIN . '/partials/kleverlist-admin-notice-popup.php';
            }
        }
        
        public function kleverlist_wc_custom_product_save_fields( $id )
        {
            /******** Subscribe list ********/
            $spi = ( isset( $_POST['spi'] ) && 'yes' === $_POST['spi'] ? 'yes' : 'no' );
            update_post_meta( $id, '_special_product', sanitize_text_field( $spi ) );
            
            if ( isset( $_POST['special_product_list'] ) && !empty($_POST['special_product_list']) ) {
                $special_product_list = sanitize_text_field( $_POST['special_product_list'] );
                update_post_meta( $id, '_special_product_list', $special_product_list );
            }
        
        }
        
        public function kleverlist_get_customer_details( $order_id )
        {
            if ( !$order_id ) {
                return;
            }
            // Check If API key and API URL exists or not
            $kleverlist_service_settings = get_option( 'kleverlist_service_settings', '' );
            if ( !empty($kleverlist_service_settings) ) {
                
                if ( $kleverlist_service_settings['service_verified'] != KLEVERLIST_SERVICE_VERIFIED ) {
                    return;
                } else {
                    $api_url = $kleverlist_service_settings['service_domain_name'];
                    $api_key = $kleverlist_service_settings['service_api_key'];
                }
            
            }
            // Allow code execution only once
            
            if ( !get_post_meta( $order_id, '_thankyou_action_done', true ) ) {
                // Get an instance of the WC_Order object
                $order = wc_get_order( $order_id );
                // Get an order items
                $items = $order->get_items();
                //List IDs
                $list_ids = [];
                $list_id = null;
                $unsubscribe_list_ids = [];
                foreach ( $items as $item ) {
                    $product_name = $item->get_name();
                    $product_id = $item->get_product_id();
                    $product_variation_id = $item->get_variation_id();
                    // Individual product list checkbox is checked and list is assigned (wither default/any other )
                    $pro_spi = get_post_meta( $product_id, '_special_product', true );
                    $pro_spl = get_post_meta( $product_id, '_special_product_list', true );
                    // Get Subscribe list ids
                    
                    if ( $pro_spi === 'yes' && !empty($pro_spl) ) {
                        $list_id = $pro_spl;
                    } else {
                        if ( !empty(get_option( 'kleverlist_global_sendy_list_id' )) && '1' === get_option( 'kleverlist_global_active_all_products' ) ) {
                            $list_id = get_option( 'kleverlist_global_sendy_list_id' );
                        }
                    }
                    
                    array_push( $list_ids, $list_id );
                }
                $unique_list_ids = array_unique( $list_ids );
                $is_fullname = null;
                // User Fullname
                if ( '1' === get_option( 'mapping_user_fullname' ) ) {
                    $is_fullname = get_option( 'mapping_user_fullname' );
                }
                // Get the Customer ID (User ID)
                $customer_id = $order->get_customer_id();
                // Or $order->get_user_id();
                // Get the WP_User Object instance
                $user = $order->get_user();
                // Get the Customer billing email
                $billing_email = $order->get_billing_email();
                // Customer billing information details
                $firstname = $order->get_billing_first_name();
                $lastname = $order->get_billing_last_name();
                $phone = $order->get_billing_phone();
                $company = $order->get_billing_company();
                $address_1 = $order->get_billing_address_1();
                $address_2 = $order->get_billing_address_2();
                $city = $order->get_billing_city();
                $province = $order->get_billing_state();
                $postcode = $order->get_billing_postcode();
                $country = $order->get_billing_country();
                // Username
                $user = $order->get_user();
                $username = $user->user_login;
                //Check fields
                
                if ( !empty($billing_email) ) {
                    //------ Customer Details Send to Sendy Subscribe Start ------//
                    $fullname = $firstname . ' ' . $lastname;
                    if ( !empty($list_id) ) {
                        foreach ( $unique_list_ids as $key => $listID ) {
                            // Check Subscription status
                            $subscription_status_postdata = http_build_query( array(
                                'api_key' => $api_key,
                                'email'   => $billing_email,
                                'list_id' => $listID,
                            ) );
                            $subscription_status_opts = array(
                                'http' => array(
                                'method'  => 'POST',
                                'header'  => 'Content-type: application/x-www-form-urlencoded',
                                'content' => $subscription_status_postdata,
                            ),
                            );
                            $subscription_status_context = stream_context_create( $subscription_status_opts );
                            $subscription_status_result = file_get_contents( $api_url . '/api/subscribers/subscription-status.php', false, $subscription_status_context );
                            if ( $subscription_status_result === 'Unsubscribed' && '0' === get_option( 'kleverlist_global_resubscribe' ) ) {
                                continue;
                            }
                            $postdata = http_build_query( array(
                                'name'    => ( !is_null( $is_fullname ) ? $fullname : '' ),
                                'email'   => $billing_email,
                                'list'    => $listID,
                                'api_key' => $api_key,
                                'boolean' => 'true',
                            ) );
                            $opts = array(
                                'http' => array(
                                'method'  => 'POST',
                                'header'  => 'Content-type: application/x-www-form-urlencoded',
                                'content' => $postdata,
                            ),
                            );
                            $context = stream_context_create( $opts );
                            $result = file_get_contents( $api_url . '/subscribe', false, $context );
                        }
                    }
                }
                
                $order->update_meta_data( '_thankyou_action_done', true );
            }
        
        }
        
        /**
         * Register the stylesheets for the wc admin area.
         *
         * @since    1.0.0
         */
        public function wc_enqueue_styles()
        {
            wp_enqueue_style(
                $this->plugin_name,
                KLEVERLIST_PLUGIN_ADMIN_DIR_URL . 'css/kleverlist-wc-admin.css',
                array(),
                $this->version,
                'all'
            );
        }
        
        /**
         * Register the JavaScript for the wc admin area.
         *
         * @since    1.0.0
         */
        public function wc_enqueue_scripts()
        {
            wp_enqueue_script(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'js/kleverlist-wc-admin.js',
                array( 'jquery' ),
                $this->version,
                false
            );
            // kleverlist plugin wc object
            
            if ( !empty(get_post_meta( get_the_ID(), '_special_product_list', true )) ) {
                $defualt_pro_list = '';
            } else {
                $defualt_pro_list = get_option( 'kleverlist_global_sendy_list_id', '' );
            }
            
            wp_localize_script( $this->plugin_name, 'kleverlist_wc_object', array(
                'defualt_pro_list'     => $defualt_pro_list,
                'active_all_products'  => get_option( 'kleverlist_global_active_all_products' ),
                'product_id'           => get_the_ID(),
                'special_product_type' => get_post_meta( get_the_ID(), '_special_product', true ),
            ) );
        }
    
    }
}