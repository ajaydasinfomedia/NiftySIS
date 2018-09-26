<?php
use Cake\Routing\Router;

?>
<!--- start checkbox js ---->
<script>
	$(function(){

	
	$('#fee').click(function() {
		
			if($(".ch_pend").is(":checked")) {	

			swal({   
				title: "Are You Sure?",
				text: "Are you sure you want to delete this?",   
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#297FCA",   
				confirmButtonText: "Yes, delete!",
				cancelButtonText: "No, cancel it!",	
				closeOnConfirm: false,
				closeOnCancel: false
			}, function(isConfirm){
				if (isConfirm)
				{
					swal("Deleted!", "Your records has been deleted.", "success");		

		
					var get_id = $('.ch_pend:checked').map(function() {
					return this.attributes.dataid.textContent;
					}).get()
					get_id = JSON.stringify(get_id);
					data={f_id:get_id	};	


					jQuery.ajax({
					type:"POST",
					url:"<?php echo Router::url(array('controller'=>'Feepayment','action'=>'feemultidelete'));?>",
					data:data,
					async:false,
					success: function(response){
							location.reload();

					},
					error: function (e) {
					},
					beforeSend:function(){
					$(this).hide();
					},
					complete:function(e){
					console.log(e.responseText);
					
					}
					});	
				}
				else {	 
					swal("Cancelled", "Not removed!", "error"); 
				}
			});
			}
	  });

	});
</script>

<!--- checkbox js ---->
<script>
$(document).ready(function(){
    $('#select_all').on('click',function(){
        if(this.checked){
            $('.checkbox').each(function(){
                this.checked = true;
            });
        }else{
             $('.checkbox').each(function(){
                this.checked = false;
            });
        }
    });
    
    $('.checkbox').on('click',function(){
        if($('.checkbox:checked').length == $('.checkbox').length){
            $('#select_all').prop('checked',true);
        }else{
            $('#select_all').prop('checked',false);
        }
    });
});
</script>
<!--- end checkbox js ---->


<script>
	$(document).ready(function(){
        
        $('#examlist').DataTable({
				responsive: true,
				"order":[[0,"desc"]],
			});
        
		$('body').on('click','.paymodal',function(){
			payid=$(this).attr('id');
			$('#payid').attr('value',payid);
		});

		$('body').on('click','#addpayment',function(){
			var pid=0;
			var pamt=0;
			var pby=0;
			var total_amount=0;
			var net_amount=0;
			pid=$('#payid').val();
			pamt=$('#paymentamount').val();
			pby=$('#paymentby').val();
			total_amount=$('#total_amount'+pid).val();
			net_amount=$('#net_amount'+pid).val();
			
			var validNumber = new RegExp(/^\d*\.?\d*$/);
			
			if(pamt == '')
			{
				alert("Paid Amount Required");
				return false;
			}
			else
			{
				if (validNumber.test(pamt)) 
				{
					$.ajax({
		              type: 'POST',
		              url: '<?php echo $this->Url->build(["controller" => "Feepayment","action" => "addhistory"]);?>',
		              data : {
							paymentid:pid,
							paymentamt:pamt,
							paymentby:pby,
							nettotal:net_amount,
							totalamt:total_amount
							},
					  success: function (data)
					  {
							if(data == 'success')
								location.reload();
							else
								$('#addpayment').text('Add Payment');
					  },
					  beforeSend:function(){
							$('#addpayment').text('Loading...');
					  },
					  error: function(e) {
							 console.log(e);
					  },
					  complete:function(){
							 //window.location="http://192.168.1.23/ajay/school_management/feepayment/feelist";
							 location.reload();
					  }
					});
				}
				else
				{
					alert("Allow only numbers and decimal");
					return false;
				}
			}
		});

		$('body').on('click','.viewmodal',function(){
			payid=$(this).attr('id');
			$('#payid').attr('value',payid);
			vpid=$('#payid').val();
				$.ajax({
		              type: 'POST',
		              url: '<?php echo $this->Url->build(["controller" => "Feepayment","action" => "paymentview"]);?>',
		              data : {
		              		vpaymentid:vpid,

							},
						success: function (data)
		            {
								$('#modal-view').html(data);
		   				 },
						beforeSend:function(){
							$('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
						},
		                     error: function(e) {
		                     console.log(e);
		                 }

		        });



		});

	});
</script>

<?php
$currency = $this->Setting->getfieldname('currency_code');
$currency_symbol = $this->Setting->get_currency_symbole($currency);
$user_session_id=$this->request->session()->read('user_id');
$role=$this->Setting->get_user_role($user_session_id);
$heading = $this->Setting->getfieldname('school_name');
?>

<div class="modal fade " id="myModalpay" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header" >
				<a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
				<h4> <?php echo $heading;?> </h4>
			</div>
			<div class="modal-body" >
				<div class="row">
					<div class="form-group">
						<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label(__('Paid Amount '));?><span class="require-field">*</span></div>
						<div class="col-md-10 col-sm-10 col-xs-12">
							<input type="hidden" value="" name="" id="payid">
							<?php echo $this->Form->input('',array('name'=>'','id'=>'paymentamount','class'=>'form-control validate[required]','required'));?>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label(__('Payment By '));?><span class="require-field">*</span></div>
						<div class="col-md-10 col-sm-10 col-xs-12">
							<?php									
							$options=['Cash'=> __('Cash'),
							'Cheque'=> __('Cheque'),
							'Bank Tranfer'=> __('Bank Transfer'),
							];
							echo $this->Form->select('',$options,['class'=>'form-control select validate[required]','id'=>'paymentby']);
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<center>
					<button type="button" class="btn btn-default" class="btn btn-success" id="addpayment"> <?php echo __('Add Payment'); ?> </button>
					<button type="button" class="btn btn-default" data-dismiss="modal"> <?php echo __('Close'); ?> </button>
				</center>
			</div>
		</div>
	</div>
</div>

<div class="modal fade " id="myModalview" role="dialog">
	<div class="modal-dialog modal-md"  >
		<div class="modal-content">
			<div class="modal-header" >
			 <a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
			 <h4> <?php echo $heading; ?> </h4>
			</div>
			<div class="modal-body" id="modal-view"></div>
		</div>
	</div>
</div>

<div class="row schooltitle">
				<ul role="tablist" class="nav nav-tabs panel_tabs">
					  <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Fee Type List'),array('controller'=>'Feepayment','action' => 'feetypelist'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>

					   <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Fee Type'),array('controller'=>'Feepayment','action' => 'addfeetype'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>


					     <li class="active">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Fees List'),array('controller'=>'Feepayment','action' => 'feelist'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>

					     <li class="">

						<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Generate Invoice'),array('controller'=>'Feepayment','action' => 'invoice'),array('escape' => false));?>
					  </li>
				</ul>
</div>

<?php
if(isset($fees_data))
{
	if(!empty($fees_data->toArray()))
	{
	?>
<div class="panel-body">
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr><th><input type="checkbox" id="select_all" name="select_all" /></th>
						<th><?php echo __('Fee Type');?></th>
						<th><?php echo __('Student Name');?></th>
						<th><?php echo __('Student ID');?></th>
						<th><?php echo __('Class');?></th>
						<th><?php echo __('Payment Status');?></th>
						<th><?php echo __('Amount');?></th>
						<th><?php echo __('Due Amount');?></th>
						<th><?php echo __('Year');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
				</thead>
				<tfoot>
					<tr><th></th>
                       <th><?php echo __('Fee Type');?></th>
						<th><?php echo __('Student Name');?></th>
						<th><?php echo __('Student ID');?></th>
						<th><?php echo __('Class');?></th>
						<th><?php echo __('Payment Status');?></th>
						<th><?php echo __('Amount');?></th>
						<th><?php echo __('Due Amount');?></th>
						<th><?php echo __('Year');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
				</tfoot>
				<tbody>


					<?php
					
					foreach ($fees_data as $fees_info) :
						$pay_status='';
						if($fees_info['payment_status'] == 0){
							$pay_status = __('Not Paid');
						}else if($fees_info['payment_status'] == 1){
							$pay_status = __('Partially Paid');
						}else if($fees_info['payment_status'] == 2){
							$pay_status = __('Fully Paid');
						}

						$year=$fees_info['start_year'].' - '.$fees_info['end_year'];

					foreach($get_all_class as $class_info){

							if($class_info['class_id'] == $fees_info['class_id']){

					foreach($get_all_user as $user_info){

						if($fees_info['student_id'] == $user_info['user_id']){

						foreach($get_all_data_cat as $fee_type){

							if($fee_type['category_id'] == $fees_info['fees_id']){
								
							foreach($section_record as $section){
                                if($fees_info['section'] == $section['class_section_id']){	
					
							$net_amount = 0;
							$net_amount = ($fees_info['total_amount'] - $fees_info['fees_paid_amount']);
							
					?>
					
					<tr>
					
						<td>
						<p style='display:none;'><?php echo $fees_info['fees_pay_id'];?></p>
						<input type="checkbox" class="checkbox ch_pend" name="id[]" dataid="<?php echo $fees_info['fees_pay_id'];  ?>"></td>
						<td><?php echo $fee_type['category_type'];  ?></td>
						<td><?php echo $user_info['first_name'].' '.$user_info['last_name']; ?></td>
						<td><?php echo $this->Setting->get_studentID($user_info['user_id']); ?></td>
						<td><?php echo $class_info['class_name']; ?></td>
						<td><label class="btn btn-success btn-xs"><?php echo $pay_status;?></label></td>
						<td><?php echo $currency_symbol." ".$fees_info['total_amount']; ?><input type="hidden" value="<?php echo $fees_info['total_amount'];?>" id="total_amount<?php echo $fees_info['fees_pay_id'];?>"></td>
						<td><?php 
						$due_amt = 0;
						$due_amt = (int)$fees_info['total_amount']-(int)$fees_info['fees_paid_amount'];
						echo $currency_symbol." ".$due_amt; ?><input type="hidden" value="<?php echo $net_amount;?>" id="net_amount<?php echo $fees_info['fees_pay_id'];?>"></td>
						<td><?php echo $year; ?></td>
						<td>
							<?php
							if($fees_info['payment_status'] != 2)
							{
							?>
							<button type="button" id="<?php echo $fees_info['fees_pay_id'];?>" data-toggle="modal" data-target="#myModalpay" class="btn btn-default paymodal" style=""> <?php echo __('Pay'); ?> </button>
							<?php
							}
							?>
							<button type="button" id="<?php echo $fees_info['fees_pay_id'];?>" data-toggle="modal" data-target="#myModalview" class="btn btn-default viewmodal" style=""> <?php echo __('View'); ?> </button>
						
							<?php 
							echo $this->Html->link(__('Edit'),array('controller'=>'Feepayment','action' => 'invoice',$this->Setting->my_simple_crypt($fees_info['fees_pay_id'],'e')),array('class'=>'btn btnview btn-info'))	
							."&nbsp;".
							$this->Form->button( __('Delete'),['action'=>'#','url'=>$this->request->base.'/'.$this->name.'/paymentdelete/'.$fees_info['fees_pay_id'],'class'=>'btn btn-danger sa-warning']);				 
							?></td>

					</tr>
					<?php
								}
							}
							}
							}
						}
					}
					}
				}
				endforeach;
				?>
				</tbody>
				</table>
				<tr>
					<td><button type="button" style="float:left" class="btn btn-danger" id="fee"> <?php echo __('Delete Selected'); ?> </button></td>
					<td>
					<?php
					/*
					<form method="post" action="<?php echo $this->request->base;?>/feepayment/feepaymentalert">
					<button type="submit" style="float:left;margin-left:10px;" class="btn btn-warning" name="fees_notification"> <?php echo __('Fees Alert'); ?> </button>
					</form>
					*/
					?>
					</td>
				</tr>
		</div>
	</div>
</div>
<?php
	}
	else{
		?>
		<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Fee data Available');?></h4></div>
<?php		
	}
}
?>