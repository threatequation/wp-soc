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
        if ( (int) sl_config('telog')) {
            add_action( 'shutdown', [$this, 'send_log_to_threatEquation'], 10, 1 ); 
        }
       
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
}