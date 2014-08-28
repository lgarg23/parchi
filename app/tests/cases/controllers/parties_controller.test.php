<?php
/* Parties Test cases generated on: 2014-08-20 16:45:11 : 1408533311*/
App::import('Controller', 'Parties');

class TestPartiesController extends PartiesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class PartiesControllerTestCase extends CakeTestCase {
	function startTest() {
		$this->Parties =& new TestPartiesController();
		$this->Parties->constructClasses();
	}

	function endTest() {
		unset($this->Parties);
		ClassRegistry::flush();
	}

}
?>