<?php
namespace MMs;
require_once 'autoload.php';
require_once 'lib/PhpConsole.php';
$debug = true;

$request 	= Request::factory('json');
$modelName  = $request->getPath(0);

$model   = new SimpleModelStore($modelName);
$data 	 = $request->getData();
// $valid_data = (isset($data) && is_array($data) && !empty($data));

$routes = array(
	"POST   /{$modelName}"				=> 'create_model',
	"PUT    /{$modelName}/(?<id>\d+)"		=> 'update_model',
	"PATCH  /{$modelName}/(?<id>\d+)"		=> 'update_model',
	"DELETE /{$modelName}/(?<id>\d+)"		=> 'delete_model',
	"GET    /{$modelName}/(?<id>\d+)"		=> function($id) use ($model) {return $model->get($id);},
	"GET    /{$modelName}"		=> function() use ($model) { return $model->getAll();}
);

// if ($model == 'note')
$result = Router::run($routes);
// else $result = '';
echo json_encode($result);

if (isset($log)) debug($log);
function create_model($modelName){
	return $model->create($data);
}

function update_model($modelName, $id){
	return $model->update($data);
}

function delete_model($id){
	return $model->delete($id);
}

function get_model($id){
	echo "get";
	return $model->get($id);
}

function getall_models(){
	return $model->getAll();
}


// var_dump($_POST);
// var_dump($request::getHeaders());
// echo $req::getHeaders('Content-Type');
// echo $req::getHeaders('Accept-Encoding');
//var_dump($req->getData());
\PhpConsole::debug('hallo');

function debug($message, $tags = 'debug') {
	if ($GLOBALS['debug'] === true){
		if (is_array($message))
			$message = var_export($message, true);
		\PhpConsole::debug($message, $tags);
	}
	return false;
}