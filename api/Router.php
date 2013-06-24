<?php 
namespace MMs;

class Router{
	/**
	 * Routes format: array('route1'=>'callback1','route2'=>'callback2') etc.
	 * @var array
	 */
	private static $routes  = array();
	/**
	 * Parameter array passed to the controller by Url (i.e. action, id, category etc.)
	 * @var array
	 */
	public static $params = array();

	private static $allowed_methods = array('GET','POST','PUT','DELETE','PATCH');


	/**
	 * add a route
	 * @param  $path "METHOD regex" for a route with named parameters
	 *         example 1: "POST /(?<model>\w+)/(?<id>\d+)"
	 *         example 2: "/test/(?<foo>\w+)" - method is set to "GET", if not present            
	 * @param  $callback function to execute for the route (i.e. a controller)
	 */
	public static function set($path, $callback ){
		//remove multiple whitespaces
		$path = preg_replace('%\s{2,}%'," ",$path);
		
		$path = rtrim($path,'/').'/';
	
		self::$routes[$path] = $callback;
	}

	/**
	 * Compare Current Request with the Routes and run Callback for the first match
	 * @param  array  $routes [description]
	 * @return [type]         [description]
	 */
	public static function run($routes = array()){
		/**
		 * if $routes are passed directly via array:
		 * set all routes
		 */
		if (!empty($routes)){
			foreach ($routes as $path => $callback) {
				self::set( $path, $callback );
			}
		}
		$test = false;
		$params = array();
		echo "<pre>"; print_r(self::$routes);
		foreach (self::$routes as $path=>$callback) {
			$path_a = explode(' ', $path);
			if (count($path_a) == 2 && in_array($path_a[0], self::$allowed_methods)){
				$path   = $path_a[1];
				$method = $path_a[0];
			}
			else $method = 'GET';
			$path = '%'.$path.'%';
			$test = preg_match($path, rtrim(Url::rawPath(),'/').'/', $matches);

			/**
			 * if Url matches route- path:
			 *  - collect named Parameters from route
			 *  - call Callback- function with parameters
			 *  - done
			 */
			if (!empty($matches) && $method == Request::getMethod()){
				foreach($matches as $key=>$match){
					if (!ctype_digit((string) $key)){
						$params[$key] = $match;  
					}
				}
				self::$params = $params;
				if (!is_callable($callback)) $callback = __NAMESPACE__.'\\'.$callback;
				if (!is_callable($callback)){
					throw new \Exception('Unknown callback function: '.$callback);
				}
				// print_r($params);
				return call_user_func_array ($callback, $params);
				// break;
			}	
		}
	}

}