<?php

namespace SOCLITE;


class Init {

    public $config;
    public static $instance = null;

    public $filterCollection;

    public function __construct( array $config = null ) {
        $request = array(
            'GET' => $_GET, 
            'POST' => $_POST, 
            'COOKIE' => $_COOKIE
        );

        if ( $config !== null ) {
            Config::init( $config );
        }
        
        if ( Config::isVerified() ) {

            $this->filterCollection = new \SOCLITE\Detector\FilterCollection();
            $this->filterCollection->load();

            $this->runValidation( $request );
        }   
        return $this;
    }

    public static function init( array $config ) {
        if ( self::$instance == null ){
            return self::$instance = new self($config);
        }

        return self::$instance;
    }

    /**
    *function for run input validation from request variable
    *
    */
    public function runValidation( array $request ) { 
        if ( Config::isVerified() ) {
            // new \SOCLITE\Detector\DLChecker();
            //new \SOCLITE\Detector\CSRF();
            $detector = new \SOCLITE\Detector\Validator( $request, $this->filterCollection );
            $detector->run();
            //$this->setHeader();
            return $this;
        }
        return false;
    }

    /**
    * run csrf attack only
    */

    /**
    * set header.
    */
    public function setHeader() {
      
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('X-Content-Type-Options: nosniff');
        header('X-CSRF-Protection: OWASP CSRFP 1.0.0');
        header('Content-Type: text/html; charset=iso-8859-1'); 

        $rewrite = array();

        foreach ( headers_list() as $header ) {
            if (strpos($header, 'Set-Cookie:') === false ) { 
                continue; 
            }

            if ( stripos($header, '; httponly' ) !== false ) {
                $rewrite[] = $header;
                continue;
            }
            $rewrite[] = $header . '; httponly';
        }

        if ( ! empty( $rewrite ) ) {
            header_remove('Set-Cookie');

            foreach ( $rewrite as $cookie ) {
                header($cookie, false);
            }

        }

        if ( 
            $_SERVER['SERVER_PORT'] != 443 &&
            (! isset( $_SERVER['HTTP_X_FORWARDED_PROTO']) ||
            $_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https') 
        ) {
            return;
        }

        header('Strict-Transport-Security: max-age=16070400; includeSubDomains');
    }


    /**
    * function will work for base64 encoding and decoding file and rules
    */

    public static function loadSettings( $path=null ) {

        if( $path==null ){
            $file  ="log.txt";
        }

        $loadFile = 'rules.json';

        $data = json_decode( file_get_contents( $loadFile ), true ) ;

        //var_dump($data);

        $data = base64_encode(json_encode($data));

        file_put_contents( $file, $data );

        $getfile = file_get_contents( $file);

        $getfile = json_decode( base64_decode( $getfile ) );

        // foreach ( $getfile as $key => $value ) {
        //     # code...
        //     foreach ( $value as $key2 => $value2) {
        //         # code...

        //         //get the rules
        //     }
        // }
    }
}
