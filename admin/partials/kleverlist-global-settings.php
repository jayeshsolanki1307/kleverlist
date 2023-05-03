<div class="wrap kleverlist-global-settings-page kleverlist-setting-page">
    <!--New Code-->
    <div id="kleverlist_global_settings_content" class="kleverlist-global-settings-content">
        <h1 class="kleverlist_mapping_heading"><?php esc_html_e( 'Global Settings', 'kleverlist' );?></h1>     
        <?php
            $sendy_lists = get_option( 'kleverlist_sendy_lists', '' );   
            $mapping_integration_type = null;
            $mapping_user_email = null;

            $mapping_settings = get_option( 'kleverlist_mapping_settings', '' );               
            if( !empty( $mapping_settings ) ){
                $mapping_integration_type = $mapping_settings['mapping_integration_type'];
                $mapping_user_email = $mapping_settings['mapping_user_email'];            
            }
        ?>
        <form method="post" id="kleverlist_global_settings">
            <div class="kleverlist-sendy-integration-section <?php //echo ( $mapping_integration_type === 'sendy' ) ? 'show-block': 'hide-block'?>">
                <table class="form-table kleverlist-choose-lists">
                    <tbody>
                        <tr>
                            <th><?php esc_html_e( 'Please choose your Default List', 'kleverlist' );?></th>
                            <td>
                                <div>
                                    <select name="global_list" id="global_list">
                                        <option value=""><?php esc_html_e( 'Choose List', 'kleverlist' );?></option>
                                        <?php
                                            $selected = '';
                                            if( count( (array) $sendy_lists['sendy_api_lists'] ) > 0 ) {
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

            <table class="form-table width-900">
                <tbody>                   
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
                                <?php _e( 'If set to <strong>Yes</strong>, resubscribe a previously unsubscribed user in the list.', 'kleverlist' );?>
                            </p>                                
                        </td>
                    </tr>    

                    <tr>
                        <th><?php esc_html_e( 'Active on All Products', 'kleverlist' );?></th>
                        <td>          
                            <div class="kleverlist-container">
                                <label class="kleverlist-switch" for="klerverlist_active_all_products">
                                    <input type="checkbox" name="klerverlist_active_all_products" id="klerverlist_active_all_products" <?php checked( '1' === get_option( 'kleverlist_global_active_all_products' ) );?> class="kleverlist-global-checkbox" value="1" />
                                    <div class="kleverlist-slider kleverlist-round"></div>
                                </label>
                            </div>                          
                                                        
                            <p class="kleverlist-data">
                                <?php _e( 'If set to <strong>Yes</strong>, the integration will be active on all products by default. Is set to <strong>No</strong>, each product must be assigned to a list manually. ', 'kleverlist' );?>
                            </p>                                
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
                            <div id="loader" class="kleverlist-loader-outer-div hidden"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        <p class="kleverlist-gloabal-response"></p>        
    </div>
    <!--New Code-->
</div>