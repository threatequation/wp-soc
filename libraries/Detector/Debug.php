<?php

namespace SOCLITE\Detector;

use DebugBar\DebugBar;
use DebugBar\DataCollector\TimeDataCollector;
use DebugBar\DataCollector\RequestDataCollector;
use DebugBar\DataCollector\MemoryCollector;
use DebugBar\DataCollector\PhpInfoCollector;
use SOCLITE\Detector\Send;
use \SOCLITE\Config;

class Debug {

	public  $data;
	public static $instance = null;
	public $debugbar;

	public function __construct () {
		$this->debugbar = new DebugBar();
	}

	public static function init ( ) {
		if ( self::$instance == null ){
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function header () {
		$this->debugbar->addCollector(new TimeDataCollector());
		$this->debugbar->addCollector(new RequestDataCollector());
		$this->debugbar->addCollector(new MemoryCollector());
		$this->debugbar->addCollector(new PhpInfoCollector());
		$this->debugbar['time']->startMeasure('execution', 'Total time');
		return $this;
	}

	public function footer () {

		if( !isset( $_SESSION ) ){
			session_start();
		}

		$this->data = $this->debugbar->stackData()->getData();
		$id = $this->debugbar->getStackDataSessionNamespace();
		$_SESSION[ $id ] = [];
		return $this;
	}

	public function sendData () {
		
		$data = [
			'product_id'=> Config::getConfig('product_id'),
			'url'		=>  $_SERVER['PHP_SELF'],
			'agent'		=> 2,
			'data'		=> json_encode([
				'meta'		=> $this->data['__meta'],
				'time'		=> $this->data['time'],
				'server'	=> $_SERVER,
				'memory'	=> $this->data['memory'],
				'php'		=> $this->data['php'],

			]),
		];
		Send::curl('/debug_log/', $data);
	}
}