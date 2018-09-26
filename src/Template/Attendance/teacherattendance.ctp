<script>
$( document ).ready(function(){
	var today = new Date();
	$('#date_of_birth').datepicker
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
		<li class="active"  style="display:<?php if($get_role == 'teacher'){echo 'none';}else{echo 'block';}?>">
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-user fa-lg')) . __('Teacher Attendance'),['controller' => 'Attendance', 'action' => 'teacherattendance'],['escape' => false]);?>
		</li>
		<li>
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-users fa-lg')) . __('Subject Wise Attendance'),['controller' => 'Attendance', 'action' => 'subjectattendance'],['escape' => false]);?>
		</li>
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-bar-chart fa-lg')) . __('Monthly Attendance Report'),['controller' => 'Attendance', 'action' => 'attendancemonthly'],['escape' => false]);?>
		</li>
	</ul>
</div>
<div class="row">
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'teacherattendance']]);?>
			<div class="form-group">
				<div class="col-md-3 col-sm-6 col-xs-12 attenddatepickericon">
					<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
						<?php echo $this->Form->label(__('Date'));?>
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
						<?php echo $this->Form->input('',array('value'=>date("Y-m-d", strtotime($current_date)),'id'=>'date_of_birth','name'=>'attendence_date','class'=>'form-control validate[required]'));?>
					</div>	
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 button-possition">
					<?php echo $this->Form->label(__(''));?>
					<?php echo $this->Form->input(__('Take/View Attendance'),array('type'=>'submit','name'=>'take_view_attendance','class'=>'btn btn-info'));?>
				</div>

			</div>

			<?php $this->Form->end(); ?>
		</div>
		<div class="clearfix"> </div>

        <?php
        if(isset($_REQUEST['take_view_attendance']))
        {
			$stud_date = $this->Setting->getfieldname('date_format');
        ?>
				<div class="main-attend">
					<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
						<div class="panel-heading">
							<h4 class="panel-title1"><?php echo __('Teacher Attendance');?> ,
							<?php echo __('Date')?> : <?php echo date($stud_date, strtotime($current_date));?></h4>
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="xs_tbl">
							<table id="teacherattendance_table" class="table">
								<thead>
									<tr>
										<th><?php echo __('Srno');?></th>
										<th><?php echo __('Teacher');?></th>
										<th><?php echo __('Attendance');?></th>
										<th><?php echo __('Comment');?></th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th><?php echo __('Srno');?></th>
										<th><?php echo __('Teacher');?></th>
										<th><?php echo __('Attendance');?></th>
										<th><?php echo __('Comment');?></th>
									</tr>
								</tfoot>
								<tbody>
								<?php
									$date = $current_date;

									$s=1;
										foreach ($teacher as $user)
										{
										$i=0;

											echo '<tr>';

											echo '<td>'.$s.'</td>';
											echo '<td><span>' .$user['first_name'].' '.$user['last_name']. '</span></td>';
											?>
											<td>
												<label class="radio-inline label_float radio">

												<?php


													foreach($show_data as $s_data)
													{

														if($s_data['user_id'] == $user['user_id'])
														{

														$status=$s_data['status'];
														
														echo $this->Form->radio('attendence_'.$s_data['user_id'],[
														[
															'value'=>'Present','text'=>__('Present'),'class'=>'attendance_'.$user['user_id']
														],
														[
															'value'=>'Absent','text'=>__('Absent'),'class'=>'attendance_'.$user['user_id']
														],
														[
															'value'=>'Late','text'=>__('Late'),'class'=>'attendance_'.$user['user_id']
														]
													],['value'=>$status]);
													?>
													<input type="hidden" value=<?php echo $status;?> name="<?php echo 'attendence_'.$user['user_id']; ?>" class="<?php echo 'attend'; ?>" id="<?php echo 'attendance_'.$user['user_id']; ?>">
													<?php	$i=1;
														break;
														}
													}

												if($i == 0)
												{

												echo $this->Form->radio('attendence_'.$user['user_id'],[
													[
														'value'=>'Present','text'=>__('Present'),'checked'=>'checked','class'=>'attendance_'.$user['user_id']
													],
													[
														'value'=>'Absent','text'=>__('Absent'),'class'=>'attendance_'.$user['user_id']
													],
													[
														'value'=>'Late','text'=>__('Late'),'class'=>'attendance_'.$user['user_id']
													]
												]); ?>
												<input type="hidden" value="Present" name="<?php echo 'attendence_'.$user['user_id']; ?>" class="<?php echo 'attend'; ?>" id="<?php echo 'attendance_'.$user['user_id']; ?>">
											<?php	}
												?>
											</label>

											<script>
											$(function(){

												$(".attendance_<?php echo $user['user_id']; ?>").click(function(){

													$("#attendance_<?php echo $user['user_id'];?>").attr('value',$(this).val());

												});

											});
											</script>

											</td>
											<td>
												<?php


													foreach($show_data as $s_data)
													{

														if($s_data['user_id'] == $user['user_id'])
														{

														
														echo $this->Form->input('',array('value'=>$s_data['comment'],'name'=>'attendence_comment_'.$s_data['user_id'],'class'=>'form-control validate[required]'));														$i=1;
														$i=1;
														break;
														}
													}

												if($i == 0)
												{
													echo $this->Form->input('',array('name'=>'attendence_comment_'.$user['user_id'],'class'=>'form-control validate[required]'));
												}
												?>
											</td>
										</tr>
								<?php	$s++;
									} ?>
								</tbody>
							</table>
							</div>
						</div>
						<div class="cleatrfix"></div>
						<div class="form-group">
							<div class="col-md-8 col-sm-8 col-xs-12">
								<?php echo $this->Form->input (__('Save  Attendance'),array('type'=>'submit','name'=>'save_teach_attendence','class'=>'btn btn-success')); ?>
							</div>
						</div>

				<?php $this->Form->end(); ?>
				</div>
		<?php }?>

</div>
