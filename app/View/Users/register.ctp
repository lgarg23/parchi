<?php 
	echo $this->Form->create();
	echo $this->Form->input('user_role', array('options' => array('admin' => 'Admin', 'user' => 'User')));
	echo $this->Form->input('name', array('type' => 'text'));
	echo $this->Form->input('username', array('type' => 'text'));
	echo $this->Form->input('password', array('type' => 'password'));
	
	echo $this->Form->submit('Enter');
?> 