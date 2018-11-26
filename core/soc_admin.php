<?php  ///if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Threat Equation admin class
 */
class soc_admin {
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
		
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		// add_filter( 'screen_settings', array( $this, 'screen_settings' ), 10, 2 );
		// add_filter( 'set-screen-option', array( $this, 'set_screen_option' ), 10, 3 );
		add_filter( 'plugin_action_links_'. SL_BAIS_NAME, array( $this, 'plugin_action_links' ) );
    }
    
     /**
     * Get the Threat Equation instance
     *
     * @return object
     */
    public static function instance() {
        if ( self::$instance  instanceof  soc_admin ){
            return self::$instance;
        } else {
            return self::$instance = new self();
        }
	}
	
	/**
	 * Add admin menu items
	 *
	 * @return void
	 */
	public function admin_menu() {
		// $intrusion_count = (int) ThreatEquation::instance()->get_option( 'new_intrusions_count' );
		// $intrusions_menu_title = sprintf( __( 'Intrusions %s', 'wp-soc-lite' ), "<span class='update-plugins count-$intrusion_count' title='$intrusion_count'><span class='update-count'>" . number_format_i18n( $intrusion_count ) . '</span></span>' );
		
		
		add_menu_page( __( 'Soc Lite', 'wp-soc-lite' ), __( 'Soc Lite', 'wp-soc-lite' ), 'activate_plugins' , 'soc_lite', array( $this, 'intrusions' ), 'dashicons-shield' );
		add_submenu_page(
	        'soc_lite',
	        'Options',
	        'Options',
	        'activate_plugins',
	        'soc_options',
	        array( $this, 'options' )
	    );
	}
	

	/**
	 * Admin init
	 *
	 * @return void
	 */
	public function admin_init() {
		
		// Are we on Threat Equation's intrusions page?
		if ( isset($_GET['page']) && $_GET['page'] == 'soc_options') {
			
			$options = $this->options_validate( $_POST );
			
		}

		// // Add admin CSS
		// wp_enqueue_style( 'soc_styles', ThreatEquation::plugin_url() . 'css/mscr.css', array(), ThreatEquation::VERSION );

	}

	// /**
	//  * Perform an action based on the request
	//  *
	//  * @return void
	//  */
	// private function do_action() {
	// 	global $wpdb;
	// 	$sendback = remove_query_arg( array( 'intrusions' ), wp_get_referer() );

	// 	// Handle bulk actions
	// 	if ( isset( $_GET['doaction'] ) || isset( $_GET['doaction2'] ) ) {
	// 		check_admin_referer( 'mscr_action_intrusions_bulk' );

	// 		if ( ( $_GET['action'] != '' || $_GET['action2'] != '' ) && ( isset( $_GET['page'] ) && isset( $_GET['intrusions'] ) ) ) {
	// 			$intrusion_ids = $_GET['intrusions'];
	// 			$doaction = ( $_GET['action'] != '' ) ? $_GET['action'] : $_GET['action2'];
	// 		} else {
	// 			wp_redirect( admin_url( 'index.php?page=mscr_intrusions' ) );
	// 			exit;
	// 		}

	// 		switch ( $doaction ) {
	// 			case 'bulk_delete':
	// 				$deleted = 0;
	// 				foreach ( (array) $intrusion_ids as $intrusion_id ) {
	// 					if ( ! current_user_can( 'activate_plugins' ) )
	// 						wp_die( __( 'You are not allowed to delete this item.', 'wp-soc-lite' ) );

	// 					$sql    = $wpdb->prepare( 'DELETE FROM ' . $wpdb->tewp_intrusions . ' WHERE id = %d', $intrusion_id );
	// 					$result = $wpdb->query( $sql );

	// 					if ( ! $result ) {
	// 						wp_die( __( 'Error in deleting...', 'wp-soc-lite' ) );
	// 					}
	// 					$deleted++;
	// 				}
	// 				$sendback = add_query_arg( 'deleted', $deleted, $sendback );
	// 				break;

	// 			case 'bulk_exclude':
	// 				$excluded = 0;
	// 				foreach ( (array) $intrusion_ids as $intrusion_id ) {
	// 					if ( ! current_user_can( 'activate_plugins' ) ) {
	// 						wp_die( __( 'You are not allowed to exclude this item.', 'wp-soc-lite' ) );
	// 					}

	// 					// Get the intrusion field to exclude
	// 					$sql    = $wpdb->prepare( "SELECT name FROM {$wpdb->tewp_intrusions} WHERE id = %d", $intrusion_id );
	// 					$result = $wpdb->get_row( $sql );

	// 					if ( ! $result ) {
	// 						wp_die( __( 'Error in excluding...', 'wp-soc-lite' ) );
	// 					}

	// 					$mscr = ThreatEquation::instance();
	// 					$exceptions = $mscr->get_option( 'exception_fields' );

	// 					// Only add the field once
	// 					if ( ! in_array( $result->name, $exceptions ) ) {
	// 						$exceptions[] = $result->name;
	// 					}

	// 					$mscr->set_option( 'exception_fields', $exceptions );
	// 					$excluded++;
	// 				}
	// 				$sendback = add_query_arg( 'excluded', $excluded, $sendback );
	// 				break;
	// 		}

	// 		if ( isset( $_GET['action'] ) ) {
	// 			$sendback = remove_query_arg( array( 'action', 'action2', 'intrusions' ), $sendback );
	// 		}

	// 		wp_redirect( $sendback );
	// 		exit;
	// 	} else if ( ! empty( $_GET['_wp_http_referer'] ) ) {
	// 		wp_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) );
	// 		exit;
	// 	}

	// 	// Handle other actions
	// 	$action = MSCR_Utils::get( 'action' );
	// 	$id     = (int) MSCR_Utils::get( 'intrusion' );

	// 	if ( ! $action )
	// 		return;

	// 	switch ( $action ) {
	// 		case 'exclude':
	// 			check_admin_referer( 'mscr_action_exclude_intrusion' );
	// 			if ( ! current_user_can( 'activate_plugins' ) )
	// 				wp_die( __( 'You are not allowed to exclude this item.', 'wp-soc-lite' ) );

	// 			// Get the intrusion field to exclude
	// 			$sql    = $wpdb->prepare( "SELECT name FROM {$wpdb->tewp_intrusions} WHERE id = %d", $id );
	// 			$result = $wpdb->get_row( $sql );

	// 			if ( ! $result ) {
	// 				wp_die( __( 'Error in excluding...', 'wp-soc-lite' ) );
	// 			}

	// 			$mscr = ThreatEquation::instance();
	// 			$exceptions = $mscr->get_option( 'exception_fields' );

	// 			// Only add the field once
	// 			if ( ! in_array( $result->name, $exceptions ) ) {
	// 				$exceptions[] = $result->name;
	// 			}

	// 			$mscr->set_option( 'exception_fields', $exceptions );
	// 			$sendback = add_query_arg( 'excluded', $id, $sendback );
	// 			break;

	// 		case 'delete':
	// 			check_admin_referer( 'mscr_action_delete_intrusion' );
	// 			if ( ! current_user_can( 'activate_plugins' ) )
	// 				wp_die( __( 'You are not allowed to delete this item.', 'wp-soc-lite' ) );

	// 			$sql    = $wpdb->prepare( 'DELETE FROM ' . $wpdb->tewp_intrusions . ' WHERE id = %d', $id );
	// 			$result = $wpdb->query( $sql );

	// 			if ( ! $result ) {
	// 				wp_die( __( 'Error in deleting...', 'wp-soc-lite' ) );
	// 			}

	// 			$sendback = add_query_arg( 'deleted', 1, $sendback );
	// 			break;
	// 	}

	// 	wp_redirect( $sendback );
	// 	exit;
	// }

	/**
	 * Add custom screen options & help to a plugin page
	 *
	 * @param string
	 * @param object
	 * @return string
	 */
	// public function screen_settings( $action, $screen_object ) {
	// 	if ( $screen_object->id == 'dashboard_page_mscr_intrusions' ) {
	// 		// Add screen options to the intrusions list page
	// 		$per_page = MSCR_Utils::mscr_intrusions_per_page();
	// 		$data['per_page'] = $per_page;
	// 		$action = MSCR_Utils::view( 'admin_intrusions_screen_options', $data, true );

	// 		// Are we on WordPress 3.1 or higher?
	// 		if ( function_exists( 'get_current_screen' ) ) {
	// 			return $action;
	// 		}

	// 		// Legacy support for contextual help on the intrusions page for WordPress 3.0
	// 		add_contextual_help( $screen_object->id, $this->get_contextual_help() );
	// 	}

	// 	return $action;
	// }

	/**
	 * Update the current user's screen options
	 *
	 * @return mixed
	 */
	// public function set_screen_option( $flag, $option, $value ) {
	// 	switch ( $option ) {
	// 		case 'mscr_intrusions_per_page':
	// 			$value = absint( $value );
	// 			if ( $value < 1 ) {
	// 				return false;
	// 			}

	// 			return $value;
	// 	}

	// 	return $flag;
	// }

	/**
	 * Add link to settings on the plugins page
	 *
	 * @param array
	 * @return array
	 */
	public function plugin_action_links( $actions ) {
		$actions['settings'] = '<a href="'.admin_url( 'admin.php?page=soc_options' ).'">Settings</a>';
		$actions['threatequation'] = '<a href="https://www.threatequation.com/" target="_blank" style="color:red;">ThreatEquation</a>';
		return $actions;
	}
	

	/**
	 * Display PHPIDS Intrusions
	 *
	 * @return void
	 */
	public function intrusions() {
		global $wpdb;

		// Current page number, items per page
		$per_page = soc_utils::intrusions_per_page();
		$pagenum  = isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;
		

		// Offset, limit
		$limit  = $per_page;
		$offset = ( $pagenum * $limit ) - $limit;
		$offset = ( $offset < 0 ) ? 0 : $offset;

		$table = $wpdb->prefix . SL_INTRUSIONS_TABLE;

		// Get results
		$search = isset( $_GET['search'] ) ? stripslashes( $_GET['search'] ) : '';
		$search_title = '';
		if ( $search ) {
			$search_title = sprintf( '<span class="subtitle">' . __( 'Search results for &#8220;%s&#8221;', 'wp-soc-lite' ) . '</span>', esc_html( $search ) );
			$token = '%'.$search.'%';
			$sql = $wpdb->prepare( 'SELECT SQL_CALC_FOUND_ROWS * FROM ' . $table . ' WHERE (name LIKE %s OR page LIKE %s OR tags LIKE %s OR ip LIKE %s OR impact LIKE %s) ORDER BY created DESC LIMIT %d, %d', $token, $token, $token, $token, $token, $offset, $limit );
		} else {
			$sql = $wpdb->prepare( 'SELECT SQL_CALC_FOUND_ROWS * FROM ' . $table . ' ORDER BY created DESC LIMIT %d, %d', $offset, $limit );
		}

		$intrusions = $wpdb->get_results( $sql );
		$total_intrusions = $wpdb->get_var( 'SELECT FOUND_ROWS();' );

		// Construct pagination links
		$num_pages  = ceil( $total_intrusions / $per_page );
		$pagination = soc_utils::pagination( $pagenum, $num_pages, $per_page, $total_intrusions );

		// Columns
		$columns = array(
			'type' => __( 'Type', 'wp-soc-lite' ),
			'risk' => __( 'Rist', 'wp-soc-lite' ),
			'page' => __( 'Page', 'wp-soc-lite' ),
			'user_agent' => __( 'User Agent', 'wp-soc-lite' ),
			'ip' => __( 'IP', 'wp-soc-lite' ),
			'cwe' => __( 'cwe', 'wp-soc-lite' ),
			'date' => __( 'Date', 'wp-soc-lite' )
		);
		$columns = apply_filters( 'sl_admin_intrusions_columns', $columns );

		// Was something deleted?
		$deleted = isset( $_GET['deleted'] ) ? (int) $_GET['deleted'] : 0;

		// Was something excluded?
		$excluded = isset( $_GET['excluded'] ) ? (int) $_GET['excluded'] : 0;

		$data['message'] = false;
		$data['intrusions'] = $intrusions;
		$data['style'] = '';
		$data['columns'] = $columns;
		$data['page'] = $_GET['page'];
		$data['pagination'] = $pagination;
		$data['intrusions_search'] = $search;
		$data['search_title'] = $search_title;

		$data['time_offset'] = get_option( 'gmt_offset' ) * 3600;
		$data['date_format'] = get_option( 'date_format' );
		$data['time_format'] = get_option( 'time_format' );

		if ( $deleted )
			$data['message'] = sprintf( _n( 'Item permanently deleted.', '%s items permanently deleted.', $deleted, 'wp-soc-lite' ), number_format_i18n( $deleted ) );

		if ( $excluded )
			$data['message'] = sprintf( _n( 'Item added to the exceptions list.', '%s items added to the exceptions list.', $excluded, 'wp-soc-lite' ), number_format_i18n( $excluded ) );
		soc_utils::view( 'admin_intrusions', $data );
	}



	/**
	 * Display options page
	 *
	 * @return void
	 */
	public function options() {
		
		$options = sl_config();

		wp_enqueue_script('soc-options', SL_URI. '/assest/js/options.js', ['jquery'], SL_VERSION, true );


		// Prep exception data
		$options['exception_fields'] = implode( "\r\n", $options['exception_fields'] );
		$options['html_fields'] = implode( "\r\n", $options['html_fields'] );
		$options['json_fields'] = implode( "\r\n", $options['json_fields'] );

		// Apply textarea escaping, backwards compat for WordPress 3.0
		if ( function_exists( 'esc_textarea' ) ) {
			$options['exception_fields'] = esc_textarea( $options['exception_fields'] );
			$options['html_fields'] = esc_textarea( $options['html_fields'] );
			$options['json_fields'] = esc_textarea( $options['json_fields'] );
		} else {
			$options['exception_fields'] = esc_html( $options['exception_fields'] );
			$options['html_fields'] = esc_html( $options['html_fields'] );
			$options['json_fields'] = esc_html( $options['json_fields'] );
		}

		$options['telog'] = isset($options['telog'])? (int) $options['telog'] : 0;

		soc_utils::view( 'admin_options', $options );
	}


		/**
	 * Validate options
	 *
	 * @return array
	 */
	public function options_validate( $input = array() ) {
		
		if ( ! isset( $input['submit'] ) ) {
			return;
		}



		if ( ! wp_verify_nonce( $input['_wpnonce'], 'soc_options-options' ) ) {
     		die( 'Security check' );
     	}

		$input = $input['sl_options'];

		$options = sl_config();

		foreach ( array( 'email', 'email_threshold', 'exception_fields', 'html_fields', 'json_fields' ) as $key ) {
			if ( ! isset( $input[$key] ) ) {
				continue;
			}

			$options[$key] = $input[$key];

			switch ( $key ) {
				case 'email':
					if ( !is_email( $options[$key] ) ) {
						$options[$key] = get_option( 'admin_email' );
					}
					break;

				case 'email_threshold':
					$options[$key] = absint( $options[$key] );
					break;

				case 'exception_fields':
				case 'html_fields':
				case 'json_fields':
					if ( ! is_string( $options[$key] ) ) {
						continue;
					}

					$options[$key] = str_replace( array( "\r\n", "\n", "\r" ), "\n", $options[$key] );
					$options[$key] = explode( "\n", $options[$key] );

					// Exception fields array must not contain an empty string
					// otherwise all fields will be excepted
					foreach ( $options[$key] as $k => $v ) {
						if ( strlen( $options[$key][$k] ) == 0 ) {
							unset( $options[$key][$k] );
						}
					}
			}
		}
		$options['telog'] = isset( $input['telog'] )? $input['telog']: 0;
		$options['product_id'] = isset( $input['product_id'] )? $input['product_id']: '';
		$options['api_token'] = isset( $input['api_token'] )? $input['api_token']: '';
		// Warnings
		$options['warning_wp_admin']  = isset( $input['warning_wp_admin'] ) ? 1 : 0;
		$options['warning_threshold'] = isset( $input['warning_threshold'] ) ? absint( $input['warning_threshold'] ): 20;

		// Checkboxes
		$options['email_notifications']      = isset( $input['email_notifications'] ) ? 1 : 0;
		$options['enable_admin']             = isset( $input['enable_admin'] ) ? 1 : 0;
		$options['enable_intrusion_logs']    = isset( $input['enable_intrusion_logs'] ) ? 1 : 0;


		// Banning
		$options['ban_enabled'] = isset( $input['ban_enabled'] ) ? 1 : 0;
		$options['ban_threshold'] = isset( $input['ban_threshold'] )? absint( $input['ban_threshold'] ): 20;
		$options['attack_repeat_limit'] = isset($input['attack_repeat_limit'])? absint( $input['attack_repeat_limit'] ) : 20;
		$options['ban_time'] = isset( $input['ban_time'] ) ? absint( $input['ban_time'] ): 10;

		update_option( 'soc_lite_options', $options );
		
		return $options;
	}
}
