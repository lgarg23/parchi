<?php
	echo $this->Form->create('Product', array('action' => 'print_p'));
?>
<?php 
	echo $this->Form->input("Parchi.party_name",
									array(
								 		'class' => 'party ContractField',
										'label' => false,
										'style' => 'width:209px;height:10px;',
										'placeholder' => 'Party Name',
										'tabindex' => 1
									)
								);
?>

<table style="width: 70%;" id="lineItems" cellpadding="0"
	cellspacing="0">
	<thead>
		<tr>
			<td><?php echo __('Description'); ?></td>
			<td><?php echo __('Quantity'); ?></td>
			<td><?php echo __('Unit'); ?></td>
			<td><?php echo __('Unit Price'); ?></td>
			<td><?php echo __('Total'); ?></td>
			<td><?php echo __(' '); ?></td>
		</tr>
	</thead>
	<tr class="addMoreItems">
		<td class='formRow'>
			<?php 
				echo $this->Form->input("Retail.0.product_id",
												array(
											 		'class' => 'services ContractField',
													'label' => false,
													'style' => 'width:209px',
													'type' => 'text',
													'tabindex' => 2
											)
										);
											
				echo $this->Form->input("Retail.0.date", array('type' => 'hidden', 'value' => date('Y-m-d')));
			?>
		</td>
		<td>
			<?php 
				echo $this->Form->input("Retail.0.quantity",
												array(
													'class' => 'quantity ContractField',
													'label' => false,
													'type' => 'text',
													'style' => 'width:120px',
													'value' => '',
													'tabindex' => 3
											)
										);
				echo $this->Form->input("Retail.0.order",
												array(
													'class' => 'order ContractField',
													'type' => 'hidden',
													'value' => $order
											)
										);
			?>
		</td>
		<td>
			<?php 
				echo $this->Form->input("Retail.0.unit",
												array(
													'class' => 'unit ContractField',
													'label' => false,
													'type' => 'text',
													'style' => 'width:120px',
													'value' => '',
													'tabindex' => 4
											)
										);
			?>
		</td>
		<td>
			<?php 
				echo $this->Form->input("Retail.0.unit_price",
												array(
													'class' => 'unit_price ContractField',
													'label' => false,
													'type' => 'text',
													'style' => 'width:120px',
													'value' => '',
													'tabindex' => 5
											)
										);
			?>
		</td>
		<td>
			<?php 
				echo $this->Form->input("Retail.0.total",
												array(
													'class' => 'total ContractField',
													'label' => false,
													'type' => 'text',
													'style' => 'width:120px',
													'value' => '',
													'tabindex' => 6
											)
										);
			?>
		</td>
		<td valign="bottom" style="text-align: left;">
			<p style="margin: 24px 0 0 4px; width: 50px">
				<a href "#" id="remove0" class="itemCloneRemove"><?php echo __('Remove') ?></a>
			</p>
		</td>
	</tr>
</table>
<div class="formfeilds">
<p>
	<?php 
		echo $this->Html->link(__('+ Add More', true), '#', array('id' => 'addMoreItems_link'));
	?>
</p>
</div>
<?php echo $this->Form->submit('Submit'); ?>
<script>
	$(document).ready(function(){

		var parties_arr = [];
		var parties = <?php echo json_encode($parties); ?>;
		$.each(parties, function(key, val){
			parties_arr.push(val);
		});

		$( "#ParchiPartyName" ).autocomplete({
	      	source : parties_arr
		});

		var products_arr = [];
		var products = <?php echo json_encode($products); ?>;
		$.each(products, function(key, val){
			products_arr.push(val);
		});

		$("#Retail0ProductId").autocomplete({
	      	source : products_arr
		});
		
		$('#addMoreItems_link').FormModifier({
			cloneElem:'.addMoreItems', 
			cloneRow:true, 
			isParent:true,
			labelPrefix:null, 
			labelDiv:'', 
			child:'.formRow', 
			formid:'ProductForm',
			canDeleteLast:false, 
			appendTo:'e_result'
		});
		$('#addMoreItems_link').live('click', function(e) {
			e.preventDefault();
			val = $('.order').val();
			$('#addMoreItems_link').data('FormModifier').appendRow();
			$('.order').val(val);
		});

		$('.itemCloneRemove').live('click', function(e){
			e.preventDefault();
			if ($('.itemCloneRemove').length > 1) {
				$(this).closest("tr").remove();
			}
			
			//Reshuffling names so that no service is overwritten
			//When a new row is added form modifier counts number of rows and assign name acc to that
			//but if we have removed some row then names will clash with each other and 1 service is omitted while saving
			k = 0;
			$("table#lineItems tbody tr").each(function(index) {
				newElem = $(this);
				newElem.find('input, select, textarea').each(function() {
					var name = $(this).attr('name');
		   			var newName = name.replace(/\d+/, k);
		   			$(this).attr('name', newName);
				});
				k++;
			});
		});

		var products_unit = <?php echo json_encode($products_unit); ?>;
		var products_price = <?php echo json_encode($products_price); ?>;

		$('.quantity').live('keyup', function(e){
		    if(e.which == 9){
		    	elem = $(this).parent().parent().parent();
		    	val = elem.find('.services').val();
				
				if(products_unit[val]){
					vals_unit = products_unit[val];
				}else{
					vals_unit = '';
				}	
				
				elem.find('.unit').val(vals_unit);
				if(products_price[val]){
					elem.find('.unit_price').val(products_price[val]);	
				}else{
					elem.find('.unit_price').val(0.0);
				}
				
				quant = elem.find('.quantity').val();
				if(quant == ''){
					quant = 0;
				}
				tot = elem.find('.unit_price').val() * quant;
				tot = tot.toFixed(2);
				elem.find('.total').val(tot);    
			}
		});
		
		$('.services').live('keyup', function(e){
		    if(e.which == 13){
		    	val = $(this).val();
				elem = $(this).parent().parent().parent();
				vals_unit = products_unit[val];
				elem.find('.unit').val(vals_unit);
				elem.find('.unit_price').val(products_price[val]);
				quant = elem.find('.quantity').val();
				if(quant == ''){
					quant = 0;
				}
				tot = products_price[val] * quant;
				tot = tot.toFixed(2);
				elem.find('.total').val(tot);    
			}
		});

		$('.total').live('keydown', function(e){
			e.preventDefault();
			if(e.which == 13){
				val = $('.order').val();
				$('#addMoreItems_link').data('FormModifier').appendRow();
				$('.order').val(val);
				
				k = 1;
				check = 2;
				$("table#lineItems tbody tr").each(function(index) {
					newElem = $(this);
					check = k + 1;
					newElem.find('input[type="text"]').each(function() {
						var tabIndex = $(this).attr('tabindex');
						k++;
			   			$(this).attr('tabindex', k);
					});
					newElem.find('.services[tabindex="'+ check +'"]').autocomplete({
				      	source : products_arr
					});
				});
				
				newElem.find('.services[tabindex="'+ check +'"]').focus();
				return false; 
			}
		});
		
		$('.quantity').live('change', function(){
			elem = $(this).parent().parent().parent();
			tot = elem.find('.unit_price').val() * $(this).val();
			tot = tot.toFixed(2);
			elem.find('.total').val(tot);
		});

		$('.unit_price').live('change', function(){
			elem = $(this).parent().parent().parent();
			tot = elem.find('.unit_price').val() * $(this).val();
			tot = tot.toFixed(2);
			elem.find('.total').val(tot);
		});
		
	});
</script>