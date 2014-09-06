<?php
	echo $this->Form->create();
	echo $this->Form->input('start_date', array('type' => 'text',
									'class' => 'datepicker',
									'style' => 'width: 150px;height: 10px;'
	));
	
	echo $this->Form->input('end_date', array('type' => 'text',
									'class' => 'datepicker',
									'style' => 'width: 150px;height: 10px;'
	));
	
	echo $this->Form->submit('Submit');
	
	if(empty($this->request->data)){
		echo __('No records found.');
	}
	unset($this->request->data['Product']);
	if(!empty($this->request->data)){
?>
<table>
	<tr>
		<td>Item</td>
		<td>Quantity Sold</td>
		<td>Date</td>
	</tr>
	<?php		
		foreach($this->request->data as $key => $val){
	?>
	<tr>
		<td><?php echo $val['Retail']['product_id']; ?></td>
		<td><?php echo $val[0]['total_quantity']; ?></td>
		<td><?php echo date('d-m-Y', strtotime($val['Retail']['date'])); ?></td>
	</tr>
	<?php				
			}		
		}
	?>
</table>
<script>
	$(document).ready(function(){
		
	});
</script>
