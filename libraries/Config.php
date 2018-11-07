<?php

namespace SOCLITE;


class Config {


    public static $instance = null;

    /**
    * Holds config settings.
    *
    * @var array
    */
    public static $config =  array(
    
        'filter_path'         =>  __DIR__.'/Detector/default_filter.json',
        'HTML_Purifier_Cache' =>  __DIR__.'/cache',
        'cache'               =>  __DIR__.'/cache',
        'product_id'          => '6102D26M',
        'api_token'           =>'',
        'apptime'             => '',
        'ApplicationName'     =>'ThreatequationPHPCore v0.3.0',
        'exceptions'          => array(),
        'verifed'             => false,
        'excludeHTMLtag'      => array(),
        "url_redirect_key"    => ['to', 'url','next','redirect']

    );




    public function __construct( array $config = null ) {
        /*Load previous saved config file*/
        if ( self::load() ) {
  		    self::$config = array_merge( self::load(), $config );

            if ( ( ( time()-(60*60*24) ) > self::$config['apptime'] ) || !$this->isVerified() ) {
                //self::varifyApplication();
                self::$config['apptime']=time();
            }
        } else {
  		    self::$config = array_merge(self::$config, $config);
            self::$config['apptime'] = time();
            //self::varifyApplication();
        }
        if( $config ){
  		    self::saveConfig();
        }

        return $this;
    }

    public static function init( $config ) {
        if ( self::$instance === null ){
            self::$instance = new self( $config );
        }

        return self::$instance;
    }


    /**
    * [load will load save environment and setting veriable
    * [void] ;
    */
    public static function load( $path=null ){

        if ( $path == null ){
            $path = self::$config['cache']."/config.json";
        }

        if( file_exists( $path ) ) {
            return json_decode(file_get_contents($path), true);
        }

        return false;
    }



    /**
    * get configuration Environment variable by key
    *
    * @key variable key
    * @return get configuration key value
    */
    public static function getConfig ( $key=null ) {

        if( $key==null ){
            return self::$config;
        }else if( array_key_exists ( $key, self::$config ) ) {
            return self::$config[$key];
        } else {
            return false;
        }
    }


    /**
    * set configuration Environment variable
    *
    * @key variable key
    * @$value set key value
    * @return configuration value
    */

    public static function setConfig( $key, $value ) {
        self::$config[$key] = $value;
        self::saveConfig(); 

        return self::$config;
    }



        /**
       * [saveConfig will save environment and setting veriable
       * [void] ;
       */

    public static function saveConfig( $path=null ) {

        if( $path == null ){
            $path = self::$config['cache']."/config.json";
        }

        return file_put_contents( $path, json_encode( self::$config ) );
    }

    public static function isVerified () {
        return true;//self::$config['verifed'];
    }

    /**
    * [varifyApplication will verify our application with application secrate key and user id
    * @return [boolean] true is application is varified;
    */
    public static function varifyApplication () {
        $data = array(
            'product_id'    => self::$config['product_id'], 
            'api_token'     => self::$config['api_token']
        );

        list($body, $header)= \SOCLITE\Detector\Send::curl('/product_verify/', $data);
        
        if ( $body && (trim($body) == 'trial' || trim($body) == 'paid' ) ) {
            return self::$config['verifed'] = true;
        } else{
            return  self::$config['verifed'] = false;
        }
    }
}

?>