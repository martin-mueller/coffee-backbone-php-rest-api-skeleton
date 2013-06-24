<?php

/**
* 	
*/
class HttpService 
{
	private $request;
	private $response;


	private static function getRequest()
	{
		self::$request  = $request;
	}

	private static function setResponse()
	{
		self::$response = $response;
	}

	public static function run($request, $response, $callback_function, $arguments)
	{
		
		getRequest($request);
		$response = call_user_func($callback_function, $arguments);
		setResponse($response);

	}
}