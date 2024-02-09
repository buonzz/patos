<?php namespace Buonzz\Patos;

class PhpFilesList {

	public static function get($folder){

		$output = array();
		$it = new \RecursiveDirectoryIterator($folder);
		$display = array( 'php');
		
		foreach(new \RecursiveIteratorIterator($it) as $file)
		{
			$f = explode('.', $file);
			$f = strtolower(array_pop($f));
	
		    if (in_array($f, $display))
		        $output[] = $file;
		}

		return $output;

	}

}