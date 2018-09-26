<script>
$(document).ready(function() {
	$('#teacherlist').DataTable({responsive: true});
});
</script>
<div class="row schooltitle">			
				<ul role="tablist" class="nav nav-tabs panel_tabs">
					  <li class="active">
					  
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Transport List'),array('controller'=>'Comman','action' => 'transportlist'),array('escape' => false));
						?>
						
						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->
						  
					  </li>
					  
					 
				</ul>
</div>
<div class="panel-body">	
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="teacherlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr>
                        <th><?php echo __('Photo');?></th>
						<th><?php echo __('Route Name');?></th>
						<th><?php echo __('Vehicle Identifier');?></th>
						<th><?php echo __('Vehicle Registration Number');?></th>						
						<th><?php echo __('Driver Name');?></th>
						<th><?php echo __('Driver Phone Number')?></th>
						<th><?php echo __('Route Fare')?></th>
					
					</tr>
				</thead>
				<tfoot>
					<tr>
						 <th><?php echo __('Photo');?></th>
						<th><?php echo __('Route Name');?></th>
						<th><?php echo __('Vehicle Identifier');?></th>
						<th><?php echo __('Vehicle Registration Number');?></th>						
						<th><?php echo __('Driver Name');?></th>
						<th><?php echo __('Driver Phone Number')?></th>
						<th><?php echo __('Route Fare')?></th>
					</tr>
				</tfoot>
				<tbody>
						
						<?php 	
								foreach($rows as $row):
								
							?>
								<tr>
                                    <Td><?php 
									if(!empty($row['image'])){
									echo $this->Html->image($row['image'],array('height'=>'50px','width'=>'50px','class'=>'profileimg')); 
									}
									else{
									echo $this->Html->image('transport.png',array('height'=>'50px','width'=>'50px','class'=>'profileimg')); 
									}
									?></td>
									<Td><?php echo $row['route_name']; ?></td>
									<Td><?php echo $row['vehicle_identifier']; ?></td>
									<Td><?php echo $row['vehicle_registration_number']; ?></td>
									
									<Td><?php echo $row['driver_name']; ?></td>
									<Td><?php echo $row['driver_phone_number']; ?></td>
									<Td><?php echo $row['route_fare']; ?></td>
								
								</tr>
										
					
				
					<?php endforeach;
							?>
				</tbody>
				</table>
		</div>
	</div>
</div>