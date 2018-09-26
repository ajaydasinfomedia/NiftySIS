<script>	
function PrintElem(elem)
{
	Popup($(elem).html());
}

function Popup(data) 
{
	var mywindow = window.open('', 'Print Expense Invoice', 'height=600,width=800');
   
	mywindow.document.write(data);
   

	mywindow.document.close();
	mywindow.focus();

	mywindow.print();
	mywindow.close();

	return true;
}	
</script>

<?php
	$admin_user=$admin_info['first_name'].' '.$admin_info['last_name'];
	$stud_date = $this->Setting->getfieldname('date_format');
	$heading = $this->Setting->getfieldname('school_name');
?>
<div class="print_show">
	<table class="table" width="100%" style="border:1px solid #ddd">
		<tr>
		<?php $logo = isset($logo)?$logo:'school-logo.jpg';?>
				<Td><img src="<?php echo $this->request->base;?>/img/<?php echo $logo;?>"></td>
				<td align="right"><b> <?php echo __('Issue Date:'); ?> </b><?php echo __(date($stud_date,strtotime($expense_data['income_create_date']))); ?>
					<br>
					<b> <?php echo __('Status:'); ?> </b><?php echo $expense_data['payment_status'];?>
				</td>
		</tr>
		<tr>
			<td><b><h4> <?php echo __('Payment To'); ?> </h4></b>
				<div><?php echo $heading;?> <br>
				<?php echo $address; ?><br>
				<?php echo $phone; ?>
			</div>
			</td>
			<td align="right"><b><h4> <?php echo __('Bill to'); ?> </h4></b>
				<div>
				<?php echo $expense_data['supplier_name']; ?><br>
				
				</div>
			</td>
		</tr>
	</table>

	<caption><h4><b><?php echo __('Invoice Entries');?></b></h4></caption>
		<table class="table table-bordered" style="border:0.2px solid #ddd" width="100%" >
			
			<tr>
				<th class="text-center" align="center" style="border:1px solid #ddd"><?php echo __('#')?></th>
				<th class="text-center" align="center" style="border:1px solid #ddd"><?php echo __('Date')?></th>
				<th class="text-center" align="center" style="border:1px solid #ddd"><?php echo __('Entry');?></th>
				<th class="text-center" align="center" style="border:1px solid #ddd"><?php echo __('Price')?></th>
				<th class="text-center" align="center" style="border:1px solid #ddd"><?php echo __('Username');?></th>
			</tr>

			<?php
				
				$num=1;
				$total_amount=0;
				$entry=$expense_data['entry'];
		
				
				$am=json_decode($entry);
				
				$amount=array();
				
				foreach($am as $total){
					
					
					$total_amount=$total_amount + $total->amount;
					
			
			?>
		

			<tr>
					<td class="text-center" align="center" style="border:1px solid #ddd"><?php echo $num;?></td>
					<td class="text-center" align="center" style="border:1px solid #ddd"><?php echo date($stud_date,strtotime($expense_data['income_create_date']));?></td>
					<td class="text-center" align="center" style="border:1px solid #ddd"><?php echo $total->entry; ?></td>
					<td class="text-center" align="center" style="border:1px solid #ddd"><?php echo $total->amount;?></td>
					<td class="text-center" align="center" style="border:1px solid #ddd"><?php echo $admin_user; ?></td>
			</tr>

				<?php
					
					$num++;
					}
				?>

		</table>

		<table class="table" style="border:1px solid #ddd"  width="100%">
			<tr>
				<td colspan="2" class="text-right" align="right"> <?php echo __('Grand Total:'); ?> </td>
				<td colspan="2" class="text-center" align="center"><?php echo $total_amount; ?></td>
			
			</tr>
	
		</table>

</div>



<center>
 <button type="button" class="btn btn-default printbtn" onclick="PrintElem('.print_show')" > <?php echo __('Print'); ?> </button> 
 <?php echo $this->Html->link('PDF',array('action' => 'expensepdf',$expense_data['income_id']),array('class'=>'btn btnview btn-info','target'=>'top')); ?>
 
</center>