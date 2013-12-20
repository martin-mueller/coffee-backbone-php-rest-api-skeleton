<?php
/** 
* @package  MMs php REST api
* @author  Martin Müller github.com/martin-mueller
* @version  0.2
*/
namespace MMs;
require_once 'autoload.php';
require_once 'lib/PhpConsole.php';
$debug = true;
if ($debug) \PhpConsole::start();

$request 	= Request::factory('json');
$modelName  = $request->getPath(0);
$allowed_models = array('notes');

if (!in_array($modelName, $allowed_models)) throw new \Exception("404 Fehler kommt hier mal", 1);

$model   = new SimpleModelStore($modelName);
$data 	 = $request->getData();
// $valid_data = (isset($data) && is_array($data) && !empty($data));

$routes = array(
	"POST   /{$modelName}"				=> function () 	  use ($model, $data) {return $model->create($data);},
	"PUT    /{$modelName}/(?<id>\d+)"	=> function ($id) use ($model, $data) {return $model->update($id, $data);},
	"PATCH  /{$modelName}/(?<id>\d+)"	=> function ($id) use ($model, $data) {return $model->update($id, $data);},
	"DELETE /{$modelName}/(?<id>\d+)"	=> function ($id) use ($model) 		  {return $model->delete($id);},
	"GET    /{$modelName}/(?<id>\d+)"	=> function ($id) use ($model) 		  {return $model->get($id);},
	"GET    /{$modelName}"				=> function ()    use ($model)  	  {return $model->getAll();}
);


$result = Router::run($routes);
debug($log);
echo json_encode($result);


function debug($message, $tags = 'debug') {
	if ($GLOBALS['debug'] === true){
		if (is_array($message) || is_object($message))
			$message = var_export($message, true);
		\PhpConsole::debug($message, $tags);
	}
	return false;
}