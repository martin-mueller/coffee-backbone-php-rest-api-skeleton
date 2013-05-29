<?php
require_once('simpletest/autorun.php');
require_once('../HttpRequest.php');

class RequestTest extends UnitTestCase {

	function testGetHeaders(){
		HttpRequest::init();
		$this->assertEqual(HttpRequest::getHeaders('HOst'),'localhost');

	}
}