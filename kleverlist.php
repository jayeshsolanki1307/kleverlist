<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://kleverlist.com/
 * @since             1.0.0
 * @package           Kleverlist
 *
 * @wordpress-plugin
 * Plugin Name:       KleverList
 * Plugin URI:        https://kleverlist.com/
 * Description:       A powerful and user-friendly WordPress plugin to integrate your WooCommerce store with Sendy, and unlock the true potential of customer segmentation.
 * Version:           1.0.0
 * Author:            KleverPlugins
 * Author URI:        https://kleverplugins.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       kleverlist
 * Domain Path:       /languages
 *
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
$plugin_class = 'kleverlist-free-plan';
$plugin_plan = 'kle-free';
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    
    if ( !function_exists( 'kle_fs' ) ) {
        // Create a helper function for easy SDK access.
        function kle_fs()
        {
            global  $kle_fs ;
            
            if ( !isset( $kle_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $kle_fs = fs_dynamic_init( array(
                    'id'              => '12489',
                    'slug'            => 'kleverlist',
                    'type'            => 'plugin',
                    'public_key'      => 'pk_3134d843e3c025c47ba22a04587bd',
                    'is_premium'      => false,
                    'premium_suffix'  => 'Pro',
                    'has_addons'      => false,
                    'has_paid_plans'  => true,
                    'has_affiliation' => 'selected',
                    'menu'            => array(
                    'slug'    => 'kleverlist',
                    'support' => false,
                ),
                    'is_live'         => true,
                ) );
            }
            
            return $kle_fs;
        }
        
        // Init Freemius.
        kle_fs();
        // Signal that SDK was initiated.
        do_action( 'kle_fs_loaded' );
    }

}
define( 'KLEVERLIST_VERSION', '1.0.0' );
define( 'KLEVERLIST_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'KLEVERLIST_ROOT_DIR_ADMIN', dirname( __FILE__ ) . '/admin' );
define( 'KLEVERLIST_PLUGIN_PUBLIC_DIR_URL', plugin_dir_url( __FILE__ ) . '/public' );
define( 'KLEVERLIST_PLUGIN_ADMIN_DIR_URL', plugin_dir_url( __FILE__ ) . 'admin/' );
define( 'KLEVERLIST_SERVICE_VERIFIED', 'verified' );
define( 'KLEVERLIST_PLUGIN_CLASS', $plugin_class );
define( 'KLEVERLIST_PLUGIN_PLAN', $plugin_plan );
/**
 * Main plugin file.
 */
const  KLEVERLIST_PLUGIN_FILE = __FILE__ ;
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-kleverlist-activator.php
 */
function activate_kleverlist()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-kleverlist-activator.php';
    Kleverlist_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-kleverlist-deactivator.php
 */
function deactivate_kleverlist()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-kleverlist-deactivator.php';
    Kleverlist_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_kleverlist' );
register_deactivation_hook( __FILE__, 'deactivate_kleverlist' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-kleverlist.php';
/**
 * The core plugin file that us used to delete plugin data when unistall plugin
*/
require plugin_dir_path( __FILE__ ) . 'includes/kleverlist-cleanup.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_kleverlist()
{
    $plugin = new Kleverlist();
    $plugin->run();
}

run_kleverlist();