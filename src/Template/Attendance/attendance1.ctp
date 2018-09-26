<div class="row">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">
		<li class="active">
		  <?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-pencil fa-lg')) . __('Attendance'),['controller' => 'Attendance', 'action' => 'attendance'],['escape' => false]);?>
		</li>
	</ul>
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'attendance']]);?>
			<div class="form-group">
				<div class="col-md-3 col-sm-6 col-xs-12">
					<?php echo $this->Form->label(__('Date'));?>
					
						<?php echo $this->Form->input('',array('value'=>date("Y-m-d", strtotime($current_date)),'name'=>'attendence_date','class'=>'form-control validate[required]'));?>
				
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12">
							
					<?php echo $this->Form->label('Select Class Name');?><span style="color:red;"><?php echo " *"; ?></span>
							
					<?php 
								
						foreach($class_id as $class_data):
						{
							$class[$class_data['class_id']]=$class_data['class_name'];
						}
						endforeach; 
						if(isset($_POST['attendance']))
						{
							echo $this->Form->select('',array('options'=>$class),array('name'=>'class_id','value'=>$cls_id,'class'=>'form-control validate[maxSize[50]]','id'=>'class_id'));
						}
						else
						{
							echo $this->Form->select('',array('options'=>$class),array('name'=>'class_id','class'=>'form-control validate[maxSize[50]]','id'=>'class_id','empty'=>'Select Class'));
						}
								
						?>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 button-possition">
							
					<?php echo $this->Form->label('');?><span></span>
					<?php echo $this->Form->input('Take/View Attendance',array('type'=>'submit','name'=>'attendance','class'=>'btn btn-info'));?>

				</div>
					
			</div>
			
			<?php $this->Form->end(); ?>
		</div>
		<div class="clearfix"> </div>
        <?php 
        if(isset($_POST['attendance']))
        {
			$stud_date = $this->Setting->getfieldname('date_format');
		?>
			<div class="panel-body main-attend">  
				<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
				<div class="panel-heading">
					<h4 class="panel-title1"> 
						<?php echo __('Class')?> : <?php echo $c_id;?> , 
						<?php echo __('Date')?> : <?php echo date($stud_date, strtotime($current_date));?>
					</h4>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<table class="table">
						<tr>
							<th><?php echo __('Srno');?></th>
							<th><?php echo __('Roll No.');?></th>
							<th><?php echo __('Student Name');?></th>
							<th><?php echo __('Attendance');?></th>
							<th><?php echo __('Comment');?></th>				
						</tr>
						<?php
							$date = $current_date;
							$i = 1;
							foreach($user as $user_data)
							{
								
								echo '<tr>';
              
								echo '<td>'.$i.'</td>';
								echo '<td><span>' .$user_data['user_id']. '</span></td>';
								echo '<td><span>' .$user_data['first_name'].' '.$user_data['last_name']. '</span></td>';
								
								?>
								<td>
								<span class="label"><b>Gender</b></span>
<label for="male">Male</label>
  <input type="radio" name="gender" id="male" value="male"><br>
  <label for="female">Female</label>
  <input type="radio" name="gender" id="female" value="female"><br>
  <label for="other">Other</label>
  <input type="radio" name="gender" id="other" value="other"><br><br>

    <input style="font-size:18px;" type="radio" value="male" name="gender" />male
   
								<label class="radio-inline label_float radio">
									<?php 
													
										$options = array('Present' =>'Present', 'Absent' => 'Absent', 'Late' =>'Late');
								
										echo $this->Form->radio('attendance_'.$user_data['user_id'],$options,['hiddenField'=>false]);
									 
									?>
								</label>
								</td>
								<td>
									<?php
								/*	if($it['comment'] != '')
									{
										
										echo $this->Form->input('',array('value'=>$it['comment'],'name'=>'attendence_comment_'.$user_data['user_id'],'class'=>'form-control validate[required]'));
									}
									else
									{*/
										echo $this->Form->input('',array('name'=>'attendence_comment_'.$user_data['user_id'],'class'=>'form-control validate[required,maxSize[150]]'));
								/*	}*/
									?>
								</td>
								<?php 
                
								echo '</tr>';
								$i++;
							}
						?>
							
					</table>
				<!--	<div class="form-group">
						<div class="col-sm-4 label_float"><?php echo $this->Form->label(__('If student absent then Send  SMS to his/her parents '));?></div>
						
							<div class="col-md-2 col-sm-2 col-xs-12">
								<?php
								/*	$data=$it['submitted_document'];
									$d=explode(',',$data); */
									echo $this->Form->select('', ['msg'=>''],array('name'=>'smgt_sms_service_enable','multiple' => 'checkbox')); ?>
							</div>
					</div> -->
					<div class="col-md-12 col-sm-12 col-xs-12">
						<?php echo $this->Form->input('Save  Attendance',array('type'=>'submit','name'=>'save_attendence','class'=>'btn btn-success'));?>
					</div>
				</div>
			<?php $this->Form->end(); ?>
			</div>
		<?php } ?>
</div>
	