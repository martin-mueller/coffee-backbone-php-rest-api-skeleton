<?php
namespace MMs;

class Url{

	static private $path = false;
	static private $rawPath = false;


	/**
	 * "Path" part ( after ".php"!! ) of the request string
	 * Use  RewriteRule ^(.*)$ index.php/$1 [QSA,L] for redirect!
	 * @return array [description]
	 */
	public static function path( $key=null ){
		$path = self::$path;
		if (!$path){ 
			$path	= isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
			self::$rawPath = $path;
			$path  	= trim($path,'/ ');
			$path 	= explode('/',$path);
			self::$path = $path;
		}
		if ($key!==null){
			if (isset($path[$key])) return $path[$key];
			else return '';
		}
		return $path;
	}

	public static function rawPath(){
		if (self::$rawPath === false){
			self::path();
		}
		return self::$rawPath;
	}

	


}
