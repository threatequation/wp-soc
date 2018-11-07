<?php 

namespace SOCLITE\Detector;
/**
* dependency checker implemented using sensio labs
*/
use SensioLabs\Security\SecurityChecker;

use \SOCLITE\Config;

class DLChecker
{
	public static $instance = null;

	public function __construct() {
		
		if( !Config::isVerified() ){
			return false;
		}
		$this->check();
		
		return $this;

	}

	public static function init() {
		if ( self::$instance == null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function check(){

		$lockfile= Config::getConfig('composerLock');

		$lockinfo=pathinfo($lockfile);

		if(file_exists($lockfile) && $lockinfo['basename'] == 'composer.lock'){
			$modified = date ("Y-m-d H:i:s", filemtime($lockfile));
			$pevday = date('Y-m-d H:i:s', time() - (24 * 60 * 60));
			$lastmodified= Config::getConfig("lock-modified");
			
			if($lastmodified == null){
				Config::setConfig("lock-modified", $modified);
				$lastmodified =$modified;
			}


			if($pevday > $lastmodified || $modified >= $lastmodified){	
				$data = null;
				$checker = new SecurityChecker();
				$data = $checker->check($lockfile);
				if($data !=null){
					Config::setConfig("lock-modified", date('Y-m-d H:i:s', time()));
				}
				if($data){
					$this->sendLog($data);
				}
				
				return $data;				
			}			
		}else{
			throw new \Exception(
            'Please make sure that '.$lockfile.' is composer.lock file'
        	);
		}	
		
	}


	public function sendLog($data){
		$data3=array();
		foreach ($data as $key => $value) {
			$pn=explode('/', $key)[1];
			$data3[$pn]=array('name'=>htmlspecialchars($key), 'version'=>  $value['version'], 'description'=>$value['advisories']);
		}

		$data2 = array(
			"product_id"		=> Config::getConfig('product_id'),
			"plugin_type" 		=> Config::getConfig('ApplicationName'),
			"library_data" 		=> json_encode($data3)
		);
    	return Send::curl('/library/', $data2);
	}

}


 ?>