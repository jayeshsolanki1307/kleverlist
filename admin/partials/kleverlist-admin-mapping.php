<div class="wrap kleverlist-mapping-page kleverlist-setting-page">
    <!--New Code-->
    <div id="kleverlist_mapping_settings_content" class="kleverlist-mapping-content">
        <h1 class="kleverlist_mapping_heading"><?php esc_html_e( 'Mapping', 'kleverlist' );?></h1>
        <?php
            $sendy_lists = get_option( 'kleverlist_sendy_lists', '' );   
            $mapping_list_id = null;
            $mapping_integration_type = null;
            $mapping_user_email = null;
            $mapping_settings = get_option( 'kleverlist_mapping_settings', '' );   
            
            if( !empty( $mapping_settings ) ){
                //$mapping_list_id = $mapping_settings['mapping_list_id'];
                $mapping_integration_type = $mapping_settings['mapping_integration_type'];
                $mapping_user_email = $mapping_settings['mapping_user_email'];            
            }
            
            if( empty( $sendy_lists ) ):
            ?>
            <div class="postbox kleverlist-postbox">
                <span>
                <?php                    
                    $admin_url = add_query_arg( array( 
                        'page' => 'kleverlist_dashboard', 
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
                <table class="form-table kleverlist-choose-integration">
                    <tbody>                       
                        <tr>
                            <th><?php esc_html_e( 'Choose your integration *', 'kleverlist' );?></th>
                            <td>
                                <div>
                                    <select id="mapping_integration_type" name="mapping_integration_type" required>
                                        <option value=""><?php esc_html_e( 'Choose Integration', 'kleverlist' );?></option>
                                        <?php
                                            $selected = '';
                                            $selected = ( !is_null( $mapping_integration_type ) && $mapping_integration_type === 'sendy' ) ? 'selected' : '' ;
                                            echo "<option value=\"sendy\" $selected >Sendy</option>"; 
                                        ?>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="kleverlist-sendy-integration-section <?php echo ( $mapping_integration_type === 'sendy' ) ? 'show-block': 'hide-block'?>">
                    
                    <!-- <table class="form-table kleverlist-choose-lists">
                        <tbody>
                            <tr>
                                <th><?php //esc_html_e( 'Choose your default list, type the name of an existing list in your Autoresponder *', 'kleverlist' );?></th>
                                <td>
                                    <div>
                                        <select name="mapping_list" id="mapping_list" required>
                                            <option value=""><?php //esc_html_e( 'Choose List', 'kleverlist' );?></option>
                                            <?php
                                               // $selected = '';
                                                // if( count( (array) $sendy_lists['sendy_api_lists'] ) > 0 ) {
                                                //     foreach ( $sendy_lists['sendy_api_lists'] as $key => $list ) {
                                                //     $selected = ( !is_null( $mapping_list_id ) && $mapping_list_id === $list->id ) ? 'selected' : '' ;
                                                //     echo "<option value=\"$list->id\" $selected >$list->name</option>";                                        
                                                //     }      
                                                // }
                                            ?>
                                        </select>
                                    </div>                              
                                </td>
                            </tr>

                            <tr>
                                <th></th>
                                <td>
                                    <div>
                                        <p><?php //esc_html_e( 'The default list will be used when a list has not been associated to a product. For example, if, you are selling Product A, and you have not associated any list to it, the default list will be used.', 'kleverlist' );?></p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table> -->
                    

                    <table class="form-table width-900">
                        <tbody>
                            <tr>
                                <th></th>
                                <td>
                                    <div>                                    
                                        <h4><?php esc_html_e( 'Choose which fields will be sent to the target lists in Sendy.', 'kleverlist' );?></h4>
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
                                        <?php _e( 'If set to <strong>Yes</strong>, the full name of the customer is taken from the billing information and filled into the corresponding <strong>“Name”</strong> field in Sendy.', 'kleverlist' );?>
                                    </p>                                
                                </td>
                            </tr>
                           
                            <!-- Pro featured code start -->
                            <tr>
                                <th></th>
                                <td>
                                    <div>                                    
                                        <h4><?php esc_html_e( 'Choose which extra fields you want to send to your target lists for better segmentation', 'kleverlist' );?></h4>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <th><?php esc_html_e( 'First name', 'kleverlist' );?></th>                            
                                <td>          
                                    <div class="kleverlist-container">
                                        <label class="kleverlist-switch" for="mapping_user_firstname">
                                            <input type="checkbox" name="mapping_user_firstname" class="kleverlist-mapping-checkbox" id="mapping_user_firstname" <?php checked( '1' === get_option( 'mapping_user_firstname' ) );?> value="1" />
                                            <div class="kleverlist-slider kleverlist-round"></div>
                                        </label>
                                    </div>
                                    <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                        <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/info.png'">
                                        <span class="kleverlist-tooltiptext"><?php esc_html_e( '"firstname" custom field must be manually created in Sendy in advance before to activate the toggle.', 'kleverlist' );?></span>
                                    </div>         
                                </td>
                            </tr>

                            <tr>
                                <th><?php esc_html_e( 'Last name', 'kleverlist' );?></th>                            
                                <td>
                                    <div class="kleverlist-container">
                                        <label class="kleverlist-switch" for="mapping_user_lastname">
                                            <input type="checkbox" name="mapping_user_lastname" class="kleverlist-mapping-checkbox" id="mapping_user_lastname" <?php checked( '1' === get_option( 'mapping_user_lastname' ) );?> value="1" />
                                            <div class="kleverlist-slider kleverlist-round"></div>
                                        </label>
                                    </div>
                                    <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                        <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/info.png'">
                                        <span class="kleverlist-tooltiptext"><?php esc_html_e( '"lastname" custom field must be manually created in Sendy in advance before to activate the toggle.', 'kleverlist' );?></span>
                                    </div>   
                                </td>
                            </tr>

                            <tr>
                                <th><?php esc_html_e( 'Username', 'kleverlist' );?></th>                            
                                <td>
                                    <div class="kleverlist-container">
                                        <label class="kleverlist-switch" for="mapping_user_username">
                                            <input type="checkbox" name="mapping_user_username" class="kleverlist-mapping-checkbox" id="mapping_user_username" <?php checked( '1' === get_option( 'mapping_user_username' ) );?> value="1" />
                                            <div class="kleverlist-slider kleverlist-round"></div>
                                        </label>
                                    </div>
                                    <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                        <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/info.png'">
                                        <span class="kleverlist-tooltiptext"><?php esc_html_e( '"username" custom field must be manually created in Sendy in advance before to activate the toggle.', 'kleverlist' );?></span>
                                    </div>   
                                </td>
                            </tr>

                            <tr>
                                <th><?php esc_html_e( 'Company name', 'kleverlist' );?></th>                            
                                <td>                                                            
                                    <div class="kleverlist-container">
                                        <label class="kleverlist-switch" for="mapping_user_company_name">
                                            <input type="checkbox" name="mapping_user_company_name" class="kleverlist-mapping-checkbox" id="mapping_user_company_name" <?php checked( '1' === get_option( 'mapping_user_company_name' ) );?> value="1" />
                                            <div class="kleverlist-slider kleverlist-round"></div>
                                        </label>
                                    </div>
                                    <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                        <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/info.png'">
                                        <span class="kleverlist-tooltiptext"><?php esc_html_e( '"company" custom field must be manually created in Sendy in advance before to activate the toggle.', 'kleverlist' );?></span>
                                    </div>   
                                </td>
                            </tr>

                            <tr>
                                <th><?php esc_html_e( 'Country', 'kleverlist' );?></th>                            
                                <td>                                                            
                                    <div class="kleverlist-container">
                                        <label class="kleverlist-switch" for="mapping_user_country">
                                            <input type="checkbox" name="mapping_user_country" class="kleverlist-mapping-checkbox" id="mapping_user_country" <?php checked( '1' === get_option( 'mapping_user_country' ) );?> value="1" />
                                            <div class="kleverlist-slider kleverlist-round"></div>
                                        </label>
                                    </div>
                                    <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                        <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/info.png'">
                                        <span class="kleverlist-tooltiptext"><?php esc_html_e( '"country" custom field must be manually created in Sendy in advance before to activate the toggle.', 'kleverlist' );?></span>
                                    </div>   
                                </td>
                            </tr>

                            <tr>
                                <th><?php esc_html_e( 'Address line 1', 'kleverlist' );?></th>                            
                                <td>
                                    <div class="kleverlist-container">
                                        <label class="kleverlist-switch" for="mapping_user_address_line_1">
                                            <input type="checkbox" name="mapping_user_address_line_1" class="kleverlist-mapping-checkbox" id="mapping_user_address_line_1" <?php checked( '1' === get_option( 'mapping_user_address_line_1' ) );?> value="1" />
                                            <div class="kleverlist-slider kleverlist-round"></div>
                                        </label>
                                    </div>
                                    <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                        <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/info.png'">
                                        <span class="kleverlist-tooltiptext"><?php esc_html_e( '"address1" custom field must be manually created in Sendy in advance before to activate the toggle.', 'kleverlist' );?></span>
                                    </div>   
                                </td>
                            </tr>

                            <tr>
                                <th><?php esc_html_e( 'Address line 2', 'kleverlist' );?></th>                            
                                <td>
                                    <div class="kleverlist-container">
                                        <label class="kleverlist-switch" for="mapping_user_address_line_2">
                                            <input type="checkbox" name="mapping_user_address_line_2" class="kleverlist-mapping-checkbox" id="mapping_user_address_line_2" <?php checked( '1' === get_option( 'mapping_user_address_line_2' ) );?> value="1" />
                                            <div class="kleverlist-slider kleverlist-round"></div>
                                        </label>
                                    </div>
                                    <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                        <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/info.png'">
                                        <span class="kleverlist-tooltiptext"><?php esc_html_e( '"address2" custom field must be manually created in Sendy in advance before to activate the toggle.', 'kleverlist' );?></span>
                                    </div>   
                                </td>
                            </tr>

                            <tr>
                                <th><?php esc_html_e( 'Town/City', 'kleverlist' );?></th>                            
                                <td>                                                            
                                    <div class="kleverlist-container">
                                        <label class="kleverlist-switch" for="mapping_user_town_city">
                                            <input type="checkbox" name="mapping_user_town_city" class="kleverlist-mapping-checkbox" id="mapping_user_town_city" <?php checked( '1' === get_option( 'mapping_user_town_city' ) );?> value="1" />
                                            <div class="kleverlist-slider kleverlist-round"></div>
                                        </label>
                                    </div>
                                    <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                        <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/info.png'">
                                        <span class="kleverlist-tooltiptext"><?php esc_html_e( '"city" custom field must be manually created in Sendy in advance before to activate the toggle.', 'kleverlist' );?></span>
                                    </div>   
                                </td>
                            </tr>

                            <tr>
                                <th><?php esc_html_e( 'Province/County/District', 'kleverlist' );?></th>                            
                                <td>
                                    <div class="kleverlist-container">
                                        <label class="kleverlist-switch" for="mapping_user_province_county_district">
                                            <input type="checkbox" name="mapping_user_province_county_district" class="kleverlist-mapping-checkbox" id="mapping_user_province_county_district" <?php checked( '1' === get_option( 'mapping_user_province_county_district' ) );?> value="1" />
                                            <div class="kleverlist-slider kleverlist-round"></div>
                                        </label>
                                    </div>
                                    <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                        <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/info.png'">
                                        <span class="kleverlist-tooltiptext"><?php esc_html_e( '"district" custom field must be manually created in Sendy in advance before to activate the toggle.', 'kleverlist' );?></span>
                                    </div>   
                                </td>
                            </tr>

                            <tr>
                                <th><?php esc_html_e( 'Postcode / ZIP', 'kleverlist' );?></th>                            
                                <td>                             
                                    <div class="kleverlist-container">
                                        <label class="kleverlist-switch" for="mapping_user_postcode">
                                            <input type="checkbox" name="mapping_user_postcode" class="kleverlist-mapping-checkbox" id="mapping_user_postcode" <?php checked( '1' === get_option( 'mapping_user_postcode' ) );?> value="1" />
                                            <div class="kleverlist-slider kleverlist-round"></div>
                                        </label>
                                    </div>
                                    <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                        <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/info.png'">
                                        <span class="kleverlist-tooltiptext"><?php esc_html_e( '"postcode" custom field must be manually created in Sendy in advance before to activate the toggle.', 'kleverlist' );?></span>
                                    </div>   
                                </td>
                            </tr>

                            <tr>
                                <th><?php esc_html_e( 'Phone', 'kleverlist' );?></th>                            
                                <td>
                                    <div class="kleverlist-container">
                                        <label class="kleverlist-switch" for="mapping_user_phone">
                                            <input type="checkbox" name="mapping_user_phone" class="kleverlist-mapping-checkbox" id="mapping_user_phone" <?php checked( '1' === get_option( 'mapping_user_phone' ) );?> value="1" />
                                            <div class="kleverlist-slider kleverlist-round"></div>
                                        </label>
                                    </div>
                                    <div class="kleverlist-tooltip kleverlist-tooltip-box">
                                        <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/info.png'">
                                        <span class="kleverlist-tooltiptext"><?php esc_html_e( '"phone" custom field must be manually created in Sendy in advance before to activate the toggle.', 'kleverlist' );?></span>
                                    </div>   
                                </td>
                            </tr>
                            <!-- Pro featured code end -->
                        </tbody>
                    </table>

                    <table class="form-table width-900">
                        <tbody>
                            <tr>
                                <th></th>
                                <td class="kleverlist-position">
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
            </form>
            <p class="kleverlist-response"></p>
        <?php endif;?>
    </div>
    <!--New Code-->
</div>