<?php
// namespace MMs;
require_once 'SimpleModelStore.php';
require_once 'HttpRequest.php';
require_once 'HttpRouter.php';

$request = HttpRequest::factory('json');
$path    = $request->getPath();
$model   = $path[0];
$id 	 = isset($path[1]) ? $path[1] : false;
$method  = $request->getMethod();



$model   = new Model($model, $id);




var_dump($_POST);
var_dump($request::getHeaders());
// echo $req::getHeaders('Content-Type');
// echo $req::getHeaders('Accept-Encoding');
//var_dump($req->getData());



function debug($message, $tags = 'debug') {
	if ($GLOBALS['debug'] === true)
		if (is_array($message))
			$message = var_export($message, true);
		\PhpConsole::debug($message, $tags);
	return false;
}