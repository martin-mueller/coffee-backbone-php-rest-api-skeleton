<?php 
namespace MMs;

function autoload($class_name) {
	$class_name  = ucfirst($class_name);
	$file_name  = str_replace(__NAMESPACE__.'\\', '', $class_name);
	$file_name	= __DIR__.'/'.$file_name . '.php';
	if (file_exists($file_name)){
    	require_once $file_name;
    }
    if (!class_exists($class_name,false)) throw new \Exception("Unable to load class: $class_name");
}
spl_autoload_register(__NAMESPACE__ . "\\autoload");
