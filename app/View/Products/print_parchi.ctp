<?php
	echo $this->fetch('script');
	echo $this->Html->script('jquery.min');
?>
<table style = "max-width:40%;font-size:10px;width:40%;text-align:center;margin-left: 210px;">
	<tr>
		<td colspan = "5" style = "text-align:center;"><b>Order</b></td>
	</tr>
	<tr>
		<td colspan = "2"><b>Party Name</b></td>
		<td colspan = "3"><?php echo $data['Parchi']['party_name']; ?></td>
	</tr>
	<tr>
		<td colspan = "2"><b>Voucher No.</b></td>
		<td colspan = "3"><?php echo $voucher_number;?></td>
	</tr>
	<tr>
		<td colspan = "2"><b>Date</b></td>
		<td colspan = "3"><?php echo date('d-m-Y'); ?></td>
	</tr>
	 
	<tr style = "border-bottom: 1px solid;">
		<td style = "width:7%;"><b>Qty.</b></td>
		<td style = "width:7%;"><b>Unit</b></td>
		<td style = "text-align:center;width:12%;"><b>Item</b></td>
		<td style = "width:7%;"><b>Unit Price</b></td>
		<td style = "width:7%;"><b>Total</b></td>
	</tr>
	
	<?php
		$total = 0; 
		foreach($data['Retail'] as $key => $val){ ?>
			<tr>
				<td style = "width:7%;"><?php echo $val['quantity']; ?></td>
				<td style = "width:7%;"><?php echo $val['unit']; ?></td>
				<td style = "text-align:center;width:12%;"><?php echo wordwrap($val['product_id'], 15, " ", true); ?></td>
				<td style = "width:7%;"><?php echo $val['unit_price']; ?></td>
				<td style = "width:7%;"><?php echo number_format($val['quantity'] * $val['unit_price'], 2); 
					$total = $total + $val['quantity'] * $val['unit_price'];
				?></td>
			</tr>
	<?php } ?>
	<tr>
		<td colspan = "4" style = "text-align:center;"><?php echo __('Total'); ?></td>
		<td><?php echo number_format($total, 2); ?></td>
	</tr>
	<tr>
		<td colspan = "5" style = "text-align:center;"><?php echo __(' '); ?></td>
	</tr>
	<tr>
		<td colspan = "5" style = "text-align:center;"><?php echo __(' '); ?></td>
	</tr>
	<tr>
		<td colspan = "2" style = "text-align:left;"><?php echo __('VAT Extra'); ?></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td colspan = "2" style = "text-align:left;"></td>
		<td></td>
		<td style = "text-align:right;"><?php echo __('SIGN'); ?></td>
		<td></td>
	</tr>
</table>
<style>
	table tr{
		background : none !important;
		height : 2px;
	}
	table{
		border : none !important;
	}
	table tr td{
		border-bottom: none;
		padding:0px; 
		margin:0px;
	}
</style>
<style type="text\css" media="print">
#Footer, #Header{
  display: none;
}
</style>
<script>
	$(document).ready(function(){
		
		window.print();
		window.location.href = "/";		
	});
	
</script>