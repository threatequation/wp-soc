<?php

namespace SOCLITE\Detector;

use SOCLITE\Config;
use Illuminate\Http\Request;

class Protect
{
    public static $instance = null;

    public function __construct(){

        if( Config::isVerified() ){
            return $this;
        }
    }

    public static function init() {
        if( self::$instance == null ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function run($key, $value, array $filters = null)
    {
      /*disalow meta charecter*/
      if(sizeof(explode("\n", $value)) == 2){
        $value=explode("\n", $value)[1];
      }
     
      $value = $this->HTMLpurify($value);

      $meta=array('/[\']/', '/[\"]/', '/[%]/', '/[;]/', '/[\)]/', '/[(]/', '/[&]/', '/[+]/');
      $meta_replace = array('&apos;', '&quot;', '&percnt;', '&semi;','&rpar;', '&lpar;', '&amp;', '&plus;');
      $value = preg_replace($meta, $meta_replace, $value);

      if($filters !=null ){

        foreach ($filters as $filter) {
            $value = preg_replace_callback('/'.$filter->getRule().'/ms', array(__CLASS__, 'replace_quot'), $value);
        }

      }

       // $this->rewriteMethod($key, $value);

    }

    /**
     * function for replacing /" /'  into quotes.
     *
     * @param string
     *
     * @return replaing string
     */
    private function replace_quot($matches)
    {
        if( !Config::isVerified() ) {
            return false;
        }

        $pat    = array('/([-])/', '/([#])/', '/(union|select|or|exec(|declare|system|char)/ms');
        $re     = array('&bsol;', '&bsol;', '');
        $matches[0] = str_replace($pat, $re, $matches[0]);

        return $matches[0];
    }

    /**
     * function for purify html and protect XSS.
     *
     * @param $value string HTML content
     *
     * @return $value after purify HTML
     */
    public function HTMLpurify($value)
    {
        if( !Config::isVerified() ){
            return false;
        }
        if ( Config::getConfig('cache') != null && !is_writeable( Config::getConfig('cache'))) {
            throw new \Exception( Config::getConfig('cache') .' must be writeable');
        }
      /** @var $config \HTMLPurifier_Config */
      $config = \HTMLPurifier_Config::createDefault();
        $config->set( 'Core.Encoding', 'ISO-8859-1' ); // not using UTF-8
        $config->set( 'Attr.EnableID', true);
        $config->set( 'Cache.SerializerPath', Config::getConfig('cache') );
        $config->set( 'Output.Newline', "\n" );
        $config->getHTMLDefinition( true );
        $htmlPurifier = new \HTMLPurifier( $config) ;

        $value = preg_replace('([\x0b-\x0c])', ' ', $value);

        $value = $htmlPurifier->purify($value);

        return $value;
    }

    /**
     * replace html tag with &gl; &lt; entity.
     *
     * @param $value html content
     *
     * @return plane text with &gl; &lt; entity
     */

    /**
     *function for rewrite Request Metod.
     *
     * @param $key string key after validate
     * @param $value string value after validation
     */
    public function rewriteMethod( $key, $value )
    {
        if( !Config::isVerified() ) {
            return false;
        }
        $key = explode('.', $key);

        if ($key[0] === 'POST') {
            $_POST[$key[1]] = $value;
            $_REQUEST[$key[1]] = $value;
        } elseif ($key[0] == 'GET') {
            $_GET[$key[1]] = $value;
            $_REQUEST[$key[1]] = $value;
        } elseif ($key[0] == 'COOKIE') {
            $_COOKIE[$key[1]] = $value;
        }
    }



    /**
     *function for rewrite Request Metod.
     *
     * @param $key string key after validate
     * @param $value string value after validation
     */
    public function rewriteLaravelMethod( $key, $value )
    {
        
        $input = Request()->all();
        $input[$key] = $value;

        Request()->replace($input);

    }

    /* @End class*/
}
