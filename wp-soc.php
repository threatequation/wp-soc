<?php  
/**
*	Plugin Name: WP SOC
*	Plugin URI: https://threatequation.com/
*	Description: WP SOC generate Security Audit Log a security alert for everything that happens on your WordPress websites. Use the Audit Log Viewer included in the plugin to get all the security alerts. Identify WordPress security issues before they become a problem. 
*	Author: threatEquation
*	Version: 0.1
*	Author URI: https://threatequation.com/
*	Text Domain: wp-soc
* 	Domain Path: /languages
*   License: GPL2
*   License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/


if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @package WP SOC LITE
 * @version 0.0.1
 */

if ( ! class_exists( 'WP_SOC' ) 
    && version_compare( PHP_VERSION, '5.6', '>=' ) ) :


require __DIR__.'/vendor/autoload.php';
    
class WP_SOC {


    /**
     * An instance of this class
     *
     * @var object
     */
    private static $instance = null;


    /**
     * Constructor
     *
     * Initialise Threat Equation and run PHPIDS
     *
     * @return object
     */
    public function __construct() {
    
        if ( is_multisite() ) {
            add_action( 'network_admin_notices', [$this, 'multisite_notice'] );
            return;
        }
        $this->define_constant();
        $this->includes();
        $this->int_actions();
        $this->int_filters();
    }

    /**
     * Get the Threat Equation instance
     *
     * @return object
     */
    public static function instance() {
        if ( self::$instance  instanceof  WP_SOC ){
            return self::$instance;
        } else {
            return self::$instance = new WP_SOC();
        }
    }

    /**
     * Define methods for global constant
     *  @return null;
     */
    public function define( $var, $val) {
        if ( ! defined($var) ) {
            define( $var, $val );
        }
    }

    /**
     * Define some global constant
     * 
     * @return null;
     */
    private function define_constant() {
        $this->define( "SL_PATH", dirname( __FILE__ ) );
        $this->define( "SL_BAIS_NAME", plugin_basename(__FILE__) );
        $this->define( "SL_DIR", __DIR__ );
        $this->define( "SL_URI", plugin_dir_url( __FILE__ ) );
        $this->define( "SL_INTRUSIONS_TABLE", "wpsl_intrusions" );
        $this->define( "SL_VERSION",  "0.1" );
        $this->define( "SL_DB_VERSION", "0.1" );
        $this->define( "SL_POST_TYPE", "wpsl" );
    }


    /**
     * include required files
     */
    private function includes() {
        
        require_once SL_PATH . '/core/soc_activate.php';
        require_once SL_PATH . '/core/soc_functions.php';
        require_once SL_PATH . '/core/soc_admin.php';
        require_once SL_PATH . '/core/soc_utils.php';
        require_once SL_PATH . '/core/soc_actions.php';
        require_once SL_PATH . '/core/soc_email.php';
        require_once SL_PATH . '/core/soc_intrusion.php';
    }

    /**
     * Actions for plugins
     */
    private function int_actions() {
        
        // Register activation, deactivation and uninstall hooks,
        // run Threat Equation on init
        register_activation_hook( __FILE__, ['soc_activate', 'activate'] );
        // register_deactivation_hook( __FILE__, 'WP_SOC::deactivate' );
        // register_uninstall_hook( __FILE__, 'WP_SOC::uninstall' );

        add_action( 'plugins_loaded',  [ $this, 'init' ] );
    }

    /**
     * Filder for plugins
     */
    private function int_filters() {

    }

    public function init () {
        soc_admin::instance();
        soc_actions::instance();
        soc_intrusion::instance();
        soc_email::instance();
        \SOCLITE\Init::instance();
    }

    /**  ------------------Notice-------------------- */
    /**
     * Show admin notice for multisite install
     *
     * @return void
     */
    public function multisite_notice() {
        echo '<div class="update-nag">' . __( 'multisite install currently not supported.', 'wp-soc' ) . '</div>';
    }

}




function wp_soc () {
    return WP_SOC::instance();
}

wp_soc();

endif;