<?php

namespace Buonzz\Patos;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log {
	private $logger;	

	public function __construct(){
        $this->logger = new Logger('patos');
        $this->logger->pushHandler(new StreamHandler(getenv('LOGFILE'), Logger::DEBUG));
	}

	public function info($message){
		$this->logger->info($message);
	}
}