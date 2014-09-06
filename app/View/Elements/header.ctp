<?php if(CakeSession::read('Auth.User.id')){ ?>
<div class="btn-group">
<?php
		echo $this->Html->link(
						'Print Parchi',
						array('controller' => 'products', 'action' => 'print_p'),
						array('class' => 'btn')
		);
		
		echo $this->Html->link(
						'Retail',
						array('controller' => 'products', 'action' => 'inventory'),
						array('class' => 'btn')
		);
		
		echo $this->Html->link(
						'Products/Inventory',
						array('controller' => 'products', 'action' => 'index'),
						array('class' => 'btn')
		);
		
		if(CakeSession::read('Auth.User.user_role') == 'admin'){
			echo $this->Html->link(
						'Add User',
						array('controller' => 'users', 'action' => 'register'),
						array('class' => 'btn')
			);	
		}
		
		echo $this->Html->link(
						'Logout',
						array('controller' => 'users', 'action' => 'logout'),
						array('class' => 'btn')
		);
?>
</div>
<?php } ?>
