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
   });

</script>
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

					    <li style="display:<?php if($get_role == 'teacher'){echo 'none';}else{echo 'block';}?>">
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-bar-chart fa-lg')) . __('Fee Payment Report'),array('controller'=>'report','action' => 'feepayment'),array('escape' => false));
						?>
					  </li>
					  
					  <li class="active">
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-bar-chart fa-lg')) . __('Result Report'),array('controller'=>'report','action' => 'result'),array('escape' => false));
						?>
					  </li>


				</ul>
</div>
<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'failed']]);?>
			
	<div class="row">
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="form-group">
    			<label for="exam_id"><?php echo __('Select Exam');?><span class="require-field">*</span></label>
                <select class="form-control validate[required]" name="exam_name">
                	<option value=""><?php echo __('Select Exam Name'); ?></option>

                	<?php foreach($exam_data as $exam_info):
						$selected_vl = ($exam_info['exam_id'] == $exam_id)?"selected":"";
					?>
                			<option value="<?php echo $exam_info['exam_id']; ?>" <?php echo $selected_vl;?>><?php echo $exam_info['exam_name']; ?></option>
                    <?php endforeach; ?>
				 </select>
    		</div>
		</div>
		
		<div class="col-md-3 col-sm-6 col-xs-12">
    		<div class="form-group">
    			<label for="exam_id"><?php echo __('Class Name'); ?><span class="require-field">*</span></label>
                <select class="form-control validate[required]" name="class_name" id="class_id">
                	<option value=" "><?php echo __('Select Class Name'); ?></option>

                	<?php foreach($class_data as $class_info):
					$selected_vl = ($class_info['class_id'] == $class_id)?"selected":"";
					?>
                			<option value="<?php echo $class_info['class_id'];?>" <?php echo $selected_vl;?>><?php echo $class_info['class_name']; ?></option>
                	<?php endforeach;?>

			    </select>
    		</div>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="form-group">
    			<?php echo $this->Form->label('Select Section');?>	
				<select class="form-control ajaxdata" name="section" id="dep">
					<option><?php echo __('Select Section'); ?></option>
					<?php
					if(isset($sub_id))
					{
						echo "<option value=".$sub_id." selected>".$this->Setting->get_class_section($sub_id)."</option>";
					}
					?>
				</select>
    		</div>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="form-group">
				<?php echo $this->Form->label('');?>
				<?php	echo $this->Form->input(__('GO'),array('type'=>'submit','class'=>'btn btn-info','name'=>'view_chart','style'=>''));?>
			</div>
		</div>
	<?php
	if(isset($student)){
	?>
		<div class="panel-body clearfix">
			<div class="table-responsive"style="float: left;width: 100%;overflow-x: scroll!important;">
				<table class="table col-md-12">
				<tr>	
					<th><?php echo __('Student ID'); ?></th>
					<th><?php echo __('Roll No.'); ?></th>
					<th><?php echo __('Name'); ?></th>         
					<?php 
					   if(!empty($subject_list))
						{			
							foreach($subject_list as $sub_id)
							{
								
								echo "<th> ".$sub_id['sub_name']." </th>";
							}
						} ?>
						<th><?php echo __('Total'); ?></th>
						<th>&nbsp;</th>
						</tr>
						<?php
						
						
			foreach($student as $user)
			{
				$role = $this->Setting->get_user_role($user['user_id']);
				if($role == 'student')
				{
					echo "<tr>";
					echo '<td>'.$this->Setting->get_studentID($user['user_id']).'</td>';
					echo '<td>'.$user['roll_no'].'</td>';
					echo '<td><span>' .$this->Setting->get_user_id($user['user_id']). '</span></td>';
					$total=0;
					if(!empty($subject_list))
					{		
						$total=0;
						foreach($subject_list as $sub_id)
						{
							$mark_detail = array();
							$mark_id = 0;
							$marks = 0;
							$mark_detail = $this->Setting->check_mark_detail_result_report($exam_id,$class_id,$sub_id['subid'],$user['user_id']);
							
							if(!empty($mark_detail))
							{
								foreach($mark_detail as $mark_detail)
								{
									$mark_id=isset($mark_detail['mark_id'])?$mark_detail['mark_id']:0;
									$marks=isset($mark_detail['marks'])?$mark_detail['marks']:0;
									$total+=$marks;
								}
							}
							else
							{
								$marks=0;
								$attendance=0;
								$marks_comment="";
								$total=0;
								$mark_id="0";
							}
						
							echo '<td>'.$marks.'</td>';
						}
						echo '<td>'.$total.'</td>';
					}
					else{
						echo "No Result Found";
					}
					
					echo "</tr>";
				}
			} 
			?>
					</table>
			</div>
		</div>
		
		<?php 
		}
		$this->Form->end(); ?>

		</div>
</div>