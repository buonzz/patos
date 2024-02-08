<?php namespace Buonzz\Patos;

class PhpFilesList {

	public static function get($folder){
        $result = array(); 
        $cdir = scandir($folder); 
        foreach ($cdir as $key => $value) 
        {  
            if (!is_dir($folder . DIRECTORY_SEPARATOR . $value)) 
            { 
                $f = explode('.', $value);
                $f = strtolower(array_pop($f));

                if (in_array($f, ['php']))
                    $result[] = $folder . $value; 
            } 
        } 
        
        return $result; 
	}

}