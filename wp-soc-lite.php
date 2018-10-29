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
    
    class wp_soc_lite {
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
        public function define( String $var, $val) {
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
            $this->define( "TE_PATH", dirname( __FILE__ ) );
            $this->define( "TE_DIR", __DIR__ );
            $this->define( "INTRUSIONS_TABLE", "wpsl_intrusions");
            $this->define( "VERSION",  "1.0.1");
            $this->define( "DB_VERSION", "0.1");
            $this->define( "POST_TYPE", "wpsl");
        }


        /**
         * include required files
         */
        private function includes() {
            require_once TE_PATH . '/libraries/includes/functions.php';
            require_once TE_PATH . '/libraries/includes/hooks.php';
            require_once TE_PATH . '/libraries/includes/Utils.php';
        }

        /**
         * Actions for plugins
         */
        private function int_actions() {

        }

        /**
         * Filder for plugins
         */
        private function int_filters() {

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

endif;