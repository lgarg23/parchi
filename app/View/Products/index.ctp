<div class="products index">
	<h2><?php __('Products');?></h2>
	<div class="actions" style = "width:100%;">
		<input type = "text" class = "product_name" id = "product_name" style = "width: 220px;height: 25px;"/>
		<?php echo $this->Html->link(__('Add', true), array('action' => 'view'), array('id' => 'add')); ?>
		<?php echo $this->Html->link(__('Edit', true), array('action' => 'view'), array('id' => 'edit')); ?>
		<?php echo $this->Html->link(__('Add Quantity', true), array('action' => 'edit'), array('id' => 'add_quantity')); ?>
	</div>
			
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('net_quantity');?></th>
			<th><?php echo $this->Paginator->sort('price');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class=/"actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($products as $product):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo wordwrap($product['Product']['name'], 15, " ", true); ?>&nbsp;</td>
		<td><?php echo $product['Product']['net_quantity']; ?>&nbsp;</td>
		<td><?php echo $product['Product']['price']; ?>&nbsp;</td>
		<td><?php echo $product['Product']['created']; ?>&nbsp;</td>
		<td><?php echo $product['Product']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $product['Product']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $product['Product']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Product', true), array('action' => 'add')); ?></li>
	</ul>
</div>
<script>
	$('#add').click(function(e){
		e.preventDefault();
		val = $('#product_name').val();
		if(val == ''){
			alert('Please enter product name, you want to add.');
		}else{
			location.href = '/products/add/'+val;
		}
	});

	$('#edit').click(function(e){
		e.preventDefault();
		val = $('#product_name').val();
		if(val == ''){
			alert('Please enter product name, you want to edit.');
		}else{
			location.href = '/products/edit/'+val;
		}
	});

	$('#add_quantity').click(function(e){
		e.preventDefault();
		val = $('#product_name').val();
		if(val == ''){
			alert('Please enter product name, you want to add quantity.');
		}else{
			location.href = '/products/add_quantity/'+val;
		}
	});
	
</script>