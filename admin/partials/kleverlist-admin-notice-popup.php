<?php 
    if ( KLEVERLIST_PLUGIN_PLAN === 'kle-free' ) :
    ?>
    <div class="kleverlist-premium-popup-wrapper overlay-kleverlist" id="kleverlist-notice-popup" style="display:none;">
        <div class="kleverlist-premium-popup-inner-wrapper">
            <div class="kleverlist-premium-popup-image-popup">
                <img src="<?php echo KLEVERLIST_PLUGIN_ADMIN_DIR_URL?>/images/warning-sign.png" alt="">
            </div>
            <div class="kleverlist-premium-popup-content">
                <h2><?php esc_html_e( 'Unlock the Power of KleverList Pro!', 'kleverlist' );?></h2>
                <p>
                	<?php                    
                        printf( 
                        __('Ready to supercharge your email marketing strategy? Upgrade to %s and gain access to a world of advanced features designed to take your WooCommerce store to new heights, boost customer engagement, and drive higher conversions. Say goodbye to limitations and unlock the full potential of KleverList.', 'kleverlist'), 
    				    '<a href="https://kleverlist.com/pricing/" target="_blank">KleverList Pro</a>'
    					); 
                    ?>     
                </p>
                <div class="kleverlist-premium-btn">
                    <a href="javascript:void(0)"><?php esc_html_e( 'Close', 'kleverlist' );?></a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>