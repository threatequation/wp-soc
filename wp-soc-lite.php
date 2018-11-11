<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @package ThreatEquation
 * @version 0.0.1
 */
/**
*	Plugin Name: wp soc lite
*	Plugin URI: https://threatequation.com/
*	Description: 
*	Author: threatEquation
*	Version: 1.0.0.
*	Author URI: https://threatequation.com/
*	Text Domain: wp-soc-lite
* 	Domain Path: /languages/
*/

/**
 * Threat Equation
 */

if ( ! class_exists( 'wp_soc_lite' ) 
    && version_compare( PHP_VERSION, '5.6', '>=' ) ) :


require __DIR__.'/vendor/autoload.php';
    
class wp_soc_lite {


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
        if ( self::$instance  instanceof  wp_soc_lite ){
            return self::$instance;
        } else {
            return self::$instance = new wp_soc_lite();
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
        $this->define( "SL_INTRUSIONS_TABLE", "wpsl_intrusions" );
        $this->define( "SL_VERSION",  "1.0.1" );
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

    }

    /**
     * Actions for plugins
     */
    private function int_actions() {
        
        // Register activation, deactivation and uninstall hooks,
        // run Threat Equation on init
        register_activation_hook( __FILE__, ['soc_activate', 'activate'] );
        // register_deactivation_hook( __FILE__, 'wp_soc_lite::deactivate' );
        // register_uninstall_hook( __FILE__, 'wp_soc_lite::uninstall' );

        add_action( 'plugins_loaded',  [ $this, 'init' ] );
    }

    /**
     * Filder for plugins
     */
    private function int_filters() {

    }

    public function init () {
        soc_admin::instance();
        \SOCLITE\Init::instance();
    }

    /**  ------------------Notice-------------------- */
    /**
     * Show admin notice for multisite install
     *
     * @return void
     */
    public function multisite_notice() {
        echo '<div class="update-nag">' . __( 'multisite install currently not supported.', 'wp-soc-lite' ) . '</div>';
    }

}




function wp_soc_lite () {
    return wp_soc_lite::instance();
}

wp_soc_lite();

endif;