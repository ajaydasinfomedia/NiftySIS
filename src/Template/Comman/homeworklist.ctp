<?php
use Cake\Routing\Router;

$get_current_user_id = $this->request->session()->read('user_id');
$role=$this->Setting->get_user_role($get_current_user_id);
							
?>
<script>
$(document).ready(function(){
	$('#homeworklist').DataTable({responsive: true});
});
</script>

<div class="row schooltitle">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Homework List'),['controller' => 'Comman', 'action' => 'homeworklist'],['escape' => false]);?>
		</li>
		<?php
		if($role == 'teacher')
		{
		?>
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Homework'),['controller' => 'Comman', 'action' => 'addhomework'],['escape' => false]);?>
		</li>
		<?php
		}
		?>
	</ul>	
</div>
<?php
if(isset($it))
{
	if($role == 'teacher')
	{
	?>
	<div class="panel-body" style="padding-top: 50px;">	
	<form method="post">
		<div class="col-md-3 col-sm-6 col-xs-12">	
		<select name="homework" class="form-control validate[required]">
			<option><?php echo __('Select Homework'); ?></option>
			<?php
				
				foreach($homework_data as $homework_ids)
				{
					$selected = ($homework_ids == $homework_id)?'selected':'';
					echo "<option value='".$homework_ids."' ".$selected.">".$this->Setting->get_homework_data($homework_ids,'title')."</option>";
				}
			?>
		</select>								
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12 button-list-possition">
			<?php echo $this->Form->label('');?>
			<input class="btn btn-info" type="submit" name="filter_class" value="Go">
		</div>
	</form>
</div>
<?php } 
if($role == 'parent')
{
?>
<div class="panel-body">	
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="homeworklist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr>				 
						<th><?php echo __('Child Name'); ?></th>
						<th><?php echo __('Homework Title'); ?></th>
						<th><?php echo __('Class');?></th>
						<th><?php echo __('Subject');?></th>
						<th><?php echo __('Status');?></th>
						<th><?php echo __('Submission Date');?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th><?php echo __('Child Name'); ?></th>
						<th><?php echo __('Homework Title'); ?></th>
						<th><?php echo __('Class');?></th>
						<th><?php echo __('Subject');?></th>
						<th><?php echo __('Status');?></th>
						<th><?php echo __('Submission Date');?></th>
					</tr>															
				</tfoot>
				<tbody>	
				<?php 						
				$stud_date = $this->Setting->getfieldname('date_format');
				$childs = $this->Setting->get_child_id($get_current_user_id);
				$i = 0;
				foreach($childs as $user):
					$it = $this->Setting->get_user_homework($user);	
				
				if(!empty($it)):
					foreach($it as $it2):	
						
						$status_int = $this->Setting->get_student_homework_data($it2['homework_id'],$user,'status');
						
					if($status_int == 1)
						$status = "<span style='color: #008000;'>Submitted</span>";
					elseif($status_int == 2)
						$status = "<span style='color: #008000;'>Late-Submitted</span>";
					else
						$status = "<span style='color: #FF0000;'>Pending</span>";
						
					?>
					<tr>
						<td><?php echo $this->Setting->get_user_id($user);?></td>
						<td><?php echo $this->Setting->get_homework_data($it2['homework_id'],'title');?></td>
						<td><?php 
						$class_id = $this->Setting->get_homework_data($it2['homework_id'],'class_id');
						echo $this->Setting->get_class_id($class_id);?></td>
						<td><?php 
						$subject_id = $this->Setting->get_homework_data($it2['homework_id'],'subject_id');
						echo $this->Setting->get_subject_name($subject_id);?></td>
						<td><?php echo $status;?></td>
						<td><?php 
						$submission_date = $this->Setting->get_homework_data($it2['homework_id'],'submission_date');
						echo date($stud_date,strtotime($submission_date));?></td>
						<?php
					echo "</tr>";
					
				endforeach;
				endif;
				endforeach;
				?>			
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php
}
else
{	
?>
<div class="panel-body">	
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="homeworklist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr>				 
						<th><?php echo __('Homework Title'); ?></th>
						<th><?php echo __('Class');?></th>
						<th><?php echo __('Subject');?></th>
						<?php
						if($role == 'student'){?>
						<th><?php echo __('Status');?></th>
						<?php } ?>
						<th><?php echo __('Submission Date');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>					
						<th><?php echo __('Homework Title'); ?></th>
						<th><?php echo __('Class');?></th>
						<th><?php echo __('Subject');?></th>
						<?php
						if($role == 'student'){?>
						<th><?php echo __('Status');?></th>
						<?php } ?>
						<th><?php echo __('Submission Date');?></th>
						<th><?php echo __('Action');?></th>
					</tr>															
				</tfoot>
				<tbody>						
						<?php 
						
						$stud_date = $this->Setting->getfieldname('date_format');
						
						if(isset($homework_id))
						{
							foreach($homework_filter as $it2):
								
								if($role == 'student')
								{
									if($it2['status'] == 1)
										$status = "<span style='color: #008000;'>Submitted</span>";
									elseif($it2['status'] == 2)
										$status = "<span style='color: #008000;'>Late-Submitted</span>";
									else
										$status = "<span style='color: #FF0000;'>Pending</span>";
								}
							?>
								<tr>
								<td><?php echo $this->Setting->get_homework_data($it2['homework_id'],'title');?></td>
								<td><?php 
								$class_id = $this->Setting->get_homework_data($it2['homework_id'],'class_id');
								echo $this->Setting->get_class_id($class_id);?></td>
								<td><?php 
								$subject_id = $this->Setting->get_homework_data($it2['homework_id'],'subject_id');
								echo $this->Setting->get_subject_name($subject_id);?></td>
								<?php
								if($role == 'student'){?>
								<td><?php echo $status;?></td>
								<?php } ?>
								<td><?php 
								$submission_date = $this->Setting->get_homework_data($it2['homework_id'],'submission_date');
								echo date($stud_date,strtotime($submission_date));?></td>
							<?php								
								if($role == 'teacher')
								{
									echo "<td>".$this->Html->link(__('Edit'),array('action' => 'addhomework', $this->Setting->my_simple_crypt($it2['homework_id'],'e')),array('class'=>'btn btnview btn-info'))
									."&nbsp;".
									$this->Form->button( __('Delete'),['action'=>'#','url'=>$this->request->base.'/'.$this->name.'/homeworkdelete/'.$it2['homework_id'],'class'=>'btn btn-danger sa-warning'])
									."&nbsp;".
									$this->Html->link($this->Html->tag('i'," ", array('class' => 'fa fa-eye')).__(' View Submission'),array('action' => 'viewsubmission', $this->Setting->my_simple_crypt($it2['homework_id'],'e')),array('class'=>'btn btnview btn-default','escape' => false))
									."</td>";
								}
								else
								{
									echo "<td>".
									$this->Html->link($this->Html->tag('i'," ", array('class' => 'fa fa-eye')).__(' View'),array('action' => 'studaddsubmission', $this->Setting->my_simple_crypt($it2['homework_id'],'e')),array('class'=>'btn btnview btn-info','escape' => false))
									."</td>";
								}
							
							echo "</tr>";
							endforeach;
						}
						else
						{
							foreach($it as $it2):
														
								if($role == 'student')
								{
									if($it2['status'] == 1)
										$status = "<span style='color: #008000;'>Submitted</span>";
									elseif($it2['status'] == 2)
										$status = "<span style='color: #008000;'>Late-Submitted</span>";
									else
										$status = "<span style='color: #FF0000;'>Pending</span>";
								}
							?>				
								<td><?php echo $it2['title'];?></td>
								<td><?php echo $this->Setting->get_class_id($it2['class_id']);?></td>
								<td><?php echo $this->Setting->get_subject_name($it2['subject_id']);?></td>
								<?php
								if($role == 'student'){?>
								<td><?php echo $status;?></td>
								<?php } ?>
								<td><?php echo date($stud_date,strtotime($it2['submission_date']));?></td>
							<?php
										
								if($role == 'teacher')
								{
									echo "<td>".$this->Html->link(__('Edit'),array('action' => 'addhomework', $this->Setting->my_simple_crypt($it2['homework_id'],'e')),array('class'=>'btn btnview btn-info'))
									."&nbsp;".
									$this->Form->button( __('Delete'),['action'=>'#','url'=>$this->request->base.'/'.$this->name.'/homeworkdelete/'.$it2['homework_id'],'class'=>'btn btn-danger sa-warning'])
									."&nbsp;".
									$this->Html->link($this->Html->tag('i'," ", array('class' => 'fa fa-eye')).__(' View Submission'),array('action' => 'viewsubmission', $this->Setting->my_simple_crypt($it2['homework_id'],'e')),array('class'=>'btn btnview btn-default','escape' => false))
									."</td>";
								}
								else
								{
									echo "<td>".
									$this->Html->link($this->Html->tag('i'," ", array('class' => 'fa fa-eye')).__(' View'),array('action' => 'studaddsubmission', $this->Setting->my_simple_crypt($it2['homework_id'],'e')),array('class'=>'btn btnview btn-info','escape' => false))
									."</td>";
								}
								echo "</tr>";
							endforeach;
						}
						?>			
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php
}
}
else{
	?>
	<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Homework Data available');?></h4></div>
<?php		
}
?>