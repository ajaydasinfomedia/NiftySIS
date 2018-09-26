<script>
	$(document).ready(function(){
        
        $('#examlist').DataTable({responsive: true});
        
		$('body').on('click','.paymodal',function(){
			payid=$(this).attr('id');
			total_amount=$('#total_amount'+payid).val();
			net_amount=$('#net_amount'+payid).val();
			$('#payid').attr('value',payid);
			$('#total_amount').attr('value',total_amount);
			$('#net_amount').attr('value',net_amount);
			
		});

		/* $('body').on('click','#addpayment',function(){

			pid=$('#payid').val();
			pamt=$('#paymentamount').val();
			pby=$('#paymentby').val();
			net=$('#netamt').val();

			$.ajax({
		              type: 'POST',
		              url: '<?php echo $this->Url->build(["controller" => "Feepayment","action" => "addhistory"]);?>',
		              data : {paymentid:pid,
							 paymentamt:pamt,
							paymentby:pby,
							nettotal:net
													},
		              success: function (data)
		                  {
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


		});
 */
		$('body').on('click', '.viewmodal', function() {
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
								$('#myModalview .modal-body').html(data);
		   				 },
						beforeSend:function(){
							$('#myModalview .modal-body').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
						},
		                     error: function(e) {
		                     console.log(e);
		                 }

		        });



		});

	});
</script>

	<?php $heading = $this->Setting->getfieldname('school_name');?>

	<div class="modal fade " id="myModalpay" role="dialog">
    <div class="modal-dialog modal-md"  >

      <div class="modal-content">
	  <?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data','style'=>'padding-top:0px;'],['url'=>['action'=>'']]);?>
        <div class="modal-header" >
			<a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
			<h4> <?php echo $heading; ?> </h4>
        </div>
        <div class="modal-body" style="padding-top:20px;">	
			<div class="row">
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label(__('Paid Amount '));?><span class="require-field">*</span></div>
					<div class="col-md-10 col-sm-10 col-xs-12">
						<input type="hidden" value="" name="payid" id="payid">
						<input type="hidden" value="" name="total_amount" id="total_amount">
						<input type="hidden" value="" name="net_amount" id="net_amount">
						<?php echo $this->Form->input('',array('name'=>'paymentamount','id'=>'paymentamount','class'=>'form-control validate[required]'));?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label(__('Payment By '));?><span class="require-field">*</span></div>
					<div class="col-md-10 col-sm-10 col-xs-12">
					<?php
						$options=['Paypal'=> __('Paypal')];
						echo $this->Form->select('',$options,['class'=>'form-control select validate[required]','id'=>'paymentby','name'=>'paymentby']);
					?>
					</div>
				</div>
			</div>	
        </div>
        <div class="modal-footer">

          <center>
			<button type="submit" class="btn btn-default" class="btn btn-success" id="addpayment"> <?php echo __('Add Payment'); ?> </button>
          	<button type="button" class="btn btn-default" data-dismiss="modal"> <?php echo __('Close'); ?> </button></center>
		</div>
		<?php $this->Form->end(); ?>
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
					<?php if($role=='student')
					{ ?>
					<li class="active">
						<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Fees List'),array('controller'=>'Comman','action' => 'feelist'),array('escape' => false));?>					
					</li>
			  <?php } 
					if($role=='supportstaff')
					{ ?>
					<li class="active">
						<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Fees List'),array('controller'=>'Comman','action' => 'feelist'),array('escape' => false));?>					
					</li>
			  <?php }
			  		if($role=='parent')
					{ ?>
					<li class="active">
						<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Fees List'),array('controller'=>'Comman','action' => 'feelist'),array('escape' => false));?>					
					</li>
			  <?php }
			  	 ?>
				</ul>
</div>

<?php
$currency = $this->Setting->getfieldname('currency_code');
$currency_symbol = $this->Setting->get_currency_symbole($currency);

?>

<div class="panel-body">
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr>
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
					<tr>
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
							if($role=='student')
							{
							if($fees_info['student_id'] == $stud_id){
						foreach($get_all_data_cat as $fee_type){

							if($fee_type['category_id'] == $fees_info['fees_id']){

							$net_amount = 0;
							$net_amount = ($fees_info['total_amount'] - $fees_info['fees_paid_amount']);
					?>

					<tr>
						<td><?php echo $fee_type['category_type'];  ?></td>
						<td><?php echo $user_info['first_name'].' '.$user_info['last_name']; ?></td>
						<td><?php echo $this->Setting->get_studentID($user_info['user_id']); ?></td>
						<td><?php echo $class_info['class_name']; ?></td>
						<td><label class="btn btn-success btn-xs"><?php echo $pay_status;?></label></td>
						<td><?php echo $currency_symbol." ".$fees_info['total_amount']; ?><input type="hidden" value="<?php echo $fees_info['total_amount'];?>" id="total_amount<?php echo $fees_info['fees_pay_id'];?>"></td>
						<td><?php echo $currency_symbol." ".$net_amount; ?><input type="hidden" value="<?php echo $net_amount;?>" id="net_amount<?php echo $fees_info['fees_pay_id'];?>"></td>
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
						</td>
					</tr>
					<?php
								}
							}
						}}
						if($role=='supportstaff')
						{

						foreach($get_all_data_cat as $fee_type){

							if($fee_type['category_id'] == $fees_info['fees_id']){

							$net_amount = 0;
							$net_amount = ($fees_info['total_amount'] - $fees_info['fees_paid_amount']);
					?>

					<tr>
						<td><?php echo $fee_type['category_type'];  ?></td>
						<td><?php echo $user_info['first_name'].' '.$user_info['last_name']; ?></td>
						<td><?php echo $this->Setting->get_studentID($user_info['user_id']); ?></td>
						<td><?php echo $class_info['class_name']; ?></td>
						<td><label class="btn btn-success btn-xs"><?php echo $pay_status;?></label></td>
						<td><?php echo $currency_symbol." ".$fees_info['total_amount']; ?><input type="hidden" value="<?php echo $fees_info['total_amount'];?>" id="total_amount<?php echo $fees_info['fees_pay_id'];?>"></td>
						<td><?php echo $currency_symbol." ".$net_amount; ?><input type="hidden" value="<?php echo $net_amount;?>" id="net_amount<?php echo $fees_info['fees_pay_id'];?>"></td>
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
						</td>
					</tr>
					
					<?php
								}
							}
						}			
					if($role=='parent' && isset($child_id))
						{ 
						foreach($child_id as $c_id)
						{
						if($fees_info['student_id'] == $c_id){
						foreach($get_all_data_cat as $fee_type){

							if($fee_type['category_id'] == $fees_info['fees_id']){

							$net_amount = 0;
							$net_amount = ($fees_info['total_amount'] - $fees_info['fees_paid_amount']);
					?>

					<tr>
						<td><?php echo $fee_type['category_type'];  ?></td>
						<td><?php echo $user_info['first_name'].' '.$user_info['last_name']; ?></td>
						<td><?php echo $this->Setting->get_studentID($user_info['user_id']); ?></td>
						<td><?php echo $class_info['class_name']; ?></td>
						<td><label class="btn btn-success btn-xs"><?php echo $pay_status;?></label></td>
						<td><?php echo $currency_symbol." ".$fees_info['total_amount']; ?><input type="hidden" value="<?php echo $fees_info['total_amount'];?>" id="total_amount<?php echo $fees_info['fees_pay_id'];?>"></td>
						<td><?php echo $currency_symbol." ".$net_amount; ?><input type="hidden" value="<?php echo $net_amount;?>" id="net_amount<?php echo $fees_info['fees_pay_id'];?>"></td>
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
						</td>
					</tr>
					
					<?php
								}
							}
						}
						} }
						
					} }
					}
				}
				endforeach;
				?>
				</tbody>
				</table>
		</div>
	</div>
</div>