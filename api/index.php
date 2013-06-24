<?php
namespace MMs;
require_once 'autoload.php';

$request = Request::factory('json');
// $path    = $request->getPath();
// $model   = $path[0];
// $id 	 = isset($path[1]) ? $path[1] : false;
// $method  = $request->getMethod();
// $model   = new Model($model, $id);

$routes = array(
	'POST   /(?<model>\w+)'				=> 'create_model',
	'PUT    /(?<model>\w+)/(?<id>)'		=> 'update_model',
	'PATCH  /(?<model>\w+)/(?<id>)'		=> 'update_model',
	'DELETE /(?<model>\w+)/(?<id>)'		=> 'delete_model',
	'GET    /(?<model>\w+)/(?<id>)'		=> 'get_model',
	'GET    /(?<model>\w+)'		=> 'getall_models'
);

Router::run($routes);

function create_model($model){
	$data = RequestJson::getData();
	$id = $modelClass->create($data);
	return $id;
}



// var_dump($_POST);
// var_dump($request::getHeaders());
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