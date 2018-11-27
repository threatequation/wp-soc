<?php

class soc_actions {


    public static $instance = null;


    function __construct () {
        $this->init_action();
        $this->init_filter();
    }


    /**
     * get instance 
     * @return object instance of Actions
     */
    public static function instance() {
        if( self::$instance == null ){
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * init Actions
     */

    public function init_action() {
        add_action( 'shutdown', [$this, 'send_log_to_threatEquation'], 10, 1 ); 
        add_action( 'soc_after_detection_complete', [$this, 'logout_and_worning']);
        add_action('login_header', [$this, 'soc_login_header_wornning']);
       
    }

    /**
     * init Filters
     */
    public function init_filter() {
        // $telog = sl_config('telog');
        // if ( $telog ) {
        //     add_filter( 'soc_event_log_data', [$this, 'send_log_to_threatEquation'] );
        // }
    }


    public function send_log_to_threatEquation( $array ) {
        if ( !(int) sl_config('telog')) {
            return;
        }
        $events = \SOCLITE\Detector\Report::init()->getEventsData();

        $url = 'https://www.threatequation.com/api/v1/attack_log/';
        $product_id = sl_config('product_id');
        $api_token = sl_config('api_token');
        $telog = sl_config('telog');

        if ( !intval($telog) && empty( $product_id ) && empty( $api_token )) {
            return;
        }

        

        foreach ( (array) $events as $event ) {

            $event['product_id'] = $product_id;
            $event['api_token'] = $api_token;

            $response = wp_remote_post( $url , [
                    'method' => 'POST',
                    'body' => $event,
                ]
            );

        }
    }

    public function logout_and_worning() {
        
        if (!(int) sl_config( 'warning_wp_admin' ) ) {
            return;
        }

        $isHighRisk = \SOCLITE\Detector\Report::init()->hasRiskHigh();

        if ( $isHighRisk ) {
            wp_logout();
			wp_safe_redirect( wp_login_url().'?&loggedout=true&wp_soc_error=true' );
			exit;
        }
    }

    public function soc_login_header_wornning () {
        if ( isset( $_GET['wp_soc_error'] ) &&  $_GET['wp_soc_error'] != 'true' ) {
            return;
        }

        $class = 'notice notice-error';
	    $message = __( 'We found potential security vulnerabilities in your attempt.', 'wp_soc' );

	    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
    }
}