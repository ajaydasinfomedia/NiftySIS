<script>
		jQuery(document).ready(function() {

		jQuery("#dep").change(function(){

			var get_class_id=$(this).val();

				if($(this).val() == ''){

				}else{

			$.ajax({

				type:'POST',
				url:'<?php echo $this->Url->build(["controller"=>"User","action"=>"showdata"])?>',
				data:{id:get_class_id},


				success:function(getdata){

					$(".result").html(getdata);

				},
/*
				beforeSend:function(){
					$(".result").html("<center><h5>Loading...</h5></center>");
				},
*/
				error:function(){
					alert('An Error Occured:'+e.responseText);
					console.log();
				}



			});
		}

		});

		});
	</script>
	
<script>
$( document ).ready(function(){
	
    $("#class_id").change(function(){
	var class_id = $(this).val();
     
	 $('.ajaxdata').html();
       $.ajax({
       type: 'POST',
       url: '<?php echo $this->Url->build(["controller" => "Student","action" => "view2"]);?>',
	
       data : {id : class_id},

       success: function (data)
       {            		
			$('.ajaxdata').html(data);
			console.log(data);		  		
		},
		error: function(e) {
			   alert("An error occurred: " + e.responseText);
			   console.log(e.responseText);	
		}

       });

       });
	   var today = new Date();
	   $('#date_of_attendance').datepicker
		({
			changeMonth: true,
			changeYear: true,
			autoclose:true,
            endDate: "today",
            maxDate: today,
			yearRange:'-75:+10',
			dateFormat: 'yy-mm-dd',	
		});
		
   });

</script>


	<?php

		use Cake\ORM\TableRegistry;

		$user_id=$this->request->session()->read('user_id');

			$class_user = TableRegistry::get('smgt_users');
			$query=$class_user->find()->where(['user_id'=>$user_id]);

			$get_role='';

			foreach($query as $role){
					$get_role=$role['role'];
			}

	?>

<div class="row">
	<ul role="tablist" class="nav nav-tabs panel_tabs">
		<li>
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-users fa-lg')) . __('Attendance'),['controller' => 'Attendance', 'action' => 'attendance'],['escape' => false]);?>
		</li>
		<li style="display:<?php if($get_role == 'teacher'){echo 'none';}else{echo 'block';}?>">
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-user fa-lg')) . __('Teacher Attendance'),['controller' => 'Attendance', 'action' => 'teacherattendance'],['escape' => false]);?>
		</li>
		<li class="active">
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-users fa-lg')) . __('Subject Wise Attendance'),['controller' => 'Attendance', 'action' => 'subjectattendance'],['escape' => false]);?>
		</li>
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-bar-chart fa-lg')) . __('Monthly Attendance Report'),['controller' => 'Attendance', 'action' => 'attendancemonthly'],['escape' => false]);?>
		</li>
	</ul>
</div>
<div class="row">
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'attendance']]);?>
			<div class="form-group">
				<div class="col-md-3 col-sm-6 col-xs-12 attenddatepickericon">
					<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
						<?php echo $this->Form->label(__('Date'));?>
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
						<?php echo $this->Form->input('',array('value'=>date("Y-m-d", strtotime($current_date)),'id'=>'date_of_attendance','name'=>'attendence_date','class'=>'form-control validate[required]'));?>
					</div>	
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12">

					<?php echo $this->Form->label(__('Select Class Name'));?><span style="color:red;"><?php echo " *"; ?></span>
					<?php
			
						if(isset($_POST['attendance']))
						{
							echo @$this->form->select("class_id",$class_id,["default"=>$c_id,"empty"=>__("Select Class"),"class"=>"form-control validate[required,maxSize[50]]",'id'=>'class_id']);
						}
						else
						{
							echo @$this->form->select("class_id",$class_id,["default"=>"","empty"=>__("Select Class"),"class"=>"form-control validate[required,maxSize[50]]",'id'=>'class_id']);
						}

						?>
						
				</div>
				
				<div class="col-md-3 col-sm-6 col-xs-12">
				<?php echo $this->Form->label(__('Select Section Name'));?><span style="color:red;"><?php echo " *"; ?></span>	
					<div>
					<select class="form-control validate[required,maxSize[50]] ajaxdata" name="section" id="dep">
					<?php if(isset($sec_id)){?>
		                <option value="<?php echo $sec_id; ?>"><?php echo $this->Setting->get_class_section($sec_id); ?></option>
       						<?php } 
						else
							echo "<option value=''>" ?> <?php echo __('Select Section'); ?> <?php "</option>";
						?>
					</select>
					</div>
				</div>
				
				<div class="col-md-3 col-sm-6 col-xs-12">

						<?php echo $this->Form->label(__('Select Subject'));?><span style="color:red;"><?php echo " *"; ?></span>

						<div class="result">
							<select class="form-control validate[required,maxSize[50]]" name="sub_id">
						<?php if(isset($s_id))
						{ ?>
							<option value="<?php echo $s_id; ?>"><?php echo $sub_nm;?></option>
				<?php	}else{ ?>
							<option value=""><?php echo __('Select Subject'); ?> </option>
							<?php foreach($get_data as $id):?><option value="<?php echo $id['subid'];?>"><?php echo $id['sub_name'];?></option> <?php endforeach;?>
				<?php	}?>


					</select>

						</div>

				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 button-list-possition">
					<?php echo $this->Form->label('');?><span></span>
					<?php echo $this->Form->input(__('Take/View Attendance'),array('type'=>'submit','name'=>'attendance','class'=>'btn btn-success'));?>
				</div>
			</div>


		</div>
		<div class="clearfix"> </div>
        <?php
        if(isset($_POST['attendance']))
        {
			if(isset($user))
			{
				$stud_date = $this->Setting->getfieldname('date_format');
				?>
				<div class="main-attend">

					<div class="panel-heading">
						<h4 class="panel-title1">
							<?php echo __('Class')?> : <?php echo $cn_id;?> ,
							<?php echo __('Date')?> : <?php echo date($stud_date, strtotime($current_date));?> ,
							<?php echo __('Subject')?> : <?php echo $sub_nm;?>
						</h4>
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="xs_tbl">
						<table id="subjectattendance_table" class="table table-striped" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th><?php echo __('Srno');?></th>
									<th><?php echo __('Student ID');?></th>
									<th><?php echo __('Roll No.');?></th>
									<th><?php echo __('Student Name');?></th>
									<th><?php echo __('Attendance');?></th>
									<th><?php echo __('Comment');?></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th><?php echo __('Srno');?></th>
									<th><?php echo __('Student ID');?></th>
									<th><?php echo __('Roll No.');?></th>
									<th><?php echo __('Student Name');?></th>
									<th><?php echo __('Attendance');?></th>
									<th><?php echo __('Comment');?></th>
								</tr>
							</tfoot>
							<tbody>
							<?php
								$date = $current_date;
								$i = 0;
								$j = 0;
								$s = 1;
								foreach($user as $user_data)
								{
									
									echo '<tr>';

									echo '<td>'.$s.'</td>';
									echo '<td><span>' .$this->setting->get_studentID($user_data['user_id']). '</span></td>';
									echo '<td><span>' .$this->setting->get_user_roll_no($user_data['user_id']). '</span></td>';
									echo '<td><span>' .$user_data['first_name'].' '.$user_data['last_name']. '</span></td>';

									?>
									<td>

									<label class="radio-inline label_float radio">

										<?php
											foreach($check_attendance as $data_att)
											{
												if($data_att['user_id'] == $user_data['user_id'])
												{
													$status=$data_att['status'];
													
													echo $this->Form->radio('attendence_'.$data_att['user_id'],[
													[
														'value'=>'Present','text'=>__('Present'),'class'=>'attendance_'.$user_data['user_id']
													],
													[
														'value'=>'Absent','text'=>__('Absent'),'class'=>'attendance_'.$user_data['user_id']
													],
													[
														'value'=>'Late','text'=>__('Late'),'class'=>'attendance_'.$user_data['user_id']
													]
												],['value'=>$status]);
												?>

												<input type="hidden" value="<?php echo $status;?>" name="<?php echo 'attendence_'.$user_data['user_id']; ?>" class="<?php echo 'attend'; ?>" id="<?php echo 'attendance_'.$user_data['user_id']; ?>">

												<?php
													$i=1;
													break;
												}
											}

											if($i==0)
											{
												$status = 'Present';
												
												$stud_chk_attendance = $this->Setting->sub_stud_check_attendence($c_id,$p_date,$user_data['user_id']);
												
												if(!empty($stud_chk_attendance))
												{
													$status = $stud_chk_attendance[0]['status'];
												}
												
												echo $this->Form->radio('attendence_'.$user_data['user_id'],[
													[
														'value'=>'Present','text'=>__('Present'),'class'=>'attendance_'.$user_data['user_id']
													],
													[
														'value'=>'Absent','text'=>__('Absent'),'class'=>'attendance_'.$user_data['user_id']
													],
													[
														'value'=>'Late','text'=>__('Late'),'class'=>'attendance_'.$user_data['user_id']
													]
											],['value'=>$status]);
												?>
											<input type="hidden" value="<?php echo $status;?>" name="<?php echo 'attendence_'.$user_data['user_id']; ?>" class="<?php echo 'attend'; ?>" id="<?php echo 'attendance_'.$user_data['user_id']; ?>">
											<?php
											}
										?>
									</label>

									<script>
									$(function(){

										$(".attendance_<?php echo $user_data['user_id']; ?>").click(function(){

											$("#attendance_<?php echo $user_data['user_id'];?>").attr('value',$(this).val());

										});

									});
									</script>
									</td>
									<td>
										<?php
											foreach($check_attendance as $data_att)
											{
												if($data_att['user_id'] == $user_data['user_id'])
												{
													echo $this->Form->input('',array('value'=>$data_att['comment'],'name'=>'attendence_comment_'.$data_att['user_id'],'class'=>'form-control validate[required]'));														$i=1;
													$i=1;
													break;
												}
											}

											if($i == 0)
											{
												echo $this->Form->input('',array('name'=>'attendence_comment_'.$user_data['user_id'],'class'=>'form-control validate[required]'));
											}
										?>
									</td>
									<?php
									echo '</tr>';
									$s++;
									$j++;
								}
							?>
							</tbody>
						</table>
						</div>
						<div class="form-group">
							<div class="col-md-4 col-sm-8 col-xs-12 label_float"><?php echo $this->Form->label(__('If student absent then Send SMS to his/her parents '));?></div>
							<script>
									$(function(){

										$(".selectchk").click(function(){

											if($('.selectchk').is(':checked')){
											$("#valuechk").attr('value','1');
											}
											else{
											$("#valuechk").attr('value','0');
											}


										});

									});
								</script>
								<div class="col-md-2 col-sm-2 col-xs-12">
									<?php
									
										echo $this->Form->checkbox('',array('name'=>'smgt_sms_service_enable','class'=>'selectchk')); ?>
								<input type="hidden" value="0" name="smgt_sms_service_enable" class="<?php echo 'attend'; ?>" id="valuechk">

								</div>
						</div>
						<div class="form-group">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<?php echo $this->Form->input(__('Save  Attendance'),array('type'=>'submit','name'=>'save_attendence','class'=>'btn btn-success'));?>
							</div>
						</div>
					</div>
			</div>
		<?php }
			else
			{
			?>
			<div class="panel-body">
				<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Student Available');?></h4>
				</div>
			</div>
		<?php
			}
		}
		?>
		<div class="col-md-3 col-sm-6 col-xs-12 button-possition">
		</div>
		<?php $this->Form->end(); ?>
</div>
