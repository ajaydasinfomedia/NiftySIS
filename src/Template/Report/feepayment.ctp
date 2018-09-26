<script>

$(document).ready(function(){
  $('#examlist').DataTable({responsive: true});
$('#class_id').change(function(){
   var cl_id=$(this).val();
		 $.ajax({

				type:'POST',
			 url:'<?php echo $this->Url->build(["controller"=>"Feepayment","action"=>"showfeetype"])?>',
			 data:{id:cl_id},

			 success:function(getdata){
				 $("#resultclass").html(getdata);
			 },

			 error:function(){
				 alert('An Error Occured:'+e.responseText);
				 console.log();
			 }
		 });

 });
 $('body').on('click', '.viewmodal', function() {	
			payid=$(this).attr('id');
			$('#payid').attr('value',payid);
			vpid=$('#payid').val();
	
				$.ajax({
		              type: 'POST',
		              url: '<?php echo $this->Url->build(["controller" => "Feepayment","action" => "paymentview"]);?>',
		              data : {
		              		vpaymentid:payid,

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
$heading = $this->Setting->getfieldname('school_name');
?>
 <div class="modal fade " id="myModalview" role="dialog">
    <div class="modal-dialog modal-md"  >

      <div class="modal-content">
        <div class="modal-header" >
         <a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
		 <h4><?php echo $heading;?></h4>
        </div>
        <div class="modal-body" id="modal-view">


        </div>
        <div class="modal-footer">

          <center><button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close');?></button></center>
		</div>

        </div>
      </div>

    </div>

<div class="row">
				<ul role="tablist" class="nav nav-tabs panel_tabs">

                       <li>

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-bar-chart fa-lg')) . __('Student Failed Report'),array('controller'=>'report','action' => 'failed'),array('escape' => false));
						?>


					  </li>

					  <li>
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-bar-chart fa-lg')) . __('Attendance Report'),array('controller'=>'report','action' => 'attendance'),array('escape' => false));
						?>
					  </li>

					    <li>
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-bar-chart fa-lg')) . __('Teacher Performance Report'),array('controller'=>'report','action' => 'teacher'),array('escape' => false));
						?>
					  </li>

					    <li class="active">
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-bar-chart fa-lg')) . __('Fee Payment Report'),array('controller'=>'report','action' => 'feepayment'),array('escape' => false));
						?>
					  </li>
						
					<li>
					  <?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-bar-chart fa-lg')) . __('Result Report'),array('controller'=>'report','action' => 'result'),array('escape' => false));
						?>
					  </li>

				</ul>
</div>

<div class="row">
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addexam']]);?>

					<div class="col-md-2 col-sm-2 col-xs-12">
						<div class="form-group">
							<label><?php echo __('Class Name'); ?> <span class="require-field">*</span></label>
							<select class="form-control validate[required]" name="class_id" id="class_id">
								<option value=""><?php echo __('---Select Class---');?></option>
								<?php
								foreach($class_data as $class_info):
									?>
										<option value="<?php echo $class_info['class_id'];?>"><?php echo $class_info['class_name']?></option>
									<?php
								endforeach;
								?>

							</select>
						</div>
					</div>

					<div class="col-md-2 col-sm-2 col-xs-12">
						<div class="form-group">
							<label><?php echo __('Fee Type'); ?> <span class="require-field">*</span></label>
							<div id="resultclass">
							<select class="form-control validate[required]" name="fees_id">
								<option value=""><?php echo __('---Select Fee Type---'); ?></option>
							</select>
							</div>
						</div>
					</div>

					<div class="col-md-2 col-sm-2 col-xs-12">
						<div class="form-group">
							<label><?php echo __('Payment Status'); ?> <span class="require-field">*</span></label>
							<select class="form-control validate[required]" name="payment_status">
								<option value=""><?php echo __('---Select Status---'); ?></option>
								<option value="0"><?php echo __('Not Paid'); ?></option>
								<option value="1"><?php echo __('Partially Paid'); ?></option>
								<option value="2"><?php echo __('Fully Paid'); ?></option>
							</select>
						</div>
					</div>

					<div class="col-md-2 col-sm-2 col-xs-12">
						<div class="form-group">
							<label><?php echo __('Start Year'); ?><span class="require-field">*</span></label>
							<select class="form-control validate[required]" name="start_year" id="start_year">
								<option value=""><?php echo __('---Start Year---'); ?></option>
							<?php
								for($i=2000;$i<=2030;$i++):
                   				 ?>
                <option value="<?php echo $i;?>"><?php echo $i;?></option>

                <?php
				endfor;
              ?>
							</select>
						</div>
						
					</div>
					
					
					<div class="col-md-2 col-sm-2 col-xs-12">
						<div class="form-group">
							<label><?php echo __('End Year'); ?><span class="require-field">*</span></label>
							<select class="form-control validate[required] end_year" name="end_year">
								<option value=""><?php echo __('---End Year---'); ?></option>
							<?php
								for($i=2000;$i<=2030;$i++):
                   				 ?>
							<option value="<?php echo $i;?>"><?php echo $i;?></option>

                <?php
				endfor;
              ?>
							</select>
						</div>
						
					</div>

					<div class="col-md-2 col-sm-2 col-xs-12">
						
						<div class="form-group">
							<label><span class="require-field"></span></label>
								<?php	echo $this->Form->input(__('GO'),array('type'=>'submit','class'=>'btn btn-info','name'=>'view_chart','style'=>''));?>

						</div>
					</div>

					<?php
						if(isset($fees_data)){
					?>
<input type="hidden" value="" name="" id="payid">

					<table id="examlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th><?php echo __('Fee Type');?></th>
						<th><?php echo __('Student Name');?></th>
						<th><?php echo __('Student ID');?></th>
						<th><?php echo __('Roll No');?></th>
						<th><?php echo __('Class');?></th>
						<th><?php echo __('Payment Status');?></th>
						<th><?php echo __('Amount');?></th>
						<th><?php echo __('Due Amount');?></th>
						<th><?php echo __('Description');?></th>
						<th><?php echo __('Year');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
                       <th><?php echo __('Fee Type');?></th>
						<th><?php echo __('Student Name');?></th>
						<th><?php echo __('Student ID');?></th>
						<th><?php echo __('Roll No');?></th>
						<th><?php echo __('Class');?></th>
						<th><?php echo __('Payment Status');?></th>
						<th><?php echo __('Amount');?></th>
						<th><?php echo __('Due Amount');?></th>
						<th><?php echo __('Description');?></th>
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

					foreach($class_data as $class_info){

							if($class_info['class_id'] == $fees_info['class_id']){

					foreach($get_all_user as $user_info){

						if($fees_info['student_id'] == $user_info['user_id']){

						foreach($get_all_data_cat as $fee_type){

							if($fee_type['category_id'] == $fees_info['fees_id']){


					?>


					

					<tr>
					
					<td><?php echo $fee_type['category_type'];  ?></td>
						<td><?php echo $user_info['first_name'].' '.$user_info['last_name']; ?></td>
						<td><?php echo $this->Setting->get_studentID($user_info['user_id']); ?></td>
						<td><?php echo $user_info['roll_no']; ?></td>
						<td><?php echo $class_info['class_name']; ?></td>
						<td><label class="btn btn-success btn-xs"><?php echo $pay_status;?></label></td>
						<td><?php echo $fees_info['total_amount']; ?><input type="hidden" value="<?php echo $fees_info['total_amount']?>" id="amt<?php echo $fees_info['fees_pay_id'];?>"></td>
						<td><?php echo (int)$fees_info['total_amount']-(int)$fees_info['fees_paid_amount']; ?></td>
						<td><?php echo $fees_info['description']; ?></td>
						<td><?php echo $year; ?></td>
						<td>
							
		<button type="button" id="<?php echo $fees_info['fees_pay_id'];?>" data-toggle="modal" data-target="#myModalview" class="btn viewmodal" style=""><?php echo __('View'); ?> </button>
							
							</td>

					</tr>
					<script>
					
					$(function(){
						$('#'+<?php echo $fees_info["fees_pay_id"];?>).click(function(){
							am=$('#amt'+<?php echo $fees_info["fees_pay_id"]?>).val();
							$('#netamt').attr('value',am);
						});



					});
					
					</script>
					<?php
					
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
				<?php
				}
				?>



			<?php $this->Form->end(); ?>

		</div>
</div>

<script>
$(".end_year").change(function(){
	
	var end_year =  $(this).val();

	var start_year = document.getElementById("start_year").value;
	if (parseInt(end_year) >= parseInt(start_year))
	{
		
	} else {
		alert("Select Greater than Start Year");
		$(this).val("");
		return false;
	}
});
</script>