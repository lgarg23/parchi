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
?>
</div>
