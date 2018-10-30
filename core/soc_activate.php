<?php

class soc_activate {
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
       
    }
    
     /**
     * Get the Threat Equation instance
     *
     * @return object
     */
    public static function instance() {
        if ( self::$instance  instanceof  soc_activate ){
            return self::$instance;
        } else {
            return self::$instance = new soc_activate();
        }
    }

    	/**
	 * Setup options, database table on activation
	 *
	 * @return void
	 */
	public static function activate() {
		if ( is_multisite() ) {
			return;
		}

		global $wpdb;
		$wpdb->hide_errors();
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$collate = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
        }

		$table = $wpdb->prefix . SL_INTRUSIONS_TABLE;

		$query = "CREATE TABLE {$table} (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`type` varchar(128) NOT NULL,
			`risk` varchar(128) DEFAULT NULL,
			`page` varchar(255) DEFAULT NULL,
			`user_agent` varchar(250) DEFAULT NULL,
			`ip` varchar(16) NOT NULL DEFAULT '0',
			`cwe` varchar(255) DEFAULT NULL,
			`created` datetime NOT NULL,
			PRIMARY KEY (`id`)
		  )" . $collate ;

		// Attack attempts database table
		dbDelta( $query );
	}

    /**
	 * Clean up database on uninstall
	 *
	 * @return void
	 */
	public function uninstall() {
        global $wpdb;
        
		// Remove Threat Equation options
		delete_option( 'soc_lite_options' );

		// Remove intrusions table
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . INTRUSIONS_TABLE . '' );
    }
    
    	/**
	 * Clean up on deactivation
	 *
	 * @return void
	 */
	public static function deactivate() {
		
	}
}