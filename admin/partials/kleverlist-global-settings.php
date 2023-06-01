<div class="wrap kleverlist-global-settings-page kleverlist-setting-page">
    <!--New Code-->
    <div id="kleverlist_global_settings_content" class="kleverlist-global-settings-content">
        <div class="kleverlist-main-div-integrate-icon">
            <div class="kleverlist-icon-list">
                <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/integration-icon.png" alt="">
            </div>
            <h1 class="kleverlist_mapping_heading"><?php esc_html_e( 'Global Settings', 'kleverlist' );?></h1>  
            <p class="kleverlist-page-main-description"><?php esc_html_e( 'In this page you can apply some global conditions and rules that will be applied for the integration.', 'kleverlist' );?></p>
        </div>
        <?php
            $sendy_lists = get_option( 'kleverlist_sendy_lists', '' );   
            $mapping_integration_type = null;
            $mapping_user_email = null;

            $mapping_settings = get_option( 'kleverlist_mapping_settings', '' );               
            if( !empty( $mapping_settings ) ){
                $mapping_user_email = $mapping_settings['mapping_user_email'];            
            }

            $privacy_consent_toggle = null;
            $privacy_consent_input_text = null;
            $privacy_consent = get_option( 'kleverlist_global_checkout_privacy_consent','' );
           
            if( !empty( $privacy_consent ) ){
                $privacy_consent_toggle = $privacy_consent['kleverlist_global_checkout_privacy_toggle'];
                $privacy_consent_input_text = $privacy_consent['kleverlist_global_checkout_privacy_input_text'];
            }

            if( !is_null( $privacy_consent_input_text ) ){
                $privacy_consent_text = $privacy_consent_input_text;
            }else{
                $privacy_consent_text = __('I consent to have my email address collected for marketing purposes','kleverlist');
            }
        ?>
        <div class="klever-list-settings-main">
        <form method="post" id="kleverlist_global_settings">
            <div class="kleverlist-sendy-integration-section">
                <table class="form-table kleverlist-choose-lists">
                    <tbody class="klever-list-data-settings-page">
                        <tr>
                            <th class="klever-list-data-heading-mapping"><?php esc_html_e( 'Please choose your Default List', 'kleverlist' );?></th>
                            <td>
                                <div>
                                    <select name="global_list" id="global_list">
                                        <option value=""><?php esc_html_e( 'Choose List', 'kleverlist' );?></option>
                                        <?php                                            
                                            $selected = '';
                                            if( !empty( $sendy_lists ) && count( (array) $sendy_lists['sendy_api_lists'] ) > 0 ) {
                                                foreach ( $sendy_lists['sendy_api_lists'] as $key => $list ) {
                                                $selected = ( get_option( 'kleverlist_global_sendy_list_id' ) === $list->id ) ? 'selected' : '' ;
                                                echo "<option value=\"$list->id\" $selected >$list->name</option>";                                        
                                                }      
                                            }
                                        ?>
                                    </select>
                                </div>                              
                            </td>
                        </tr>

                        <tr>
                            <th></th>
                            <td>
                                <div>
                                    <p><?php esc_html_e( 'The Default List is a “catch-all” list, used when no lists are associated to a product, in the product’s detail.', 'kleverlist' );?></p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="klever-list-data-settings-field-page">
            <table class="form-table width-900">
                <tbody class="kleverlist-free-option"> 
                    <tr>
                        <th><?php esc_html_e( 'Resubscribe', 'kleverlist' );?></th>
                        <td>          
                            <div class="kleverlist-container">
                                <label class="kleverlist-switch" for="kleverlist_user_resubscribe">
                                    <input type="checkbox" name="kleverlist_user_resubscribe" class="kleverlist-global-checkbox" id="kleverlist_user_resubscribe" <?php checked( '1' === get_option( 'kleverlist_global_resubscribe' ) );?> value="1" />
                                    <div class="kleverlist-slider kleverlist-round"></div>
                                </label>
                            </div>                          
                                                        
                            <p class="kleverlist-data">
                                <?php _e( 'If <strong>enabled</strong>, resubscribe a previously unsubscribed user in the list.', 'kleverlist' );?>
                            </p>                                
                        </td>
                    </tr>    
                </tbody>
                
                <tbody class="kleverlist-premium-option <?php echo KLEVERLIST_PLUGIN_CLASS?>">
                    <tr>
                        <th><?php esc_html_e( 'Active on All Products', 'kleverlist' );?>
                            <?php if ( KLEVERLIST_PLUGIN_PLAN === 'kle-free' ): ?>
                                <div class="pro-featured-icon">                                
                                    <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/pro_featured.png" alt="">
                                </div>   
                            <?php endif; ?>
                        </th>
                        <td>          
                            <div class="kleverlist-container">
                                <label class="kleverlist-switch" for="klerverlist_active_all_products">
                                    <input type="checkbox" name="klerverlist_active_all_products" id="klerverlist_active_all_products" <?php checked( '1' === get_option( 'kleverlist_global_active_all_products' ) );?> class="kleverlist-global-checkbox" value="1" />
                                    <div class="kleverlist-slider kleverlist-round"></div>
                                </label>
                            </div>                 
                            
                            <p class="kleverlist-data">
                                <?php _e( 'If <strong>enabled</strong>, the integration will be active on all products by default - which will have the Default List associated. Is <strong>disabled</strong>, each product must be assigned to a list manually.', 'kleverlist' );?>
                            </p> 
                        </td>
                    </tr>  
                    <tr>
                        <th><?php esc_html_e( 'Privacy Consent', 'kleverlist' );?>
                            <?php if ( KLEVERLIST_PLUGIN_PLAN === 'kle-free' ): ?>
                                <div class="pro-featured-icon">                                
                                    <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/pro_featured.png" alt="">
                                </div>   
                            <?php endif; ?> 
                        </th>
                        <td class="kleverlist-global-help-info">   
                            <div class="kleverlist-container">
                                <label class="kleverlist-switch" for="klerverlist_privacy_consent">
                                    <input type="checkbox" name="klerverlist_privacy_consent" id="klerverlist_privacy_consent" <?php checked( '1' === $privacy_consent_toggle );?> class="kleverlist-global-checkbox" value="1" />
                                    <div class="kleverlist-slider kleverlist-round"></div>
                                </label>
                                <div class="kleverlist-global-privacy-input <?php echo ( $privacy_consent_toggle === '1' && !is_null( $privacy_consent_input_text ) ) ? 'show-input': 'hide-input'?>">
                                    <input type="text" name="kleverlist_global_privacy_input" id="kleverlist_global_privacy_input" value="<?php echo $privacy_consent_text;?>">
                                </div>
                            </div>                          
                             
                            <p class="kleverlist-data">
                                <?php _e( 'If <strong>enabled</strong>, the privacy consent will be activated. Read the ? icon for further instructions.', 'kleverlist' );?>
                            </p>  
                            <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                <span class="dashicons dashicons-editor-help"></span>
                                <span class="kleverlist-tooltiptext"><?php _e('If <strong>enabled</strong>, a privacy consent checkbox will be shown in the WooCommerce checkout page. Users will be added into the lists only upon explicit consent.', 'kleverlist' );?></span>
                            </div>            
                        </td>
                    </tr>                                       
                </tbody>
            </table>

            <table class="form-table width-900">
                <tbody>
                    <tr>
                        <th></th>
                        <td class="kleverlist-position">
                            <?php 
                                $button_attributes = array( 'id' => 'global_settings' );
                                submit_button( __( 'Save Changes', 'kleverlist' ), 'primary', '', true, $button_attributes );
                            ?>
                            <div id="global_loader" class="kleverlist-loader-outer-div hidden"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
        </form>
        <p class="kleverlist-gloabal-response"></p>
        </div>        
    </div>
    <!--New Code-->
</div>
<?php
if ( KLEVERLIST_PLUGIN_PLAN === 'kle-free' ) {
    include KLEVERLIST_ROOT_DIR_ADMIN . '/partials/kleverlist-admin-notice-popup.php';
}
?>