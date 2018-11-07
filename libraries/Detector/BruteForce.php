<?php
namespace SOCLITE\Detector;

/**
 *  class for detect and protect bruteForce.
 */
class BruteForce
{
    private $pdo = null;
    private $mysqli = null;

    public static $instance = null;

    public function __construct($db)
    {
       if($db instanceof \PDO){

          $this->pdo = $db;

       }else if($db instanceof \MySQLi){

          $this->mysqli = $db;
          
       }else{
          throw new \InvalidArgumentException(
                'Invalid argument. expected and instanceof MySQLi OR PDO'
            );
       }

       $this->_createTable();

    }

    public static function init( $db ) {
        if ( self::$insnance == null ){
            self::$instance = new self();
        }

        return self::$instance;
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

  //setup and return database connection
    private function _createTable()
    {

      //create bruteForce table
      try {
           $this->pdo->query('SELECT 1 FROM bruteForce LIMIT 1');
      } catch (\PDOException $ex) {
           $this->pdo->query('CREATE TABLE bruteForce(
                user_id VARCHAR(255) NOT NULL,
               ip_address VARCHAR(20) NOT NULL,
               attempted_at DATETIME NOT NULL
             );');
      }
       
    }

  //add a failed login attempt to database. returns true, or error
    public function addFailedLoginAttempt($user_id)
    {
        
        //attempt to insert failed login attempt
        try {
            $stmt =  $this->pdo->query('INSERT INTO `bruteForce`(`user_id`, `ip_address`, `attempted_at`) VALUES ("'.$user_id.'","'.self::getIP().'",NOW())');
        } catch (\PDOException $ex) {
            //return errors
            throw new \Exception($ex->getMessage());
        }
    }

    private static $default_throttle_settings = [
            2 => 50,
          4 => 80,
          6 => 200,
        ];


    /**
    * get the current login status. 
    * either safe, delay, catpcha, or error
    */
    public function getLoginStatus($user_id, $options = null)
    {
        

        //setup response array
        $response_array = array(
            'status' => 'safe',
            'message' => null,
        );
        //get local var of throttle settings. check if options parameter set
        if ($options == null) {
            $throttle_settings = self::$default_throttle_settings;
        } else {
            //use options passed in
            $throttle_settings = $options;
        }


        $keys = array_keys($throttle_settings);
        $values = array_values($throttle_settings);



        
        try {

          
            $this->cleanRecord( $keys[2] + 10 );

            // Lavel 1 
            if (is_int($keys[0]) && $keys[0] != null) {
        
                $get_number1 = $this->getDBResult($keys[0]);

                
                if ($get_number1 >= $values[0]) {

                    $response_array['status'] = 'delay';
                    $response_array['message'] = 'There are '.$get_number1.' fail attempt';

                    $this->addEvent('Low');


                    // Lavel 2
                    if (is_int($keys[1]) && $keys[1] != null) {
                        //get all failed attempst within time frame
                 
                        $get_number2 = $this->getDBResult($keys[1]);

                        if ($get_number2 >= $values[1]) {

                            $response_array['status'] = 'catpcha';
                            $response_array['message'] = 'There are '.$get_number2.' fail attempt';

                            $this->addEvent('Mideum');


                            // Lavel 3
                            if (is_int($keys[2]) && $keys[2] != null) {
                                $get_number3 = $this->getDBResult($keys[2], $user_id);
                                  if ($get_number3 >= $values[3]) {
                                      $response_array['status'] = 'block';
                                      $response_array['message'] = 'There are '.$get_number3.' fail attempt in user '. $user_id;


                                      $this->addEvent('high');
                                  }


                            } else {
                                throw new \InvalidArgumentException('Please make sure you provided currect options in functions');
                            }
                        }
                    } else {
                        throw new \InvalidArgumentException('Please make sure you provided currect options in functions');
                    }
                }

            } else {

                throw new \InvalidArgumentException('Please make sure you provided currect options in functions');
            }




           


        } catch (\PDOException $ex) {
            //return error
            $response_array['status'] = 'error';
            $response_array['message'] = $ex;
        }


        //return the response array containing status and message
        return $response_array;
    }


    /**
    * get all failed attempst within time frame
    */

    private function getDBResult($interval, $user=null){

      $sql = '';
      $sql .= 'SELECT * FROM bruteForce WHERE attempted_at > DATE_SUB(NOW(), INTERVAL :interval MINUTE)' ;

      if($user!==null)
      {
        $sql .= ' AND user_id =:user';
      }


      $stmt= $this->pdo->prepare($sql);
      
      $stmt->bindParam(':interval', $interval);
      $stmt->bindParam(':user', $user);

      $stmt->execute();
                
      return $stmt->rowCount();
                
    }


    private function addEvent($risk){
        
        Report::init()->addEvent(
            array(
                'attack_type' => 'BF',
                'risk' => $risk,
                'cwe' =>'421',
                'defence_method' => 'session Count',
                'method'  => $_SERVER['REQUEST_METHOD'],
                'url' => $_SERVER['PHP_SELF'],
                'queryString' => $_SERVER['QUERY_STRING'],
                'description' => "Tring to access in your serve again and again ",
                'stacktrace' => array_map(
                    function($value3){
                        return [
                            'file'=>$value3['file'],
                            'line'=>$value3['line'],
                            'in_function'=>$value3['function'],
                            'class'=> isset($value3['class']) ? $value3['class'] : null,
                        ];
                    }, debug_backtrace(false)
                ),
            )
        );

    }

    public function cleanRecord($interval){
      $stmt = $this->pdo->prepare('DELETE from bruteForce WHERE attempted_at < DATE_SUB(NOW(), INTERVAL :interval MINUTE)');
              $stmt->bindParam(':interval', $interval);

              $stmt->execute();
    }

    /**
     * @description clear the database
     *
     * @return true if clear databse or error PDO
     */
    public static function clearDatabase()
    {
        
        try {
            $stmt = $this->pdo->query('DELETE from bruteForce');

            return true;
        } catch (\PDOException $ex) {
            return $ex;
        }
    }
}
