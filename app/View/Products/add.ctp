<div class="products form">
<?php echo $this->Form->create('Product');?>
	<fieldset>
 		<legend><?php __('Add Product'); ?></legend>
	<?php
		echo $this->Form->input('name', array('value' => $name));
		echo $this->Form->input('net_quantity');
		echo $this->Form->input('unit');
		echo $this->Form->input('price');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Products', true), array('action' => 'index'));?></li>
	</ul>
</div>
<script>
	$(document).ready(function(){
			
	});
</script>