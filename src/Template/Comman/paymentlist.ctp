<script>
$(document).ready(function() 
{
	$('#examlist').DataTable({responsive: true});
	
	jQuery('body').on('click', '.viewpayment', function() 
	{		
		var pay_id=$(this).attr('id');
	
		$.ajax({

			type:'POST',
			url:'<?php echo $this->Url->build(["controller"=>"Payment","action"=>"viewdatapayment"]); ?>',
			data:{id:pay_id},

			success:function(getdata){
				$(".modal-body").html(getdata);
			},

			beforeSend:function(){
				$(".modal-body").html("<center><h2 class=text-muted><b>Loading...</b></h2></center>");
			},

			error:function(e){
				alert("An error ocurred:"+e.responseText);
				console.log(e);
			},
		});	
	});	
});		
</script>
<script>
function PrintElem(elem)
{
		Popup($(elem).html());
}

function Popup(data) 
{
	var mywindow = window.open('', 'Print Expense Invoice', 'height=600,width=800');
	mywindow.document.write('<html><head><title>my div</title>');
	/*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
	mywindow.document.write('</head><body >');
	mywindow.document.write(data);
	mywindow.document.write('</body></html>');

	mywindow.document.close(); // necessary for IE >= 10
	mywindow.focus(); // necessary for IE >= 10

	mywindow.print();
	mywindow.close();

	return true;
}
</script>
<?php
$currency = $this->Setting->getfieldname('currency_code');
$currency_symbol = $this->Setting->get_currency_symbole($currency);
?>

	<?php $heading = $this->Setting->getfieldname('school_name'); ?>
	<div class="modal fade " id="myModal1" role="dialog">
    <div class="modal-dialog modal-lg"  >

      <div class="modal-content">
        <div class="modal-header" >
          <a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
			<h4> <?php echo $heading; ?> </h4>
        </div>
        <div class="modal-body" >
		
        </div>
		
        <div class="modal-footer">
		 
          
		
		</div>
			 
        </div>
      </div>
      
    </div>

				<!-- -->
				
<div class="row schooltitle">			
				<ul role="tablist" class="nav nav-tabs panel_tabs">
					 <?php if($role=='student' || $role=='parent')
					{ ?> 
					  <li class="active">
						<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Payment List'),array('controller'=>'Comman','action' => 'paymentlist'),array('escape' => false));?> 
					  </li>
					 <?php } 
					if($role=='supportstaff')
					{ ?>
					  <li class="active">
						<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Payment List'),array('controller'=>'Comman','action' => 'paymentlist'),array('escape' => false));?> 
					  </li>
					  <li>					  
						<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Payment'),array('controller'=>'Comman','action' => 'addpayment'),array('escape' => false));?>
					  </li>
					  <li>
						<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Income List'),array('controller'=>'Comman','action' => 'incomelist'),array('escape' => false));?>	  
					  </li>
					  <li>
						<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Income'),array('controller'=>'Comman','action' => 'addincome'),array('escape' => false));?> 
					  </li>
					  <li>
						<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Expense List'),array('controller'=>'Comman','action' => 'expenselist'),array('escape' => false));?>
					  </li>
					  <li>
						<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Expense'),array('controller'=>'Comman','action' => 'addexpense'),array('escape' => false));?>
					  </li>
					 <?php } ?>
				</ul>
</div>
<div class="panel-body">	
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th> <?php echo __('Student Name'); ?> </th>
						<th> <?php echo __('Student ID');?></th>
						<th> <?php echo __('Roll No'); ?> </th>
						<th> <?php echo __('Class'); ?> </th>
						<th> <?php echo __('Title'); ?> </th>
						<th> <?php echo __('Amount'); ?> </th>
						<th> <?php echo __('Status'); ?> </th>
						<th> <?php echo __('Date'); ?> </th>
						<?php
						if($role=='supportstaff')
						{ ?>
						<th><?php echo __('Action');?></th>
					<?php	} ?>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th> <?php echo __('Student Name'); ?> </th>
						<th> <?php echo __('Student ID');?></th>
						<th> <?php echo __('Roll No'); ?> </th>
						<th> <?php echo __('Class'); ?> </th>
						<th> <?php echo __('Title'); ?> </th>
						<th> <?php echo __('Amount'); ?> </th>
						<th> <?php echo __('Status'); ?> </th>
						<th> <?php echo __('Date'); ?> </th>
						<?php
						if($role=='supportstaff')
						{ ?>
						<th><?php echo __('Action');?></th>
					<?php	} ?>
					</tr>
				</tfoot>
				<tbody>

			<?php
			$stud_date = $this->Setting->getfieldname('date_format');
			foreach($payment as $payment_data):
					{
						foreach($user as $userdata):
						{
							if($userdata['user_id'] == $payment_data['user_id'])
							{
								if($role=='student')
								{
									if($userdata['user_id'] == $stud_id)
									{
									?>				
									<tr>								
										<td><?php echo $userdata['first_name'].'  '.$userdata['last_name'];?></td>
										<Td><?php echo $this->Setting->get_studentID($userdata['user_id']); ?></td>
										<Td><?php echo $userdata['roll_no']; ?></td>
										<td><?php echo $payment_data['c_name']; ?></td>
										<Td><?php echo $payment_data['payment_title'];?></td>
										<td><?php echo $currency_symbol." ".$payment_data['amount']; ?></td>
										<td><?php echo $payment_data['payment_status']; ?></td>
										<td><?php echo date($stud_date,strtotime($payment_data['date'])); ?></td>				
									</tr>
									<?php
									}
								}
								if($role=='supportstaff')
								{ ?>
									<tr>								
										<td><?php echo $userdata['first_name'].'  '.$userdata['last_name'];?></td>
										<Td><?php echo $this->Setting->get_studentID($userdata['user_id']); ?></td>
										<Td><?php echo $userdata['roll_no']; ?></td>
										<td><?php echo $payment_data['c_name']; ?></td>
										<Td><?php echo $payment_data['payment_title'];?></td>
										<td><?php echo $currency_symbol." ".$payment_data['amount']; ?></td>
										<td><?php echo $payment_data['payment_status']; ?></td>
										<td><?php echo date($stud_date,strtotime($payment_data['date'])); ?></td>				
										<td>
										<button type="button" id="<?php echo $payment_data['payment_id']; ?>" data-toggle="modal" data-target="#myModal1" class="btn btn-default viewpayment"><span class="fa fa-eye fa-lg"></span> <?php echo __('View Income'); ?> </button>
											<?php 
											echo $this->Html->link(__('Edit'),array('controller'=>'Comman','action' => 'addpayment',$this->Setting->my_simple_crypt($payment_data['payment_id'],'e')),array('class'=>'btn btnview btn-info'))
											."&nbsp;".
											$this->Form->button( __('Delete'),['action'=>'#','url'=>$this->request->base.'/'.$this->name.'/paymentdelete/'.$payment_data['payment_id'],'class'=>'btn btn-danger sa-warning']);
											?>
											</td>
										</td>
									</tr>
						<?php	}
								if($role=='parent')
								{
									if(isset($child_id))
									{
										foreach($child_id as $c_id)
										{
											if($userdata['user_id'] == $c_id)
											{
											?>				
											<tr>								
												<td><?php echo $userdata['first_name'].'  '.$userdata['last_name'];?></td>
												<Td><?php echo $this->Setting->get_studentID($userdata['user_id']); ?></td>
												<Td><?php echo $userdata['roll_no']; ?></td>
												<td><?php echo $payment_data['c_name']; ?></td>
												<Td><?php echo $payment_data['payment_title'];?></td>
												<td><?php echo $currency_symbol." ".$payment_data['amount']; ?></td>
												<td><?php echo $payment_data['payment_status']; ?></td>
												<td><?php echo date($stud_date,strtotime($payment_data['date'])); ?></td>				
											</tr>
											<?php
											} 
										}
									}
								}
						
						}}endforeach;
					
					}endforeach;
					?>
				
				</tbody>
				</table>
		</div>
	</div>
</div>