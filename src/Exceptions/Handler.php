<?php

namespace Buonzz\Patos\Exceptions;

use Illuminate\Contracts\Debug\ExceptionHandler;

class Handler implements ExceptionHandler{

	public function report(\Exception $e){
		global $logger;
		$logger->info($e->getMessage());
	}	

	public function render($request, \Exception $e){}

	public function renderForConsole($output, \Exception $e){}
}