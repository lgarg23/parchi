<?php
class UsersController extends AppController {
		
	public function beforeFilter(){
		$this->Auth->allow('register', 'login');
	}
		
	public function login() {
		if (!empty($this->request->data)) {
			if ($this->Auth->login()) {
				$this->redirect($this->_loginRedirect());
		    } else {
		       $this->Session->setFlash(__('Username or password is incorrect'), 'default', array('class' => 'error'));
		    }
		}
	}

    public function logout() {
		if ($this->Auth->logout() || $this->Session->delete('Auth')) :
			$this->Cookie->delete('Auth.User');
			setcookie("CakeCookie[Auth][User]", null, time()-10, '/','.'.$_SERVER['HTTP_HOST']);
			$this->Session->destroy();
			$this->Session->setFlash(__('Successful Logout'), 'default', array('class' => 'success'));
			$this->redirect(array('controller' => 'users', 'action' => 'login'));
		else :
			$this->Session->setFlash(__('Logout Failed'), 'default', array('class' => 'error'));
			$this->redirect($this->_loginRedirect());
		endif;
    }


/**
 * Set the default redirect variables, using the settings table constant.
 */
	private function _loginRedirect() {
		$redirect = $this->Auth->redirect();
		$redirect = array('controller' => 'products', 'action' => 'print_p');
		return $redirect;
	}

	public function register() {
		if($this->Auth->user('user_role') == 'admin'){		
			 if ($this->request->is('post')) {
			 	$user = $this->User->find('first', array('conditions' => array('User.username' => $this->request->data['User']['username']), 
			 								'recursive' => -1));
			 	if(!empty($user)){
					$this->Session->setFlash('User already exists with this username.');
					$this->redirect($this->referer());			 		
			 	}
			 	
			    if ($this->User->save($this->request->data)) {
			        $this->Session->setFlash('User registered successfully');
			        $this->redirect(array('controller' => 'users', 'action' => 'login'));
			    }
			 }
		}else{
			$this->Session->setFlash('You are not allowed to add users. Please contact admin.');
	        $this->redirect(array('controller' => 'products', 'action' => 'print_p'));
		}
	}
		
	}
?>