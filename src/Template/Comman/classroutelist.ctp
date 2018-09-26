<?php
$user_id=$this->request->session()->read('user_id');
$get_role=$this->Setting->get_user_role($user_id);			
?>
<script>
	$(document).ready(function() {
		$('#clasroutelist').DataTable({responsive: true});
	});
</script>
<div class="row schooltitle">	
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Route List'),['controller' => 'Comman', 'action' => 'classroutelist'],['escape' => false]);?>
		</li>
		<?php
		if($get_role == 'teacher'){?>
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-calendar fa-lg')) . __('Teacher Time Table'),['controller' => 'Comman', 'action' => 'teacherroutelist'],['escape' => false]);?>
		</li>
		<?php } ?>
	</ul>
</div>
<?php
	if($get_role == 'student'){

?>
<div class="panel panel-white">
    <div class="panel-body">
        <div id="accordion" class="panel-group" aria-multiselectable="true" role="tablist">
			<?php 
			
				$i=0;			
				if(isset($it1))
				{
				foreach($class_list_id as $classes)
				{ 	
					
			?>
				<div class="panel panel-default">
			
					<div id="heading_<?php echo $i;?>" class="panel-heading" role="tab">
					<h4 class="panel-title">
					<a class="collapsed" aria-controls="collapse_<?php echo $i;?>" aria-expanded="true" href="#collapse_<?php echo $i;?>" data-parent="#accordion" data-toggle="collapse">
					<?php echo __('Class : '); ?> <?php echo __($classes['class_name']);?> </a>
				
					</h4>
				   </div>
				   <div id="collapse_<?php echo $i;?>" class="panel-collapse collapse" aria-labelledby="heading_<?php echo $i;?>" role="tabpanel" aria-expanded="false" style="height: 0px;">
					<div class="panel-body">
				   <table class="table table-bordered">
						<?php
							$i++;						
							foreach($daywk as $key2 => $period)
							{
						?>
							<tr>
								<th width="100"><?php echo __($period);?></th>
									<td>
									<?php
											foreach($class_route as $period_data)
											{ 	
												if(array_key_exists($key2,$period_data))
												{																
													foreach($period_data[$key2] as $p_data)
														{
															if($p_data['class'] ==  $classes['class_id'] && $p_data['day'] == $period )
															{
																echo '<div class="btn-group m-b-sm">';
																echo '<button class="btn btn-primary dropdown-toggle" aria-expanded="false" data-toggle="dropdown"><span class="period_box" id='.$p_data['route_id'].'>'.__($p_data['subject']);
																echo '<span class="time"> ('.__(substr_replace($p_data['stime'], ' ', -3, -2)).'-'. __(substr_replace($p_data['etime'], ' ', -3, -2)).') </span>';
																
												
																echo '</div>';
															}
														}												
												}
												
											}
									?>
									</td>
							</tr>
							<?php	
							}
							?>
					
					</table> 
				</div>
				</div>
			<?php }}
			else
			{
				$i=0;			
					
				foreach($class_list as $classes)
				{ 	
			?>
				<div class="panel panel-default">
			
					<div id="heading_<?php echo $i;?>" class="panel-heading" role="tab">
					<h4 class="panel-title">
					<a class="collapsed" aria-controls="collapse_<?php echo $i;?>" aria-expanded="true" href="#collapse_<?php echo $i;?>" data-parent="#accordion" data-toggle="collapse">
					<?php echo __('Class : '); ?> <?php echo __($classes['class_name']);?> </a>
				
					</h4>
				   </div>
				   <div id="collapse_<?php echo $i;?>" class="panel-collapse collapse" aria-labelledby="heading_<?php echo $i;?>" role="tabpanel" aria-expanded="false" style="height: 0px;">
					   <div class="panel-body">
				   <table class="table table-bordered">
						<?php
							$i++;						
							foreach($daywk as $key2 => $period)
							{
						?>
							<tr>
								<th width="100"><?php echo __($period);?></th>
									<td>
									<?php
											foreach($class_route as $period_data)
											{ 	
												if(array_key_exists($key2,$period_data))
												{																
													foreach($period_data[$key2] as $p_data)
														{
															if($p_data['class'] ==  $classes['class_id'] && $p_data['day'] == $period )
															{
																echo '<div class="btn-group m-b-sm">';
																echo '<button class="btn btn-primary dropdown-toggle" aria-expanded="false" data-toggle="dropdown"><span class="period_box" id='.$p_data['route_id'].'>'.__($p_data['subject']);
																echo '<span class="time"> ('.__(substr_replace($p_data['stime'], ' ', -3, -2)).'-'. __(substr_replace($p_data['etime'], ' ', -3, -2)).') </span>';
																
												
																echo '</div>';
															}
														}												
												}
												
											}
									?>
									</td>
							</tr>
							<?php	
							}
							?>
					
					</table> 
				</div>
				</div>
			<?php } }
			?>
		</div>
	</div>
</div>
<?php }else if($get_role == 'teacher'){
?>
<div class="panel panel-white">
    <div class="panel-body">
        <div id="accordion" class="panel-group" aria-multiselectable="true" role="tablist">
			<?php 

				$i=0;			
			
				if(isset($it1)){
				foreach($class_list as $classes)
				{ 	
			?>
				<div class="panel panel-default">
			
					<div id="heading_<?php echo $i;?>" class="panel-heading" role="tab">
					<h4 class="panel-title">
					<a class="collapsed" aria-controls="collapse_<?php echo $i;?>" aria-expanded="false" href="#collapse_<?php echo $i;?>" data-parent="#accordion" data-toggle="collapse">
					<?php echo __('Class : '); ?> <?php echo __($classes['class_name']);?> </a>
				
					</h4>
				   </div>
				   <div id="collapse_<?php echo $i;?>" class="panel-collapse collapse" aria-labelledby="heading_<?php echo $i;?>" role="tabpanel" aria-expanded="false" style="height: 0px;">
				   <div class="panel-body">
				   <table class="table table-bordered">
						<?php
							$i++;						
							foreach($daywk as $key2 => $period)
							{
						?>
							<tr>
								<th width="100"><?php echo __($period);?></th>
									<td>
									<?php
											foreach($class_route as $period_data)
											{ 	
												if(array_key_exists($key2,$period_data))
												{																
													foreach($period_data[$key2] as $p_data)
														{
															if($p_data['class'] ==  $classes['class_id'] && $p_data['day'] == $period )
															{
																echo '<div class="btn-group m-b-sm">';
																echo '<button class="btn btn-primary dropdown-toggle" aria-expanded="false" data-toggle="dropdown"><span class="period_box" id='.$p_data['route_id'].'>'.__($p_data['subject']);
																echo '<span class="time"> ('.__(substr_replace($p_data['stime'], ' ', -3, -2)).'-'. __(substr_replace($p_data['etime'], ' ', -3, -2)).') </span>';
																echo '</button>';
																
																echo '</div>';
															}
														}												
												}
												
											}
									?>
									</td>
							</tr>
							<?php	
							}
							?>
					
					</table> 
				</div>
				</div>
				</div>
			<?php }
		}
			 ?>
		</div>
	</div>
</div>
<?php }else if($get_role == 'parent'){
?>
<div class="panel panel-white">
    <div class="panel-body">
        <div id="accordion" class="panel-group" aria-multiselectable="true" role="tablist">
			<?php 

				$i=0;			
			
				if(isset($class_list_parent_child)){
				foreach($class_list_parent_child as $classes)
				{ 	
			?>
				<div class="panel panel-default">
			
					<div id="heading_<?php echo $i;?>" class="panel-heading" role="tab">
					<h4 class="panel-title">
					<a class="collapsed" aria-controls="collapse_<?php echo $i;?>" aria-expanded="false" href="#collapse_<?php echo $i;?>" data-parent="#accordion" data-toggle="collapse">
					<?php echo __('Class : '); ?> <?php echo $this->Setting->get_class_id($classes);?> </a>
				
					</h4>
				   </div>
				   <div id="collapse_<?php echo $i;?>" class="panel-collapse collapse" aria-labelledby="heading_<?php echo $i;?>" role="tabpanel" aria-expanded="false" style="height: 0px;">
				   <div class="panel-body">
				   <table class="table table-bordered">
						<?php
							$i++;						
							foreach($daywk as $key2 => $period)
							{
						?>
							<tr>
								<th width="100"><?php echo __($period);?></th>
									<td>
									<?php
											foreach($class_route as $period_data)
											{ 	
												if(array_key_exists($key2,$period_data))
												{																
													foreach($period_data[$key2] as $p_data)
														{
															if($p_data['class'] ==  $classes && $p_data['day'] == $period )
															{
																echo '<div class="btn-group m-b-sm">';
																echo '<button class="btn btn-primary dropdown-toggle" aria-expanded="false" data-toggle="dropdown"><span class="period_box" id='.$p_data['route_id'].'>'.__($p_data['subject']);
																echo '<span class="time"> ('.__(substr_replace($p_data['stime'], ' ', -3, -2)).'-'. __(substr_replace($p_data['etime'], ' ', -3, -2)).') </span>';
																echo '</button>';
																
																echo '</div>';
															}
														}												
												}
												
											}
									?>
									</td>
							</tr>
							<?php	
							}
							?>
					
					</table> 
				</div>
				</div>
				</div>
			<?php }
		}
			 ?>
		</div>
	</div>
</div>

<?php
}
?>
<style>
.dropdown-menu li button.sa-warning {
    padding: 7px 10px;
    color: #5f5f5f !important;
    font-size: 13px;
	background-color: transparent;
	border: medium none;
	width: 100%;
    text-align: left;
}
.dropdown-menu li button.sa-warning:hover {
    background-color: #f7f7f7;
}
</style>