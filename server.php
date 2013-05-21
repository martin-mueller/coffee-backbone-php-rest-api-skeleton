<?php
/** 
*
*
* @package  MMs php REST api
* @author  Martin Müller github.com/martin-mueller
* @version  0.2
* ## RESTful, Zero- Config Server, stores your models in a sqlite- database
*
*
* ## TODO: wrap first part in http class (namespace?)
* * do validations on models ( models.json, db config in json ),
*   when not in $demo_mode
* * map models to php classes (alternative db-structures in different classes )
* * collection post or json - import
* * HTTP status codes
*
*
*/

/**
 * Put your model names in this array down here
 * @var array
 */

namespace MMs;


$allowed_models = array('notes');
$log	= array();
$debug	= true;

if ($debug === true){
	require_once 'api/lib/PhpConsole.php';
	\PhpConsole::start();
}

$http = new Http($options = array(
		'allowed_models'=>array('notes'))
);




$method = $_SERVER['REQUEST_METHOD'];
$path	= isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
$input	= file_get_contents('php://input');



$path  = trim($path,'/ ');
$parts = explode('/',$path);

/* Test url for valid model request */
$model = $parts[0];
if (!in_array($model, $allowed_models)){
 	$log[] = "Model $model is not registered";
 	$model = null;
}

/* If we have a valid model, check if req contains an id */
if ($model && isset($parts[1]) && ctype_digit($parts[1]) && $parts[1] > 0){
	$id = (int) $parts[1];
}
else $id = null;

/*check input for data*/
if ($input > '' ){
	$data = json_decode($input, true);
	$e = json_last_error();
	if ($e != JSON_ERROR_NONE) $log[]='json decoding error:'.$e;
}
$valid_data = (isset($data) && is_array($data) && !empty($data));

/* unset id from data since we got it from url, if any */
if ($valid_data && array_key_exists('id', $data)) unset($data['id']);



if ($model){

	$result = false;
	$modelClass = new Model($model);

	switch ($method) {
		case 'POST':
			if ($id === null && $valid_data) $result = $modelClass->create($data);
			if ($result>0) $data['id'] = $result;
			break;
		case 'PUT':
			if ($valid_data && $id) $result = $modelClass->update($id, $data);
			if ($result === true) $data['id'] = $id;
			break;
		case 'PATCH':
			if ($valid_data && $id) $result = $modelClass->update($id, $data);
			if ($result === true) $data['id'] = $id;
			break;	
		case 'DELETE':
			if ($id) $result = $modelClass->delete($id);
			break;
		case 'GET':
			if ($id) $data = $modelClass->get($id);
				else $data = $modelClass->getAll();
			break;		
		default:
			# code...
			break;
	}
}

//header("HTTP/1.0 404 Not Found");



/* prepare response */
$response_body = '';
if (isset($data) && is_array($data)) $response_body = json_encode($data);
header('Content-Type: application/json');
echo $response_body;

error_log(date('h:i:s').' '.implode(",", $log)."\n" ,3,'log.txt');
// error_log(date('h:i:s')."| $method | $path | $input \n" ,3,'errors.txt');


/**
* 	
*/
class Http 
{
	
	private $defaults = array(
			'respond_content_type' => 'application/json',
//TODO: allow chrome console
			'allowed_client_origins' => array( 
										'localhost',
										'127.0.0.1'
										),
			'allowed_models' => array(),
			'sendback_on_put' => false
		);

	private $options;

	private $request_method;
	private $request_content_type = 'application/json';
	private $request_body = '';
	private $errors = array();
	private $response_headers = array();
	private $response_body = array();
	private $model = null;
	private $id = null;


	public function __construct( $options = array() )
	{
		$this->options = array_merge($this->defaults, $options);
		$this->request_method = $_SERVER['REQUEST_METHOD'];
		$this->getRequestHeaders();
		$this->parseRequestPath();
		$this->setResponseHeader($this->options['respond_content_type']);
	}


	private function getRequestHeaders(){
		$headers = apache_request_headers();
		if ($headers == false) $this->errors[] = 'No valid headers in request';
		$header_string = '';
		foreach ($headers as $header => $value) {
		    $header_string .= "$header: $value\n";
		}
		$header_string .= "\n";
		file_put_contents('headers.txt', $header_string);
		return $headers;
	}


	/**
	 * get the input
	 * @param  string $type [description]
	 * @return [type]       [description]
	 */
	public function getRequestBody($type = 'json')
	{
		return file_get_contents('php://input');
	}

	/**
	 * parse path, check for valid model and id
	 * @return array $model and id
	 */
	private function parseRequestPath()
	{
		$path	= isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
		$path  	= trim($path,'/ ');
		$parts 	= explode('/',$path);
		$model 	= $parts[0];
		debug($this->options['allowed_models']);
		if (!in_array($model, $this->options['allowed_models'])){
 			$GLOBALS['log'][] = "Model $model is not registered";
 			$model = null;
 		}
 		else $this->model = $model;
		/* If we have a valid model, check if req contains an id */
		if ($model && isset($parts[1]) && ctype_digit($parts[1]) && $parts[1] > 0){
			$this->id = (int) $parts[1];
		}
		else $this->id = null;
		return array('model' => $this->model, 'id' => $this->id);
	}

	public function validateRequestData()
	{
		
	}

	public function validateRequestHeader($validate_header_options = array())
	{
		# code...
	}

	public function storeRequestData($resource, $callbackfunction)
	{
		# code...
	}



	public function getResponseData($resource, $callbackfunction)
	{
		# code...
	}
	public function setResponseHeader($key='',$value='')
	{
		$this->response_headers[$key] = $value;
	}
	public function setResponseBody($data)
	{
		# code...
	}



	public function putResponse()
	{
		if (!empty( $this->response_headers )){
			foreach ($this->response_headers as $resp_key => $resp_value) {
				header($key.': '.$value);
			}
		}
		else $GLOBALS['log'][] = 'No response headers set';
		if (!empty( $this->response_body )){
			echo $this->response_body;

		}
	}
}/* end of class MMs\Http */






/* ## Simple db class with a bit of JSON remapping yet, we call it model */

class Model{

	private $model;
	private $table;
	private $db;

	/**
	 *
	 * @param string $name the model(collection) name (plural) = db- table
	 */

	public function __construct($name){

		$this->model = $this->table = $name;
		try{
			$this->db = new \PDO('sqlite:models.sqlite');
		}
		catch (PDOException $e) {
		   $GLOBALS['log'][] = 'Connection failed: ' . $e->getMessage();
		}
		$this->db->exec("CREATE TABLE IF NOT EXISTS {$this->table} (id INTEGER, key VARCHAR(100), value TEXT, PRIMARY KEY (id, key))");

	}


	/**
	 *
	 *  Only support for one recursion now, keep it simple 
	 *  Rules: if field is a simple value, store the value, otherwise reencode to JSON
	 *  + Simple (string) values are searchable by SQL
	 *  @param array $data insert data (without id)
	 *  @return int created id for the model, false on error
	 */


	public function create($data){
		
		$stmt = $this->db->prepare("INSERT INTO {$this->table} (id, key, value) VALUES (:id, :key, :value)");
		$stmt->bindParam(':key', $key);
		$stmt->bindParam(':value', $value);
		$stmt->bindParam(':id', $id, \PDO::PARAM_INT);
		$id = $this->createId();
		// var_dump($stmt);
		$result = true;
		foreach ($data as $key => $value) {
		
			if (is_array($value)) $value = json_encode($value);
			$GLOBALS['log'][] = "CREATE $this->model: $id, $key, $value";
			$result = $result && $stmt->execute();
		}
		if ($result === true) return $id;
		return $result;
		
	}


	/**
	 * Why INSERT OR REPLACE ? Because Prim. Key is compounded (id,key) 
	 * and so we can add in new properties of the model later
	 * @param int $id id of the model
	 * @param array $data update data (without id)
	 */

	public function update($id, $data){
		$stmt = $this->db->prepare("INSERT OR REPLACE INTO {$this->table} (id, key, value) VALUES (:id,:key,:value) ");
		$stmt->bindParam(':key', $key);
		$stmt->bindParam(':value', $value);
		$stmt->bindParam(':id', $id, \PDO::PARAM_INT);
		// var_dump($stmt);
		$result = true;
		foreach ($data as $key => $value) {
		
			if (is_array($value)) $value = json_encode($value);
			$GLOBALS['log'][] = "UPDATE $this->model: $id, $key, $value";
			$result = $result && $stmt->execute();
		}
		return $result;
	}

	/**
	 * Delete Model
	 * @param  int $id Model- id
	 * @return boolean   success
	 */
	public function delete($id){
		$stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id=:id");
		$stmt->bindParam(':id', $id, \PDO::PARAM_INT);
		$GLOBALS['log'][] = "DELETE $this->model: $id";
		// var_dump($stmt);
		return $stmt->execute();
	}

	/**
	 *  Get Model from db by id and convert it back into data array
	 *  @return array the model data (including id)
	 */

	public function get($id){
		$stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id=:id");
		$stmt->bindParam(':id', $id, \PDO::PARAM_INT);
		$stmt->execute();
		$rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$result = $this->convertData($rows);
		$GLOBALS['log'][] = "GET $this->model: $id";
		return $result;
	}

	/**
	 * Get the Collection
	 * @return array Array of all Models in the database
	 */
	public function getAll(){
		$stmt = $this->db->prepare("SELECT id, * FROM {$this->table} ORDER BY id");
		$stmt->execute();
		$models = $stmt->fetchAll(\PDO::FETCH_GROUP|\PDO::FETCH_ASSOC);
		if (is_array($models) && !empty($models)){
			foreach ($models as $key=>$model) {
				$result[] = $this->convertData($model);
			}
			$GLOBALS['log'][] = "GETALL $this->model";
			return $result;
		}
		return false;
	}

	/**
	 * Convert db- data back into Model data array
	 * @param  array  $rows properties of single Model
	 * @return array       Model- data
	 */
	private function convertData($rows = array()){
		foreach ($rows as $row){
			if (!isset($result['id'])) $result['id'] = $row['id'];
			if ($this->isJson($row['value'])) $row['value'] = json_decode($row['value']);
			$result[$row['key']] = $row['value'];
		}
		if (isset($result) && is_array($result)){
			return $result;
		}
		else return false;
	}


	/**
	 * Create new id for create method
	 * @return int the id (like autoincrement)
	 */
	private function createId(){
		$res = $this->db->query("SELECT MAX(id) FROM {$this->table}");
		// var_dump($res);
		$id = $res->fetchColumn();
		// var_dump($id);
		if ($id>0) return $id + 1;
		return 1;
	}

	public function isJson($string) {
 		json_decode($string);
 		return (json_last_error() == JSON_ERROR_NONE);
	}
}	


function debug($message, $tags = 'debug') {
	if ($GLOBALS['debug'] === true)
		if (is_array($message))
			$message =var_export($message, true);
		\PhpConsole::debug($message, $tags);
	return false;
}