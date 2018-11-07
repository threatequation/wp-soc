<?php
namespace SOCLITE\Detector;

use \SOCLITE\Config;
/**
   * @class for detect csrf vernarable;
   */
class CSRF
{
    /**
    * @var
    * diclear vriable for csrf
    */
    private static $token = null;
    //csrf token;
    private static $csrfTokenName = 'csrf_token';

    public static $instance = null;

    private static $tokenLength = 32;

    /**
     * init function.
     */

    public function  __construct( )
    {
        $validateToken = false;
        /*
        * if mod_csrfp already enabled, no verification, no filtering
        * Already done by mod_csrfp
        */
        if (getenv('mod_csrfp_enabled')) {
            return true;
        }
        //start session in case its not
        if (session_id() == '') {
            session_start();
        }


        if (count($_GET) > 0 || count($_POST) > 0) {
            $validateToken=$this->validateToken();
        }

        if (
            !isset($_COOKIE[self::$csrfTokenName])
            || !isset($_SESSION[self::$csrfTokenName])
        ){
            $this->refreshToken();
        }
        // Initialize output buffering handler
        ob_start(array($this, 'ob_handler'));

        return !$validateToken;
    }

    public static function init() {
        if( self::$instance == null ) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * function to authorise incoming post requests.
     *
     * @param
     * void
     *
     * @return:
     * bolean
     */
    public function validateToken()
    {
        $isAttack= false;

        if (count($_GET) === 0 && count($_POST) === 0) {
          return true;
        }
        //#todo this method is valid for same origin request only,
        //enable it for cross origin also sometime
        //for cross origin the functionality is different
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //currently for same origin only
            if (!(isset($_POST[self::$csrfTokenName])
                && isset($_SESSION[self::$csrfTokenName])
                && (self::isValidToken($_POST[self::$csrfTokenName]))
                )) {
                    $isAttack=$this->isAttack();

            } else {

              $this->refreshToken();    //refresh token for successfull validation
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {

            //currently for same origin only
            if (!(isset($_GET[self::$csrfTokenName])
                && isset($_SESSION[self::$csrfTokenName])
                && (self::isValidToken($_GET[self::$csrfTokenName]))
                )) {
                    $isAttack= $this->isAttack();
            } else {
                $this->refreshToken();    
                //refresh token for successfull validation
            }
        }

        return !$isAttack;
    }

    private function isAttack()
    {
        $_GET = array();
        $_POST = array();
        $_REQUEST = array();

        Report::init()->addEvent(
            array(
                'attack_type' => 'CSRF',
                'risk' => 'high',
                'cwe' =>'325',
                'defence_method' => 'Tokenize',
                'method'  => $_SERVER['REQUEST_METHOD'],
                'url' => $_SERVER['PHP_SELF'],
                'queryString' => $_SERVER['QUERY_STRING'],
                'description' => "CSRF",
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

      return true;
    }

    /*
     * function to check the validity of token in session array
     * Function also clears all tokens older than latest one
     *
     * Parameters:
     * $token - the token sent with GET or POST payload
     *
     * Returns:
     * bool - true if its valid else false
     */
    private static function isValidToken($token)
    {
        if (!isset($_SESSION[self::$csrfTokenName])) {
            return false;
        }

        return $_SESSION[self::$csrfTokenName] === $token;
    }

      /*
       * Function to set auth cookie
       * Returns:
       * void
       */
    private function refreshToken()
    {
        self::$token = self::generateAuthToken();

        if (!isset($_SESSION[self::$csrfTokenName])
        || !is_array($_SESSION[self::$csrfTokenName])) {
            $_SESSION[self::$csrfTokenName] = '';
        }

        //set token to session for server side validation
        $_SESSION[self::$csrfTokenName] = self::$token;
        //set token to cookie for client side processing
        setcookie(self::$csrfTokenName,
        self::$token,
        time() + 1800);
    }


    /*
     * function to generate random hash of length as given in parameter
     * Parameters:
     * Returns:
     * string, token
     */
    private static function generateAuthToken()
    {
        //#todo - if $length > 128 throw exception
        if (function_exists('hash_algos') && in_array('sha512', hash_algos())) {
            $token = hash('sha512', mt_rand(0, mt_getrandmax()));
        } else {
            $token = '';
            for ($i = 0; $i < 128; ++$i) {
                $r = mt_rand(0, 35);
                if ($r < 26) {
                    $c = chr(ord('a') + $r);
                } else {
                    $c = chr(ord('0') + $r - 26);
                }
                $token .= $c;
            }
        }
        return substr($token, 0, self::$tokenLength);
    }

    /**
     *get csrt token;.
     *
     * @param void
     *
     * @return token
     */
    public static function getToken()
    {
        return self::$token;
    }

    /**
     * Function: ob_handler
     * Rewrites <form> on the fly to add CSRF tokens to them. This can also
     * inject our JavaScript library.
     *
     * Parameters:
     * $buffer - output buffer to which all output are stored
     * $flag - INT
     *
     * Return:
     * string, complete output buffer
     */
    private function ob_handler($buffer, $flags)
    {
      if (stripos($buffer, '<html') == false) {
          return $buffer;

      }
        // // Perfor static rewriting on $buffer
      $buffer = self::rewriteHTML($buffer);

      return $buffer;
    }

    /**
     *
     * @function: rewriteHTML
     * Function to perform static rewriting of forms and URLS
     *
     * @param:
     * $buffer - output buffer
     *
     * @return:
     * $buffer - modified buffer
     */
    private static function rewriteHTML($buffer)
    {
        $token = $_SESSION[self::$csrfTokenName];

        $count = preg_match_all('/<form(.*?)>(.*?)<\\/form>/is', $buffer, $matches, PREG_SET_ORDER);
        if (is_array($matches)) {
            foreach ($matches as $m) {
                $buffer = str_replace($m[0],
                    "<form{$m[1]}>
			             <input type='hidden' name='". self::$csrfTokenName ."' value='{$token}' />{$m[2]}
                    </form>",
                $buffer);
            }
        }

        return $buffer;
    }


    /**
      * function for get token field. this will generate a hidden input field with csrf token
      * @return
      * hidden input field.
      */

    public static function getTokenField(){

      if(!isset($_SESSION[self::$csrfTokenName])){
        self::refreshToken();
      }

      $token = $_SESSION[self::$csrfTokenName];


      return "<input type='hidden' name='". self::$csrfTokenName ."' value='{$token}' />";

    }

/*End The Class*/
}
