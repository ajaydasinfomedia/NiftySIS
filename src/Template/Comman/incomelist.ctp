
<script>
		$(document).ready(function() {
		$('#examlist').DataTable({responsive: true});
		
		jQuery('body').on('click', '.viewincome', function() {
			
			var get_id=jQuery(this).attr('id');
				
			$.ajax({

					type:'POST',
					url:'<?php echo $this->Url->build(["controller"=>"Payment","action"=>"viewdataincome"]); ?>',
					data:{id:get_id},

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
					<?php
					if($role=='supportstaff')
					{ ?>
					  <li>
						<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Payment List'),array('controller'=>'Comman','action' => 'paymentlist'),array('escape' => false));?> 
					  </li>
					  <li>					  
						<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Payment'),array('controller'=>'Comman','action' => 'addpayment'),array('escape' => false));?>
					  </li>
					  <li class="active">
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
						<th><?php echo __('Student ID');?></th>
						<th><?php echo __('Roll No'); ?></th>
						<th><?php echo __('Student Name'); ?></th>
						<th><?php echo __('Amount'); ?></th>
						<th><?php echo __('Date')?></th>
						<th><?php echo __('Action')?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th><?php echo __('Student ID');?></th>
						<th><?php echo __('Roll No'); ?></th>
						<th><?php echo __('Student Name'); ?></th>
						<th><?php echo __('Amount'); ?></th>
						<th><?php echo __('Date')?></th>
						<th><?php echo __('Action')?></th>
					</tr>
				</tfoot>
				<tbody>

					<?php
					$stud_date = $this->Setting->getfieldname('date_format');
						foreach($income_data as $income_row):
						
							foreach ($user_data as $user_row ):
							
								if($income_row['supplier_name'] == $user_row['user_id']){
						?>
			
					<tr>
					<td><?php echo $this->Setting->get_studentID($user_row['user_id']); ?></td>
					<td><?php echo $user_row['roll_no']; ?></td>
					<td><?php echo $user_row['first_name']; ?></td>
					<Td>
					<?php
							
							$entry=$income_row['entry'];

							$am=json_decode($entry);

							$amount=array();
							foreach($am as $total){
									$amount[]=$total->amount;
							}

							$sum=0;
							if(!empty($amount))
							{
								for($i=0;$i<count($amount);$i++)
								{
									$sum=$sum+$amount[$i];
								}
							}
							echo $currency_symbol." ".$sum;

							 ?>	
					
					
					</td>
					
					
					<td><?php echo __(date($stud_date,strtotime($income_row['income_create_date']))); ?></td>
					
					<Td>
						<button type="button" id="<?php echo $income_row['income_id']; ?>" name="hid" data-toggle="modal" data-target="#myModal1" class="btn btn-default viewincome"><span class="fa fa-eye fa-lg"></span> <?php echo __('View Income'); ?> </button>
 
						<?php 
						echo 
						$this->Html->link(__('Edit'),array('action' => 'addincome',$this->Setting->my_simple_crypt($income_row['income_id'],'e')),array('class'=>'btn btnview btn-info')).
						"&nbsp;". 
						$this->Form->button( __('Delete'),['action'=>'#','url'=>$this->request->base.'/'.$this->name.'/deleteincome/'.$income_row['income_id'],'class'=>'btn btn-danger sa-warning']);
						
						?>
						</td>
				
				      
				</td>

				</tr>
				
					<?php 
					}
						endforeach;
							endforeach;
					
					?>

				</tbody>
				</table>
				
				
		
		</div>
	</div>
</div>
<!--  -->