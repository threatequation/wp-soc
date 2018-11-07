<?php

namespace SOCLITE\Detector;

use SOCLITE\Config;

/**
 * This class will collec all those event and then roport , create log and send to database.
 */
class Report
{
    private  $events = array();
    public static $instance = null;


    public function __construct(){
      
    }
    /**
     * get instance 
     * @return object instance of Report
     */
    public static function init() {
        if( self::$instance == null ){
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function addEvent(array $event)
    {
        $this->events[] = $event;
        $this->sendLog($event);
        $this->createLog($event);
    }

   /**
    * Return user ip address.
    *
    * @return string
    */
   private static function getIP()
   {
       $ipaddress = '';
       if (getenv('HTTP_CLIENT_IP')) {
           $ipaddress = getenv('HTTP_CLIENT_IP');
       } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
           $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
       } elseif (getenv('HTTP_X_FORWARDED')) {
           $ipaddress = getenv('HTTP_X_FORWARDED');
       } elseif (getenv('HTTP_FORWARDED_FOR')) {
           $ipaddress = getenv('HTTP_FORWARDED_FOR');
       } elseif (getenv('HTTP_FORWARDED')) {
           $ipaddress = getenv('HTTP_FORWARDED');
       } elseif (getenv('REMOTE_ADDR')) {
           $ipaddress = getenv('REMOTE_ADDR');
       } else {
           $ipaddress = 'UNKNOWN';
       }

       return $ipaddress;
   }




    public function getEvents()
    {
        return $this->events;
    }
  /**
   * check events is empty or not.
   *
   * @return true/false
   */
  public function isEmpty()
  {
      return empty($this->events);
  }
  /**
   *crete log file froms events.
   *
   * @return true if log file save
   *
   * @throws if file path not readable
   */
  private function createLog($event)
  {
      $log_file = Config::getConfig('cache').'/log.log';


        if(file_exists($log_file)){
          $handle = fopen($log_file, 'a');
        }else{
          $handle = fopen($log_file, 'w');
        }
        
          
        $data = $this->prepareData($event);
        if (!empty($data) && is_string($data)) {
            fwrite($handle, trim($data)."\n");
        }
        fclose($handle);
      

      return true;
  }

  /**
   * prepare event for string to save log file.
   *
   * @param $Event
   *
   * @return string
   */
  private function prepareData($event)
  {
      $format = '%s | %s | %s | %s';
      $attackedParameters = '';

      if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
        }

        $timestamp = gmdate('Y-m-d H:i:s.u', time());

      foreach ($event as $key => $value) {
          if(is_array($value)){
            $attackedParameters .= $key.':'.json_encode($value).', ';
          }else{
            $attackedParameters .= $key.':'.$value.', ';
          }
          
      }
      $dataString = sprintf($format,
                            $timestamp,
                            trim($attackedParameters),
                            $user_agent,
                             self::getIP()
                            );

      return $dataString;
  }

  /**
   *read log file.
   *
   * @return string or exceptipn
   */
  public function readLog()
  {
    $log_file = Config::getConfig('cache').'/log.log';

      if (is_writable($log_file) && file_exists($log_file)) {
          if ($file = file($log_file)) {
              $string = '';
              foreach ($file as $line_number => $line) {
                  $data_aray = explode('|', $line);
                  $string .= "<div id=\"log\">\n<strong>timestamp:</strong> {$data_aray[0]}<br />\n<strong>Data:</strong>".htmlspecialchars($data_aray[1], ENT_QUOTES)."<br />\n<strong>User Agent: </strong>{$data_aray[2]}</br><strong>IP:</strong> {$data_aray[3]}</br></br></div>";
              }

              return $string;
          } else {
              return 'Log file is empty';
          }
      } else {
          throw new \Exception(
            'Please make sure that '.$this->logfile.' is writeable.'
        );
      }
  }
  /**
   * send data report to database.
   */
    private function sendLog($event) {
        if( !Config::isVerified()){
            return false;
        }


      if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
        }

        $timestamp = gmdate('Y-m-d H:i:s.u', time());

        $data= array(
                "timestamp"   => $timestamp,
                "product_id"  => Config::getConfig('product_id'),
                "plugin_type" => Config::getConfig('ApplicationName'),
                "attack_type" => $event['attack_type'],
                "risk"        => $event['risk'],
                "attack_data" => json_encode(array(
                    "cwe"             => $event['cwe'],
                    "description"     => $event['description'],
                    "defence_method"  => $event['defence_method'],
                    "queryString"     => $event['queryString'],
                    "risk"            => $event['risk'],
                    "url"             => $event['url'],
                    "method"          => $event["method"],
                    "stacktrace"      => $event['stacktrace']
                )),
                "user_agent"  => $user_agent,
                "attacker_ip" => self::getIP()
            );
        do_action( 'te_event_log', $data );
        return Send::curl('/attack_log/', $data);
    }
}
