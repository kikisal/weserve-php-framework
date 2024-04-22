<?php

class Autoloader
{


	private static function resolvePath($symbols) {
		$result = '';
		
		for ($i = 0; $i < count($symbols); ++$i)
			$result .= $symbols[$i] . '/';
		
		return substr($result, 0, strlen($result) - 1);
	}
	
    public static function register($lookUpPaths)
    {
		
        spl_autoload_register(function ($class) use($lookUpPaths) {
			$symbols = explode('\\', $class);

			
			$idx 	   		= count($symbols) - 1;
			$className 		= $symbols[count($symbols) - 1];
			$logicPath 		= self::resolvePath(array_slice($symbols, 0, $idx));
			
			if (empty($logicPath))
				return false;
			
			foreach ($lookUpPaths as $path) {
				$classLogicFile = $path . $logicPath . '/' . $className . '.php';
			
				if (!file_exists($classLogicFile))
					return false;
				
				require $classLogicFile;
				return true;
			}
        });
    }
}