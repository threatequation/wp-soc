<?php

class soc_intrusion {

    public static $instance = null;

    function __construct () {
        $enable_intrusion_logs = sl_config('enable_intrusion_logs');
        
        if ($enable_intrusion_logs) {
            add_action( 'soc_after_detection_complete', [$this, 'get_report_data'] );
        }
    }

    /**
     * get instance 
     * @return object instance of soc_intrusion
     */
    public static function instance() {
        if( self::$instance == null ){
            self::$instance = new self();
        }

        return self::$instance;
    }


    public function get_report_data () {
        $events = \SOCLITE\Detector\Report::init()->getEventsData();

        if ( !is_array($events) && empty($events) ) {
            return;
        }

        foreach ( $events as $event ) {
            $this->save_event_database($event);
        }
    }

    public function save_event_database( $event ) {
		global $wpdb;
        $dbname = $wpdb->prefix . SL_INTRUSIONS_TABLE;

		if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
			$_SERVER['REQUEST_URI'] = substr( $_SERVER['PHP_SELF'], 1 );
			if ( isset( $_SERVER['QUERY_STRING'] ) && $_SERVER['QUERY_STRING'] ) {
				$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
			}
		}

		
		$data['type']    = $event['attack_type'];
		$data['risk']   = $event['risk'];
		$data['page']    = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
		$data['user_agent']    = $event['user_agent'];
		$data['ip']      = $event['attacker_ip'];
		$data['cwe']  	= isset( $event['attack_data']['cwe'] ) ? $event['attack_data']['cwe']: 0;
        $data['created'] = date( 'Y-m-d H:i:s', time() );
        
		$db  = $wpdb->insert( $dbname, $data );
		
		if ( false === $db ) {
			return false;
		}
		
	}
}