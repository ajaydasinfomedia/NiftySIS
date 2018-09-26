<?php
use Cake\Routing\Router;
?>
<script>
$(document).ready(function(){
	$('#viewsubmission').DataTable({responsive: true});
});
</script>

<div class="row schooltitle">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Submission List'),['controller' => 'Homework', 'action' => 'viewsubmission',$this->Setting->my_simple_crypt($id,'e')],['escape' => false]);?>
		</li>
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Homework'),['controller' => 'Homework', 'action' => 'addhomework'],['escape' => false]);?>
		</li>
	
	</ul>	
</div>
<?php
if(isset($it))
{
	if(!empty($it->toArray()))
	{
	?>
	
<div class="panel-body">	
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="viewsubmission" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr>				
						<th><?php echo __('Homework Title'); ?></th>
						<th><?php echo __('Class');?></th>
						<th><?php echo __('Student ID');?></th>
						<th><?php echo __('Student');?></th>
						<th><?php echo __('Subject');?></th>
						<th><?php echo __('Status');?></th>
						<th><?php echo __('Submitted Date');?></th>
						<th><?php echo __('Submission Date');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>					
						<th><?php echo __('Homework Title'); ?></th>
						<th><?php echo __('Class');?></th>
						<th><?php echo __('Student ID');?></th>
						<th><?php echo __('Student');?></th>
						<th><?php echo __('Subject');?></th>
						<th><?php echo __('Status');?></th>
						<th><?php echo __('Submitted Date');?></th>
						<th><?php echo __('Submission Date');?></th>
						<th><?php echo __('Action');?></th>
					</tr>															
				</tfoot>
				<tbody>										
				<?php 						
					$stud_date = $this->Setting->getfieldname('date_format');
					
					foreach($it as $it2):
						
						if($it2['status'] == 1)
							$status = "<span style='color: #008000;'>Submitted</span>";
						elseif($it2['status'] == 2)
							$status = "<span style='color: #008000;'>Late-Submitted</span>";
						else
							$status = "<span style='color: #FF0000;'>Pending</span>";
				?>
						<tr>
						<td><?php echo $this->Setting->get_homework_data($it2['homework_id'],'title');?></td>
						<td><?php 
						$class_id = $this->Setting->get_homework_data($it2['homework_id'],'class_id');
						echo $this->Setting->get_class_id($class_id);?></td>
						<td><?php echo $this->Setting->get_studentID($it2['student_id']);?></td>
						<td><?php echo $this->Setting->get_user_id($it2['student_id']);?></td>
						<td><?php 
						$subject_id = $this->Setting->get_homework_data($it2['homework_id'],'subject_id');
						echo $this->Setting->get_subject_name($subject_id);?></td>
						<td><?php echo $status;?></td>
						<td><?php 
						if($it2['status'] == 0)
							echo "";
						else
							echo date($stud_date,strtotime($it2['uploaded_date']));?></td>
						<td><?php 
						$submission_date = $this->Setting->get_homework_data($it2['homework_id'],'submission_date');
						echo date($stud_date,strtotime($submission_date));?></td>
					<?php
						if($it2['status'] == 0)
						{
							$disable = 'hidden';
						}
						else
						{
							$disable = '';
							$file = WWW_ROOT.'submission'.'/'.$it2['file'];
							$file1 =$this->request->webroot.'submission/'.$it2['file'];
						}
						echo "<td>".$this->Html->link(__('Download'),array('action' => 'submissionfile', $it2['file']),array('class'=>"btn btnview btn-info $disable"))."</td>";
					
					echo "</tr>";
					endforeach;
					?>		
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php
	}
	else{
		?>
		<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Submission Data Available');?></h4></div>
<?php		
	}
}
?>
<style>
a.disabled {
   pointer-events: none;
   cursor: default;
}
</style>