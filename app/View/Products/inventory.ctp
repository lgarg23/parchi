<?php
	echo $this->Form->input('date', array('type' => 'text',
									'class' => 'datepicker',
									'value' => $date,
									'style' => 'width: 150px;height: 10px;'
	));
	if(empty($this->request->data)){
		echo __('No records found.');
	}
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
		<td><?php echo date('F j, Y', strtotime($val['Retail']['date'])); ?></td>
	</tr>
	<?php				
			}		
		}
	?>
</table>
<script>
	$(document).ready(function(){
		$("#date").datepicker({dateFormat : 'yy-mm-dd'});

		$("#date").change(function(){
			date = $("#date").val(); 
			location.href = '/products/inventory/date:'+ date;  
		});
		
	});
</script>
