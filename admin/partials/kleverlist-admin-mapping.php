<div class="wrap kleverlist-mapping-page kleverlist-setting-page">
    <!--New Code-->
    <div id="kleverlist_mapping_settings_content" class="kleverlist-mapping-content">
        <div class="kleverlist-main-div-integrate-icon">
            <div class="kleverlist-icon-list">
                <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/integration-icon.png" alt="">
            </div>
            <h1 class="kleverlist_mapping_heading"><?php esc_html_e( 'Mapping for Sendy Integration', 'kleverlist' );?></h1>
        </div>
        <p class="kleverlist-page-main-description"><?php esc_html_e( 'In this page, you can choose which billing and customers fields will be synchronized with Sendy. You can enable or disable this fields at any time, only the email is mandatory. Once the mapping is done, to assign products to a specific list, you need to open the Product’s details in the “Product Data” section.', 'kleverlist' );?></p>
        <?php
            $sendy_lists = get_option( 'kleverlist_sendy_lists', '' );   
            $mapping_list_id = null;
            $mapping_integration_type = null;
            $mapping_user_email = null;
            $mapping_settings = get_option( 'kleverlist_mapping_settings', '' );   
            
            if( !empty( $mapping_settings ) ){
                $mapping_user_email = $mapping_settings['mapping_user_email'];            
            }
            
            if( empty( $sendy_lists ) ):
            ?>
            <div class="postbox kleverlist-postbox">
                <span>
                <?php                    
                    $admin_url = add_query_arg( array( 
                        'page' => 'kleverlist-integrations', 
                    ), admin_url( 'admin.php' ) );

                    printf(
                        esc_html__( '%1$s %2$s', 'kleverlist' ),
                        esc_html__( 'Please Configure API and then generate list from Integrations tab.', 'kleverlist' ),
                        sprintf(
                            '<a href="%s">%s</a>',
                            esc_url( $admin_url ),
                            esc_html__( 'Go to Integrations', 'kleverlist' )
                        )
                    );
                ?>     
                </span>
            </div>
        <?php endif;?>

        <?php             
            if( !empty( $sendy_lists ) ):
            ?>
            <form method="post" id="kleverlist_mapping_settings">  
                <div class="kleverlist-sendy-integration-section">        
                    <div class="klever-list-data-main-mapping-bg klever-list-mapping-target-field">
                        <table class="form-table width-900 ">
                           <div class="kleverlist-mapping-page-heading">
                                <h2><?php esc_html_e( 'Basic Mapping', 'kleverlist' );?></h2>
                                <p><?php esc_html_e( 'Basic Mapping enables you to synchronize the essential billing fields from your customers and integrate them with your Sendy lists.', 'kleverlist' );?></p>
                           </div>
                            <tbody class="kleverlist-data-mapping-bg">
                                <tr>
                                    <th></th>
                                    <td class="klever-list-data-mappinng-page-heading">
                                        <div>                                    
                                            <h4><?php esc_html_e( 'Choose the fields you want to send to your target lists.', 'kleverlist' );?></h4>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Email *', 'kleverlist' );?></th>
                                    <td>
                                        <input
                                            type="checkbox"
                                            id="mapping_user_email"
                                            name="mapping_user_email"                                    
                                            checked="checked" required disabled="disabled" />
                                            <p class="kleverlist-data"><?php esc_html_e( 'The email address is taken from the billing email of the customer. This is the only mandatory field.', 'kleverlist' );?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Full Name', 'kleverlist' );?></th>
                                    <td>                 
                                        <div class="kleverlist-container">
                                            <label class="kleverlist-switch" for="mapping_user_fullname">
                                                <input type="checkbox" name="mapping_user_fullname" class="kleverlist-mapping-checkbox" id="mapping_user_fullname" <?php checked( '1' === get_option( 'mapping_user_fullname' ) );?> value="1" />
                                                <div class="kleverlist-slider kleverlist-round"></div>
                                            </label>
                                        </div>                          
                                                            
                                        <p class="kleverlist-data">
                                            <?php _e( 'If <strong>enabled</strong>, the full name of the customer is taken from the billing information and filled into the corresponding <strong>“Name”</strong> field in Sendy.', 'kleverlist' );?>
                                        </p>                                
                                    </td>
                                </tr>
                            </tbody>
                            <!-- Pro featured code start -->
                        </table>                        
                        
                        <table class="form-table width-900 ">
                            <div class="kleverlist-mapping-page-heading kleverlist-advanced">
                                <h2><?php esc_html_e( 'Advanced Mapping', 'kleverlist' );?></h2>
                                <p><?php esc_html_e( 'With Advanced Mapping, you can synchronize one or more billing and user fields from WooCommerce with your Sendy lists, providing a more complete and accurate segmentation of your subscribers.', 'kleverlist' );?></p>
                            </div>
                            <tbody class="kleverlist-premium-option kleverlist-data-mapping-bg <?php echo KLEVERLIST_PLUGIN_CLASS?>">
                                <tr>
                                    <th></th>
                                    <td class="klever-list-data-mapping-extra-field">
                                        <div>                                    
                                            <h4><?php esc_html_e( 'Choose the extra fields you want to send to your target lists. Read the ? icon for further instructions', 'kleverlist' );?></h4>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'First name', 'kleverlist' );?>
                                        <?php if ( KLEVERLIST_PLUGIN_PLAN === 'kle-free' ): ?>
                                            <div class="pro-featured-icon">                                
                                                <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/pro_featured.png" alt="">
                                            </div>   
                                        <?php endif; ?>  
                                    </th>
                                    <td>          
                                        <div class="kleverlist-container">
                                            <label class="kleverlist-switch" for="mapping_user_firstname">
                                                <input type="checkbox" name="mapping_user_firstname" class="kleverlist-mapping-checkbox" id="mapping_user_firstname" <?php checked( '1' === get_option( 'mapping_user_firstname' ) );?> value="1" />
                                                <div class="kleverlist-slider kleverlist-round"></div>
                                            </label>
                                        </div>
                                        <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                            <span class="dashicons dashicons-editor-help"></span>
                                            <span class="kleverlist-tooltiptext"><?php _e( '"firstname" custom field must be manually created in Sendy in advance before to activate the toggle.', 'kleverlist' );?></span>
                                        </div>  
                                        <p class="kleverlist-maapping-subheading"><?php _e( 'If <strong>enabled</strong>, the first name of the customer is taken from the billing information and filled into the corresponding custom field in Sendy', 'kleverlist' );?>.</p>       
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Last name', 'kleverlist' );?>
                                        <?php if ( KLEVERLIST_PLUGIN_PLAN === 'kle-free' ): ?>
                                            <div class="pro-featured-icon">                                
                                                <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/pro_featured.png" alt="">
                                            </div>   
                                        <?php endif; ?>  
                                    </th>
                                    <td>
                                        <div class="kleverlist-container">
                                            <label class="kleverlist-switch" for="mapping_user_lastname">
                                                <input type="checkbox" name="mapping_user_lastname" class="kleverlist-mapping-checkbox" id="mapping_user_lastname" <?php checked( '1' === get_option( 'mapping_user_lastname' ) );?> value="1" />
                                                <div class="kleverlist-slider kleverlist-round"></div>
                                            </label>
                                        </div>
                                        <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                            <span class="dashicons dashicons-editor-help"></span>
                                            <span class="kleverlist-tooltiptext"><?php esc_html_e( '"lastname" custom field must be manually created in Sendy in advance before to activate the toggle.', 'kleverlist' );?></span>
                                        </div> 
                                        <p class="kleverlist-maapping-subheading"><?php _e( 'If <strong>enabled</strong>, the last name of the customer is taken from the billing information and filled into the corresponding custom field in Sendy.', 'kleverlist' );?></p>  
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Username', 'kleverlist' );?>
                                        <?php if ( KLEVERLIST_PLUGIN_PLAN === 'kle-free' ): ?>
                                            <div class="pro-featured-icon">                                
                                                <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/pro_featured.png" alt="">
                                            </div>   
                                        <?php endif; ?>  
                                    </th>
                                    <td>
                                        <div class="kleverlist-container">
                                            <label class="kleverlist-switch" for="mapping_user_username">
                                                <input type="checkbox" name="mapping_user_username" class="kleverlist-mapping-checkbox" id="mapping_user_username" <?php checked( '1' === get_option( 'mapping_user_username' ) );?> value="1" />
                                                <div class="kleverlist-slider kleverlist-round"></div>
                                            </label>
                                        </div>
                                        <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                            <span class="dashicons dashicons-editor-help"></span>
                                            <span class="kleverlist-tooltiptext"><?php esc_html_e( '"username" custom field must be manually created in Sendy in advance before to activate the toggle.', 'kleverlist' );?></span>
                                        </div>   
                                        <p class="kleverlist-maapping-subheading"><?php _e( 'If <strong>enabled</strong>, the username of the customer is taken from the user information and filled into the corresponding custom field in Sendy.', 'kleverlist' );?>.</p>  
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Company name', 'kleverlist' );?>
                                        <?php if ( KLEVERLIST_PLUGIN_PLAN === 'kle-free' ): ?>
                                            <div class="pro-featured-icon">                                
                                                <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/pro_featured.png" alt="">
                                            </div>   
                                        <?php endif; ?>  
                                    </th>
                                    <td>                                                            
                                        <div class="kleverlist-container">
                                            <label class="kleverlist-switch" for="mapping_user_company_name">
                                                <input type="checkbox" name="mapping_user_company_name" class="kleverlist-mapping-checkbox" id="mapping_user_company_name" <?php checked( '1' === get_option( 'mapping_user_company_name' ) );?> value="1" />
                                                <div class="kleverlist-slider kleverlist-round"></div>
                                            </label>
                                        </div>
                                        <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                            <span class="dashicons dashicons-editor-help"></span>
                                            <span class="kleverlist-tooltiptext"><?php esc_html_e( '"company" custom field must be manually created in Sendy in advance before to activate the toggle.', 'kleverlist' );?></span>
                                        </div>   
                                        <p class="kleverlist-maapping-subheading"><?php _e( 'If <strong>enabled</strong>, the company name of the customer is taken from the billing information and filled into the corresponding custom field in Sendy.', 'kleverlist' );?></p>  
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Country', 'kleverlist' );?>
                                        <?php if ( KLEVERLIST_PLUGIN_PLAN === 'kle-free' ): ?>
                                            <div class="pro-featured-icon">                                
                                                <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/pro_featured.png" alt="">
                                            </div>   
                                        <?php endif; ?>  
                                    </th>
                                    <td>                                                            
                                        <div class="kleverlist-container">
                                            <label class="kleverlist-switch" for="mapping_user_country">
                                                <input type="checkbox" name="mapping_user_country" class="kleverlist-mapping-checkbox" id="mapping_user_country" <?php checked( '1' === get_option( 'mapping_user_country' ) );?> value="1" />
                                                <div class="kleverlist-slider kleverlist-round"></div>
                                            </label>
                                        </div>
                                        <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                            <span class="dashicons dashicons-editor-help"></span>
                                            <span class="kleverlist-tooltiptext"><?php esc_html_e( '"country" custom field must be manually created in Sendy in advance before to activate the toggle.', 'kleverlist' );?></span>
                                        </div>   
                                        <p class="kleverlist-maapping-subheading"><?php _e( 'If <strong>enabled</strong>, the country of the customer is taken from the billing information and filled into the corresponding custom field in Sendy.', 'kleverlist' );?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Address line 1', 'kleverlist' );?>
                                        <?php if ( KLEVERLIST_PLUGIN_PLAN === 'kle-free' ): ?>
                                            <div class="pro-featured-icon">                                
                                                <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/pro_featured.png" alt="">
                                            </div>   
                                        <?php endif; ?>  
                                    </th>
                                    <td>
                                        <div class="kleverlist-container">
                                            <label class="kleverlist-switch" for="mapping_user_address_line_1">
                                                <input type="checkbox" name="mapping_user_address_line_1" class="kleverlist-mapping-checkbox" id="mapping_user_address_line_1" <?php checked( '1' === get_option( 'mapping_user_address_line_1' ) );?> value="1" />
                                                <div class="kleverlist-slider kleverlist-round"></div>
                                            </label>
                                        </div>
                                        <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                            <span class="dashicons dashicons-editor-help"></span>
                                            <span class="kleverlist-tooltiptext"><?php esc_html_e( '"address1" custom field must be manually created in Sendy in advance before to activate the toggle.', 'kleverlist' );?></span>
                                        </div>   
                                        <p class="kleverlist-maapping-subheading"><?php _e( 'If <strong>enabled</strong>, the address of the customer is taken from the billing information and filled into the corresponding custom field in Sendy.', 'kleverlist' );?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Address line 2', 'kleverlist' );?>
                                        <?php if ( KLEVERLIST_PLUGIN_PLAN === 'kle-free' ): ?>
                                            <div class="pro-featured-icon">                                
                                                <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/pro_featured.png" alt="">
                                            </div>   
                                        <?php endif; ?>  
                                    </th>
                                    <td>
                                        <div class="kleverlist-container">
                                            <label class="kleverlist-switch" for="mapping_user_address_line_2">
                                                <input type="checkbox" name="mapping_user_address_line_2" class="kleverlist-mapping-checkbox" id="mapping_user_address_line_2" <?php checked( '1' === get_option( 'mapping_user_address_line_2' ) );?> value="1" />
                                                <div class="kleverlist-slider kleverlist-round"></div>
                                            </label>
                                        </div>
                                        <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                            <span class="dashicons dashicons-editor-help"></span>
                                            <span class="kleverlist-tooltiptext"><?php esc_html_e( '"address2" custom field must be manually created in Sendy in advance before to activate the toggle.', 'kleverlist' );?></span>
                                        </div>   
                                        <p class="kleverlist-maapping-subheading"><?php _e( ' If <strong>enabled</strong>, the continued address of the customer is taken from the billing information and filled into the corresponding custom field in Sendy.', 'kleverlist' );?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Town/City', 'kleverlist' );?>
                                        <?php if ( KLEVERLIST_PLUGIN_PLAN === 'kle-free' ): ?>
                                            <div class="pro-featured-icon">                                
                                                <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/pro_featured.png" alt="">
                                            </div>   
                                        <?php endif; ?>  
                                    </th>
                                    <td>                                                            
                                        <div class="kleverlist-container">
                                            <label class="kleverlist-switch" for="mapping_user_town_city">
                                                <input type="checkbox" name="mapping_user_town_city" class="kleverlist-mapping-checkbox" id="mapping_user_town_city" <?php checked( '1' === get_option( 'mapping_user_town_city' ) );?> value="1" />
                                                <div class="kleverlist-slider kleverlist-round"></div>
                                            </label>
                                        </div>
                                        <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                            <span class="dashicons dashicons-editor-help"></span>
                                            <span class="kleverlist-tooltiptext"><?php esc_html_e( '"city" custom field must be manually created in Sendy in advance before to activate the toggle.', 'kleverlist' );?></span>
                                        </div>   
                                        <p class="kleverlist-maapping-subheading"><?php _e( 'If <strong>enabled</strong>, the town/city of the customer is taken from the billing information and filled into the corresponding custom field in Sendy', 'kleverlist' );?>.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Province/County/District', 'kleverlist' );?>
                                        <?php if ( KLEVERLIST_PLUGIN_PLAN === 'kle-free' ): ?>
                                            <div class="pro-featured-icon">                                
                                                <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/pro_featured.png" alt="">
                                            </div>   
                                        <?php endif; ?>  
                                    </th>
                                    <td>
                                        <div class="kleverlist-container">
                                            <label class="kleverlist-switch" for="mapping_user_province_county_district">
                                                <input type="checkbox" name="mapping_user_province_county_district" class="kleverlist-mapping-checkbox" id="mapping_user_province_county_district" <?php checked( '1' === get_option( 'mapping_user_province_county_district' ) );?> value="1" />
                                                <div class="kleverlist-slider kleverlist-round"></div>
                                            </label>
                                        </div>
                                        <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                            <span class="dashicons dashicons-editor-help"></span>
                                            <span class="kleverlist-tooltiptext"><?php esc_html_e( '"district" custom field must be manually created in Sendy in advance before to activate the toggle.', 'kleverlist' );?></span>
                                        </div>   
                                        <p class="kleverlist-maapping-subheading"><?php _e( 'If <strong>enabled</strong>, the province/county/district of the customer is taken from the billing information and filled into the corresponding custom field in Sendy', 'kleverlist' );?>.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Postcode / ZIP', 'kleverlist' );?>
                                        <?php if ( KLEVERLIST_PLUGIN_PLAN === 'kle-free' ): ?>
                                            <div class="pro-featured-icon">                                
                                                <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/pro_featured.png" alt="">
                                            </div>   
                                        <?php endif; ?>  
                                    </th>
                                    <td>                             
                                        <div class="kleverlist-container">
                                            <label class="kleverlist-switch" for="mapping_user_postcode">
                                                <input type="checkbox" name="mapping_user_postcode" class="kleverlist-mapping-checkbox" id="mapping_user_postcode" <?php checked( '1' === get_option( 'mapping_user_postcode' ) );?> value="1" />
                                                <div class="kleverlist-slider kleverlist-round"></div>
                                            </label>
                                        </div>
                                        <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                            <span class="dashicons dashicons-editor-help"></span>
                                            <span class="kleverlist-tooltiptext"><?php esc_html_e( '"postcode" custom field must be manually created in Sendy in advance before to activate the toggle.', 'kleverlist' );?></span>
                                        </div>   
                                        <p class="kleverlist-maapping-subheading"><?php _e( 'If <strong>enabled</strong>, the postcode/zip of the customer is taken from the billing information and filled into the corresponding custom field in Sendy', 'kleverlist' );?>.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Phone', 'kleverlist' );?>
                                        <?php if ( KLEVERLIST_PLUGIN_PLAN === 'kle-free' ): ?>
                                            <div class="pro-featured-icon">                                
                                                <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/pro_featured.png" alt="">
                                            </div>   
                                        <?php endif; ?>  
                                    </th>
                                    <td>
                                        <div class="kleverlist-container">
                                            <label class="kleverlist-switch" for="mapping_user_phone">
                                                <input type="checkbox" name="mapping_user_phone" class="kleverlist-mapping-checkbox" id="mapping_user_phone" <?php checked( '1' === get_option( 'mapping_user_phone' ) );?> value="1" />
                                                <div class="kleverlist-slider kleverlist-round"></div>
                                            </label>
                                        </div>
                                        <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                            <span class="dashicons dashicons-editor-help"></span>
                                            <span class="kleverlist-tooltiptext"><?php esc_html_e( '"phone" custom field must be manually created in Sendy in advance before to activate the toggle.', 'kleverlist' );?></span>
                                        </div>   
                                        <p class="kleverlist-maapping-subheading"><?php _e( 'If <strong>enabled</strong>, the telephone number of the customer is taken from the billing information and filled into the corresponding custom field in Sendy', 'kleverlist' );?>.</p>
                                    </td>
                                </tr>
                                <!-- Pro featured code end -->
                            </tbody>
                        </table>
                        
                        <table class="form-table width-900">
                            <tbody class="kleverlist-data-mapping-bg kleverlist-margin">
                                <tr>
                                    <th></th>
                                    <td class="kleverlist-position button-mapping">
                                        <?php 
                                            $button_attributes = array( 'id' => 'mapping_settings_save' );
                                            submit_button( __( 'Save Changes', 'kleverlist' ), 'primary', '', true, $button_attributes );
                                        ?>
                                        <div class="kleverlist-loader-outer-div">
                                            <div id="loader" class="hidden"></div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
            <p class="kleverlist-response"></p>
        <?php endif;?>
    </div>
    <!--New Code-->
</div>
<?php
if ( KLEVERLIST_PLUGIN_PLAN === 'kle-free' ) {
    include KLEVERLIST_ROOT_DIR_ADMIN . '/partials/kleverlist-admin-notice-popup.php';
}
?>