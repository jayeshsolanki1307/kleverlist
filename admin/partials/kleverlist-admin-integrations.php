<?php
    $service_type = '';
    $service_api_key = '';
    $service_domain_name = '';
    $service_verified = '';
    $integrations_message = '';
    
    $kleverlist_service_settings = get_option( 'kleverlist_service_settings', '' );
    if( !empty( $kleverlist_service_settings ) ){
        $service_verified = $kleverlist_service_settings['service_verified'];
        $service_type = $kleverlist_service_settings['service_type'];
        $service_api_key = $kleverlist_service_settings['service_api_key'];        
        $service_domain_name = $kleverlist_service_settings['service_domain_name'];        
    }    

    $sendy_lists = get_option( 'kleverlist_sendy_lists', '' );   
    $brands = get_option( 'kleverlist_sendy_brands','' );   
    
    if( !empty( $service_api_key ) ){
        $service_api_key = Kleverlist_Admin::hide_input_character( $service_api_key, 5 );
    }

    if( $service_verified === KLEVERLIST_SERVICE_VERIFIED && empty( $sendy_lists ) ){
        $integrations_message = __( 'Almost Done! Now Choose a Brand and Load the Lists', 'kleverlist' );
    }elseif( !empty( $sendy_lists ) ){
        $integrations_message = __( 'Integration Successful', 'kleverlist' );
    }
?>

<div class="wrap kleverlist-settings-page">
    <div class="kleverlist-main-div-integrate-icon">
        <div class="kleverlist-icon-list">
            <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/integration-icon.png" alt="">
        </div>
        <h1><?php esc_html_e( 'Integrations', 'kleverlist' );?></h1>
    </div>
    <p class="kleverlist-page-main-description"><?php esc_html_e( 'Please choose your integration and configure it by entering the appropriate information. Once this initial step is done, you can proceed in the Mapping page.', 'kleverlist' );?></p>
    <!--- Dashboard Settings Form Start -->    
    <form method="POST" id="kleverlist_settings">
        <table class="form-table width-900 klever-list-data-outer-div">
            <tbody>
                <th class="klever-list-data-heading">
                    <em><?php esc_html_e( 'Choose your integration', 'kleverlist' );?></em>
                </th>
                <td class="klever-list-btn-padd">
                    <div class="kleverlist-integrations sendy klever-width-btn">
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
                            <p class="klever-list-data-paragraph"><?php esc_html_e( 'Please make sure that your website is using HTTPS. If not, the integration will not work.', 'kleverlist' );?></p>       
                    </td>             
                </tr>
                <?php if( $service_verified != KLEVERLIST_SERVICE_VERIFIED ) :?>
                <tr>
                    <td class="kleverlist-position klever-list-data-mainchanges klever-list-data-one">
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

    <?php if( !empty( $integrations_message ) ) :?>
    <div class="klever-list-data-heading-load-list">
        <h1><?php echo $integrations_message;?></h1>
    </div>
    <?php endif;?>

    <!-- Remove Button Code Start -->
    <div class="klever-list-data-brandselect-main">
        <form method="POST" id="kleverlist_settings">
            <table class="form-table width-900 klever-list-data-removebtn">
                <tbody>
                    <?php if( $service_verified === KLEVERLIST_SERVICE_VERIFIED ) :?>
                        <tr>
                            <td class="kleverlist-position">                
                                <?php 
                                    $remove_btn_attributes = array( 'id' => 'kleverlist_remove_settings' );
                                    submit_button( __( 'Remove Integration', 'kleverlist' ), 'delete', '', true, $remove_btn_attributes );
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
                                    submit_button( __( 'Load Lists from Brand', 'kleverlist' ), 'secondary', '', true, $other_attributes );
                                ?>
                                <p class="klever-list-data-paragraph"><?php esc_html_e( 'Do not forget to click this button every time you create or modify a new list.', 'kleverlist' );?></p>  
                                <div id="brand_loader" class="kleverlist-loader-outer-div hidden"></div>
                            </td>
                        </tr>
                    <tbody>                    
                </table>            
            </form>  
            <p class="kleverlist-response-brands klever-list-data-generate-text"></p>
        <?php endif;?>
    </div>
    <!--- Brand Select Form End -->
</div>