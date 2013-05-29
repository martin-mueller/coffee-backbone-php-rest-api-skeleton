<?php 

abstract class HttpRequest{
	
	protected $errors = array();

	protected static $headers = array();
	protected static $body	= '';
	protected $data = array();

	/* init status */
	protected static $is_init = false;

	static function init(){
		self::setHeaders();
		self::setBody();
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
			$key = strtolower($key);
			if (isset (self::$headers[$key])) return self::$headers[$key];
			return false;
		}
		return self::$headers;
	}

	static private function setHeaders(){
		$headers = apache_request_headers();
		if ($headers == false) {
			self::$errors[] = 'No valid headers in request';
			return false;
		}
		foreach ($headers as $hkey => $hvalue) {
			$hkey = strtolower($hkey);
			self::$headers[$hkey] = $hvalue;
		}
		file_put_contents('headers.txt', var_export(self::$headers, true));
		return self::$headers;
	}
	
	protected static function setBody(){
		self::$body = file_get_contents('php://input');
	}

	public function getData(){}
	

}

class HttpRequestJson extends HttpRequest{

	public function __construct(){
		$this->setData(self::$body);
	}

	/**
	 * getter for request data
	 * @return array data array
	 */
	public function getData(){
		return $this->data;
	}

	private function setData($input){
		if (!empty($input)){
			$this->data = json_decode($input, true);
		}
	}

}

HttpRequest::init();
$req = HttpRequest::factory('json');
// print_r ($req::getHeaders());
// echo $req::getHeaders('Content-Type');
// echo $req::getHeaders('Accept-Encoding');
var_dump($req->getData());