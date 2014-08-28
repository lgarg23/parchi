<?php
/* Party Test cases generated on: 2014-08-20 16:45:08 : 1408533308*/
App::import('Model', 'Party');

class PartyTestCase extends CakeTestCase {
	function startTest() {
		$this->Party =& ClassRegistry::init('Party');
	}

	function endTest() {
		unset($this->Party);
		ClassRegistry::flush();
	}

}
?>