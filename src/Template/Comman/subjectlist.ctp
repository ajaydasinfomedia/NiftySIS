<script>
$(document).ready(function() {
	$('#subjectlist').DataTable({responsive: true});
});
</script>
<div class="row schooltitle">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li class="active">	
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Subject List'),['controller' => 'comman', 'action' => 'subjectlist'],['escape' => false]);?>
		</li>
	</ul>	
</div>
<div class="panel-body">	
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="subjectlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr>
                        <th><?php echo __('Class Name');?></th>
						<th><?php echo __('Section Name');?></th>
						<th><?php echo __('Subject Code');?></th>
						<th><?php echo __('Subject Name');?></th>
						<th><?php echo __('Teacher Name');?></th>
						<th><?php echo __('Syllabus');?></th>		
					</tr>
				</thead>
				<tfoot>
					<tr>
                        <th><?php echo __('Class Name');?></th>
						<th><?php echo __('Section Name');?></th>
						<th><?php echo __('Subject Code');?></th>
						<th><?php echo __('Subject Name');?></th>
						<th><?php echo __('Teacher Name');?></th>
						<th><?php echo __('Syllabus');?></th>
					</tr>
				</tfoot>

				<?php
						$user_id = $this->request->session()->read('user_id');
						$role = $this->Setting->get_user_role($user_id);
						$a=0;
						if($role == 'student')
						{
							foreach($stud_sub as $it4):
							?>		
							<tr>
							<td><?php 
								echo $classname;
							?></td>
							<td><?php echo $id2[$a]; ?> </td>
							<td><?php echo $it4['sub_code'];?></td>
							<td><?php echo $it4['sub_name'];?></td>
							<td><?php echo $name[$a];?></td>
							<td>
							<?php 
								if($it4['syllabus'] == NULL || $it4['syllabus'] == '')
								{
									echo ' - ';
								}else{
									
								$file = WWW_ROOT.'syllabus'.'/'.$it4['syllabus'];
								$file1 =$this->request->webroot.'syllabus/'.$it4['syllabus'];

								echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-download fa-lg')) . __('Download'),['action' => 'readfile',$it4['syllabus']],['escape' => false,'class'=>'btn btn-default']);
								
								?>
								<a href="<?php echo $file1;?>" target="_blank" class="btn btn-default"><?php echo __('View'); ?></a> 
							<?php }?>
							</td>
							
							</tr>
							

							<?php
							$a=$a+1;
					 
							endforeach; 
						}
						else
						{
							foreach($it as $it4):
							?>		
							<tr>
							<td><?php 
								echo $this->Setting->get_class_id($it4['class_id']);
							?></td>
							<td><?php echo $id2[$a]; ?> </td>
							<td><?php echo $it4['sub_code'];?></td>
							<td><?php echo $it4['sub_name'];?></td>
							<td><?php echo $this->Setting->get_user_id($it4['teacher_id']);?></td>
							<td>
							<?php 
								if($it4['syllabus'] == NULL || $it4['syllabus'] == '')
								{
									echo ' - ';
								}else{
									
								$file = WWW_ROOT.'syllabus'.'/'.$it4['syllabus'];
								$file1 =$this->request->webroot.'syllabus/'.$it4['syllabus'];

								echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-download fa-lg')) . __('Download'),['action' => 'readfile',$it4['syllabus']],['escape' => false,'class'=>'btn btn-default']);
								
								?>
								<a href="<?php echo $file1;?>" target="_blank" class="btn btn-default"><?php echo __('View'); ?></a> 
							<?php }?>
							</td>
							
							</tr>
							

							<?php
							$a=$a+1;
					 
							endforeach;
						}
				?>
			
				</tbody>
				</table>
		</div>
	</div>
</div>