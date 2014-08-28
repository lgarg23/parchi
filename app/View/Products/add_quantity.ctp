<div class="products form">
<?php echo $this->Form->create('Product');?>
	<fieldset>
 		<legend><?php __('Edit Product'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo 'Name : - ' .  $this->request->data['Product']['name'] . '<br/>';
		echo 'Existing Quantity : - ' .  $this->request->data['Product']['net_quantity'] . '<br/>';
		echo 'Price : - ' .  $this->request->data['Product']['price'] . '<br/>';
		echo $this->Form->input('add_quantity');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Product.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Product.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Products', true), array('action' => 'index'));?></li>
	</ul>
</div>