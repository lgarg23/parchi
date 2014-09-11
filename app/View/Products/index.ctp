<div class="products index" style = "width: 100%;border:none;">
	<h2><?php __('Products');?></h2>
	<?php $params = $this->request->params['named']; ?>
	<div class="actions header-none" style = "width:100%;">
		<input type = "text" class = "product_name print_none" id = "product_name" style = "width: 220px;height: 25px;" placeholder = "Product Name"/>
		<?php echo $this->Html->link(__('Add Item', true), array('action' => 'view'), array('id' => 'add', 'class' => 'print_none')); ?>
		<?php echo $this->Html->link(__('Edit Item', true), array('action' => 'view'), array('id' => 'edit', 'class' => 'print_none')); ?>
		<?php echo $this->Html->link(__('Add Quantity', true), array('action' => 'edit'), array('id' => 'add_quantity', 'class' => 'print_none')); ?>
		<?php echo $this->Html->link(__('Search', true), array('action' => 'index'), array('id' => 'searchProduct', 'class' => 'print_none')); ?>
	</div>
	<?php if(isset($params['search']) && $params['search'] != ''){ ?>		
		<table cellpadding="0" cellspacing="0">
		<tr>
				<th><?php echo 'Name';?></th>
				<th><?php echo 'Net Quantity';?></th>
				<th><?php echo 'Price';?></th>
				<th><?php echo 'Created';?></th>
				<th><?php echo 'Modified';?></th>
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
	<?php }else{ ?>
		<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo 'Name';?></th>
			<th><?php echo 'Net Quantity';?></th>
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
			<td class="actions">
				<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $product['Product']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $product['Product']['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
		</table>
	<?php } ?>
	<div class="paging header-none">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<script>
	$(document).ready(function(){
		var products_arr = [];
		var products = <?php echo json_encode($products_list); ?>;
		
		$.each(products, function(key, val){
			if(val != "" && val != null){
				products_arr.push(val);
			}
		});
		
		$("#product_name").autocomplete({
	      	source : products_arr
		});
	});

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

	$("#searchProduct").click(function(e){
		e.preventDefault();
		val = $('#product_name').val();
		if(val == ''){
			alert('Please enter product name, you want to search.');
		}else{
			location.href = '/products/index/search:'+val;
		}
	});
</script>
<style type="text\css" media="print">
</style>