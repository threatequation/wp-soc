<?php

namespace SOCLITE\Detector;

use \SOCLITE\Init;
use \SOCLITE\Detector\FilterCollection;
use \SOCLITE\Config;

class Validator
{
    private $request = null;
    private $filterCollection = null;
    public $centrifuge = array();
    public $protect;

    public function __construct (array $request, FilterCollection $filterCollection ) {

        $this->filterCollection = $filterCollection;
        $this->request = $request;
        if( !Config::isVerified() ){
            return false;
        }

        return $this; 
    }

    public function run(){
        $filter= array();
        foreach ($this->request as $key => $value) {
            $filter[]= $this->iterate($key, $value);
        }
        return $filter;
    }

    private function iterate($key, $value)
    {
        $filter = null;
        if (is_array($value)) {
            foreach ($value as $subKey => $subValue) {
                $this->iterate("$key.$subKey", $subValue);
            }
        } elseif (is_string($value)) {
            
            $value = $this->converter($value);
            $filter = $this->detect($key, $value);
            if ($filter) {
                $this->addEvent($key, $value, $filter);
            }

            $value = $this->urfValidation($key, $value);
            $value = $this->IDOR($key, $value);
            //Protect::init()->run($key, $value, $filter);
        }
        return $filter;
    }

     /**
      * Checks whether given value matches any of the supplied filter patterns.
      *
      * @param mixed $key   the key of the value to scan
      * @param mixed $value the value to scan
      *
      * @return Filter[] array of filter(s) that matched the value
      */
    private function detect($key, $value)
    {
        // define the pre-filter
        $preFilter = '([^\w\s/@!?\.]+|(?:\./)|(?:@@\w+)|(?:\+ADw)|(?:union\s+select))i';

        // to increase performance, only start detection if value isn't alphanumeric
        if (!$value || !preg_match($preFilter, $value)) {
            return array();
        }

         // check if this field is part of the exceptions
        if(sizeof( Config::getConfig('exceptions')) > 0){
            foreach ( Config::getConfig('exceptions') as $exception) {
               $matches = array();
               if (($exception === $key) || preg_match('((/.*/[^eE]*)$)', $exception, $matches) && isset($matches[1]) && preg_match($matches[1], $key)) {
                   return array();
               }
            }
         }

        $filters = $this->filterCollection;
        $filterSet = null;

        $filters->rewind();

        while($filters->valid()) {
            $filter = $filters->current(); 
            $filters->next();
            if ($filter->execute($value) === true) {
                $filterSet[] = $filter;
            }
        }
         
        return $filterSet;
    }

    /**
    *  converter value.
    *
    * @param $value
    *
    * @return converted value
    */
    private function converter($value)
    {
       // check for magic quotes and remove them if necessary
        if (function_exists('get_magic_quotes_gpc') && !get_magic_quotes_gpc()) {
            $value = preg_replace('(\\\(["\'/]))im', '$1', $value);
        }

        // use the converter
        $converter = new \SOCLITE\Detector\Converter\Converter;
        $centrifuge = new \SOCLITE\Detector\Converter\Centrifuge;
        $value = $converter->runAllConversions($value);
        $cvalue = $centrifuge->runCentrifuge($value, $this);
        return $value;
     }

    /**
    * caltulate data and add event from our rules.
    *
    * @param $key
    *
    * @return $repost
    */
    private function addEvent($key, $value, $filters)
    {
        $tag = array();
        $cwe = [];
        $dis =[];
        $impact = 0;
        foreach ($filters as $filter) {
            $tags = $filter->getTags();
            array_push($cwe, $filter->cwe);
            array_push($dis, $filter->getDescription());
            array_push($tag, $tags[0]);
            $impact += $filter->getImpact();
        }

        $tag = array_count_values($tag);
        arsort($tag);
        $tag = array_keys($tag);
        $risk = 'low';

        if ($impact >= 15 && $impact <= 25) {
            $risk = 'medium';
        } elseif ($impact > 25) {
            $risk = 'high';
        }
        Report::init()->addEvent(
            array(
                'attack_type'    =>strtoupper($tag[0]),
                'risk'           => $risk,
                'cwe'            => implode(',', $cwe),
                'defence_method' => 'Validation',
                'method'         => $_SERVER['REQUEST_METHOD'],
                'url'            => $_SERVER['PHP_SELF'],
                'queryString'    => $_SERVER['QUERY_STRING'],
                'description'    => implode('.', $dis),
                'stacktrace'     => array_map(
                    function($value3){
                        return [
                            'file'        => $value3['file'],
                            'line'        => $value3['line'],
                            'in_function' => $value3['function'],
                            'class'       => isset($value3['class']) ? $value3['class'] : null,
                        ];
                    }, debug_backtrace(false)
                ),
            )
        );
     }

    /**
    * unavialble redirect and forword validation.
    *
    * @param $key
    *
    * @return $repost
    */
    private function urfValidation($key, $value) {

        $regex = "((https?|ftp)://)?([a-z0-9+!*(),;?&=$._-]+(:[a-z0-9+!*(),;?&=$._-]+)?@)?([a-z0-9-.]*)\.([a-z]{2,4})(:[0-9]{2,5})?(/([a-z0-9+$%_-]\.?)+)*/?(\?[a-z+&$._-][a-z0-9;:@&%=+/$._-]*)?(#[a-z_.-][a-z0-9+$%_.-]*)?"; 

        if(preg_match("~^$regex$~i", $value)){
            Report::init()->addEvent(
                array(
                    'attack_type'    => "URF",
                    'risk'           => 'medium',
                    'cwe'            => '227',
                    'defence_method' => 'Validation',
                    'description'    => '',
                    'method'         => $_SERVER['REQUEST_METHOD'],
                    'url'            => $_SERVER['PHP_SELF'],
                    'queryString'    => $_SERVER['QUERY_STRING'],
                    'stacktrace'     => array_map(
                        function($value3){
                            return [
                                'file'        => $value3['file'],
                                'line'        => $value3['line'],
                                'in_function' => $value3['function'],
                                'class'       => isset($value3['class']) ? $value3['class'] : null
                            ];
                        }, debug_backtrace(false)
                    ),
                )
            );
        return urlencode($value);
        }

        $urfkey = Config::getConfig('url_redirect_key');
        $key = explode('.', $key);

        if(in_array($key[1], $urfkey)){

            Report::init()->addEvent(
                array(
                    'attack_type'    =>'URF',
                    'risk'           => 'medium',
                    'cwe'            => '227',
                    'defence_method' => 'Validation',
                    'description'    => '',
                    'method'         => $_SERVER['REQUEST_METHOD'],
                    'url'            => $_SERVER['PHP_SELF'],
                    'queryString'    => $_SERVER['QUERY_STRING'],
                    'stacktrace'     => array_map(
                        function($value3){
                            return [
                                'file'        => $value3['file'],
                                'line'        => $value3['line'],
                                'in_function' => $value3['function'],
                                'class'       => isset($value3['class']) ? $value3['class'] : null
                            ];
                        }, debug_backtrace(false)
                    ),
                )
            );
            return urlencode($value);
        }
        return $value;
    }

    private function IDOR($keys, $value){
        $keys = explode('.', $keys);
        $key= $keys[1];

        if(!empty($_SESSION[$key]) && $key !== "csrf_token"){
            if($_SESSION[$key] !== $value){
                Report::init()->addEvent(
                    array(
                        'attack_type'=>'IDOR',
                        'risk' => 'high',
                        'method'  => $_SERVER['REQUEST_METHOD'],
                        'url' => $_SERVER['PHP_SELF'],
                        'queryString' => $_SERVER['QUERY_STRING'],
                        'stacktrace' => array_map(
                            function($value3){
                                return [
                                    'file'=>$value3['file'],
                                    'line'=>$value3['line'],
                                    'in_function'=>$value3['function'],
                                    'class'=>$value3['class']
                                ];
                            }, debug_backtrace(false)
                        ),
                    )
                );
                //session_destroy();
                //header('Location: /');
                //exit;
                return "";
            }
        }
        return $value;
    }

}
