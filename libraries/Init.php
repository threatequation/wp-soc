<?php

namespace SOCLITE;


class Init {

    public $config;

    /**
     * Instance of SOCLITE Dectection
     */
    public static $instance = null;


    /**
     * Filter Collections
     */
    public $filterCollection;

    public function __construct( ) {
        $request = array(
            'GET' => $_GET, 
            'POST' => $_POST, 
            'COOKIE' => $_COOKIE
        );
        
        $this->filterCollection = new \SOCLITE\Detector\FilterCollection();
        $this->filterCollection->load();

        $this->runValidation( $request ); 
        return $this;
    }

    public static function instance() {
        if ( self::$instance == null ){
            return self::$instance = new self();
        }

        return self::$instance;
    }

    /**
    *function for run input validation from request variable
    *
    */
    public function runValidation( array $request ) { 
        if ( is_admin() && sl_config('enable_admin')  ) {
            return;
        }

        $detector = new \SOCLITE\Detector\Validator( $request, $this->filterCollection );
        $detector->run();
        do_action('soc_after_detection_complete');
        return $this;
    }
}
