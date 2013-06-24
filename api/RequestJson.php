<?php
namespace MMs;

class RequestJson extends Request{

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

