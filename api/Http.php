<?php 

abstract class HttpRequest{
	
	private $errors = array();

	private static $headers = array();
	private static $body	= '';

	/* init status */
	private static $is_init = false;

	static function init(){
		self::setHeaders();
	
		self::$is_init = true;
	}


	static function factory($type){
		if (!self::$is_init) self::init();
		$classname = 'HttpRequest'.ucfirst($type);
		if (class_exists($classname))
			return new $classname;
		return false;
	}

	static public function getHeaders($key = null){
		if (isset($key)){
			if (isset (self::$headers[$key])) return self::$headers[$key];
			return false;
		}
		return self::$headers;
	}

	static private function setHeaders(){
		$headers = apache_request_headers();
		if ($headers == false) self::$errors[] = 'No valid headers in request';
		self::$headers = $headers;
		file_put_contents('headers.txt', var_export($headers, true));
		return self::$headers;
	}

	public function getData(){}
	

}

class HttpRequestJson extends HttpRequest{

	public function __construct(){
		echo "Hallo Welt";
	}

	public function getData(){
		if (isset(self::$body)){

		}
	}

}

// HttpRequest::init();
$req = HttpRequest::factory('json');
print_r ($req::getHeaders());
echo $req::getHeaders('Content-Type');
echo $req::getHeaders('Accept-Encoding');
