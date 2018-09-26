<script>
		$(document).ready(function() {
		$('#examlist').DataTable({responsive: true});
		} );
	</script>
<div class="row schooltitle">			
				<ul role="tablist" class="nav nav-tabs panel_tabs">
					  <li class="active">
					  
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list-ul fa-lg')) . __('Holiday List'),array('controller'=>'Comman','action' => 'holidaylist'),array('escape' => false));
						?>
						
						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->
						  
					  </li>
					  
					 
				</ul>
</div>
<?php
if(isset($row))
{
?>
<div class="panel-body">	
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th> <?php echo __('Holiday List'); ?> </th>
						<th> <?php echo __('Description'); ?> </th>
						<th> <?php echo __('Start Date'); ?> </th>
						<th> <?php echo __('End Date'); ?> </th>
						
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th> <?php echo __('Holiday List'); ?> </th>
						<th> <?php echo __('Description'); ?> </th>
						<th> <?php echo __('Start Date'); ?> </th>
						<th> <?php echo __('End Date'); ?> </th>
						
					</tr>
				</tfoot>
				<tbody>

					<?php foreach($row as $get_data): 
					$stud_date = $this->Setting->getfieldname('date_format');
					?>
					<tr>
						<td><?php echo $get_data['holiday_title']; ?></td>
						<td><?php echo $get_data['description']; ?></td>
						<td><?php echo date($stud_date,strtotime($get_data['date'])); ?></td>
						<td><?php echo date($stud_date,strtotime($get_data['end_date'])); ?></td>
						
					
					</tr>
				<?php endforeach; ?>
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
<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Holiday Data Available');?></h4></div>
<?php	
}
?>

