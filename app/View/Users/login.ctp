<?php 
	echo $this->Form->create();
	echo $this->Form->input('username', array('type' => 'text'));
	echo $this->Form->input('password', array('type' => 'password'));
	echo $this->Form->submit('Enter');
?> 