<?php
    $service_type = '';
    $service_api_key = '';
    $service_domain_name = '';
    $service_verified = '';
    
    $kleverlist_service_settings = get_option( 'kleverlist_service_settings', '' );
    if( !empty( $kleverlist_service_settings ) ){
        $service_verified = $kleverlist_service_settings['service_verified'];
        $service_type = $kleverlist_service_settings['service_type'];
        $service_api_key = $kleverlist_service_settings['service_api_key'];        
        $service_domain_name = $kleverlist_service_settings['service_domain_name'];        
    }    

    $sendy_lists = get_option( 'kleverlist_sendy_lists', '' );   
    $brands = get_option( 'kleverlist_sendy_brands','' );   
?>
<div class="wrap kleverlist-settings-page">
    <h1><?php esc_html_e( 'Settings', 'kleverlist' );?></h1>
    <!--- Dashboard Settings Form Start -->    
    <form method="POST" id="kleverlist_settings">
        <table class="form-table width-900 klever-list-data-outer-div">
            <tbody>
                <th class="klever-list-data-heading">
                    <em><?php esc_html_e( 'Choose your integration', 'kleverlist' );?></em>
                </th>
                <td>
                    <div class="kleverlist-integrations sendy">
                        <input
                            id="sendy"
                            class="kleverlist-checkbox"
                            name="kleverlist_service[]"
                            type="checkbox"
                            value="sendy"
                            <?php disabled( $service_type, 'sendy' ); ?>
                            <?php checked( $service_type, 'sendy' ); ?> />
                        <label for="sendy" role="checkbox">
                            <span
                                class="labelauty-checked-image"
                                style="background-image:url('<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/sendy-logo.png')"></span>
                            <span class="labelauty-checked" ><?php esc_html_e( 'Sendy.Co', 'kleverlist' );?></span>
                        </label>
                    </div>
                </td>
            </tbody>

            <tbody
                class="settings-input-section <?php echo ( empty( $service_type ) || $service_type == 'no' ) ? 'hide_setting_input' : 'show_setting_input'; ?>">
                <tr>
                    <td class="klever-list-data-td">
                        <label for="service_api_key">
                            <?php esc_html_e( 'API Key', 'kleverlist' );?>
                        </label><br>
                        <input
                            type="text"
                            name="service_api_key"
                            id="service_api_key"
                            placeholder="<?php esc_html_e( 'Please enter api key', 'kleverlist' );?>"
                            <?php disabled( $service_verified, KLEVERLIST_SERVICE_VERIFIED ); ?>
                            value="<?php echo esc_html( $service_api_key );?>" required/>
                        <p></p>
                    </td>
                    <td>
                        <label for="domain_name">
                            <?php esc_html_e( 'Website', 'kleverlist' );?>
                        </label><br/>
                        <input
                            id="domain_name"
                            class="kleverlist-input"
                            name="domain_name"
                            type="text"
                            placeholder="<?php esc_html_e( 'Your domain: ie. example.com', 'kleverlist' );?>"
                            <?php disabled( $service_verified, KLEVERLIST_SERVICE_VERIFIED ); ?>
                            value="<?php echo esc_html($service_domain_name ); ?>" required>
                            <p><?php esc_html_e( 'Please make sure that your website is using HTTPS. If not, the integration will not work.', 'kleverlist' );?></p>       
                    </td>             
                </tr>
                <?php if( $service_verified != KLEVERLIST_SERVICE_VERIFIED ) :?>
                <tr>
                    <td class="kleverlist-position klever-list-data-mainchanges">
                        <?php
                            $submit_btn_attributes = array( 'id' => 'settings_submit_button' );
                            submit_button( __( 'Save Changes', 'kleverlist' ), 'button button-primary', '', true, $submit_btn_attributes );
                        ?>
                        <div id="loader" class="kleverlist-loader-outer-div hidden"></div>                 
                    </td>
                </tr>
                <?php endif;?>
            </tbody>
        </table>
        <?php if( $service_verified != KLEVERLIST_SERVICE_VERIFIED ) :?>
            <p class="kleverlist-response verfied-klever-center"></p>
        <?php endif;?>
    </form>
    <!--- Dashboard Settings Form End -->

    <!-- Remove Button Code Start -->
    <form method="POST" id="kleverlist_settings">
        <table class="form-table width-900">
            <tbody>
                <?php if( $service_verified === KLEVERLIST_SERVICE_VERIFIED ) :?>
                    <tr>
                        <td class="kleverlist-position">                
                            <?php 
                                $remove_btn_attributes = array( 'id' => 'kleverlist_remove_settings' );
                                submit_button( __( 'Remove', 'kleverlist' ), 'delete', '', true, $remove_btn_attributes );
                            ?>
                            <div id="loader" class="kleverlist-loader-outer-div hidden"></div>
                        </td>
                    </tr>
                <?php endif;?>
            </tbody>
        </table>
    </form>
    <!-- Remove Button Code End -->

    <!--- Brand Select Form Start -->
    <?php if( !empty( $brands ) ): ?>
        <form method="POST" id="kleverlist_brands_settings">
            <table>
                <tbody>                
                    <tr>                    
                        <td class="klever-list-data-dropdown">
                            <label for="sendy_brands"><?php esc_html_e( 'Choose a brand', 'kleverlist' );?>:</label>
                            <select name="sendy_brands" id="sendy_brands" required>
                                <option value=""><?php esc_html_e( 'Choose a brand', 'kleverlist' );?></option>
                                <?php       
                                    $selected = '';
                                    foreach ( (array) $brands as $key => $brand ) {
                                        if( !empty( $sendy_lists ) ){
                                            $selected = in_array( $brand->id, $sendy_lists ) ? ' selected="selected" ' : '';    
                                        }
                                        echo "<option value=\"$brand->id\" $selected>$brand->name</option>";                                        
                                    }                           
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="kleverlist-position klever-list-data-generate">
                            <?php 
                                $other_attributes = array( 'id' => 'generate_lists' );
                                submit_button( __( 'Load Lists', 'kleverlist' ), 'secondary', '', true, $other_attributes );
                            ?>
                            <div id="brand_loader" class="kleverlist-loader-outer-div hidden"></div>
                        </td>
                    </tr>
                <tbody>                    
            </table>            
        </form>  
        <p class="kleverlist-response-brands klever-list-data-generate-text"></p>
    <?php endif;?>
    <!--- Brand Select Form End -->
</div>