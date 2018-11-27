<?php

class soc_email {

    public static $instance = null;

    function __construct () {
        $email_notifications = sl_config('email_notifications');
        
        if ($email_notifications) {
            add_action( 'soc_after_detection_complete', [$this, 'get_report_email'] );
        }
    }

    /**
     * get instance 
     * @return object instance of soc_email
     */
    public static function instance() {
        if( self::$instance == null ){
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
	* Sends an email
	* @return boolean
	*/

    public function get_report_email() {
        $events = \SOCLITE\Detector\Report::init()->getEventsData();
        $emailStrng = 'Some intrusion inject in your site' . bloginfo();
        $subject = 'Some intrusion inject in your site' . bloginfo();

        $address = sl_config('email');

        foreach ( $events as $event ) {
            $emailStrng .= $this->prepare_data($event);
        }


        return wp_mail( $address, $subject, $emailStrng );

    }
	/**
	* Prepares data
	*
	* Converts given data into a format that can be read in an email.
	* You might edit this method to your requirements.
	*
	* @param mixed $data the report data
    * @return string
	*/
	protected function prepare_data( $event ) {

		$format  = __( "\n\n", 'wp_soc' );
		$format .= __( "Date: %s \n", 'wp_soc' );
		$format .= __( "attack Type: %s \n", 'wp_soc' );
		$format .= __( "Risk: %d \n", 'wp_soc' );
        $format .= __( "IP: %s \n", 'wp_soc' );
		$format .= __( "Origin: %s \n", 'wp_soc' );

        return sprintf( $format, 
            $event['timestamp'],
            $event['attack_type'],
            $event['risk'],
            $event['attacker_ip'],
            $event['user_agent']
		);
	}


}