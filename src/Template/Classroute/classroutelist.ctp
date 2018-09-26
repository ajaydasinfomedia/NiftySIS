<script>
$(document).ready(function() {
	$('#clasroutelist').DataTable({responsive: true});
});
</script>
<div class="row schooltitle">	
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Route List'),['controller' => 'Classroute', 'action' => 'classroutelist'],['escape' => false]);?>
		</li>
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Route'),['controller' => 'Classroute', 'action' => 'addclassroute'],['escape' => false]);?>
		</li>
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-calendar fa-lg')) . __('Teacher Time Table'),['controller' => 'Classroute', 'action' => 'teacherroutelist'],['escape' => false]);?>
		</li>
	</ul>
</div>

<div class="panel panel-white">
    <div class="panel-body">
        <div id="accordion" class="panel-group" aria-multiselectable="true" role="tablist">
			<?php 
				$i=0;
				if(isset($teacher_name))
				{		
					foreach($class_list as $classes)
					{
						$a=0;
					?>
					<div class="panel panel-default">
				
						<div id="heading_<?php echo $i;?>" class="panel-heading" role="tab">
						<h4 class="panel-title">
						<a class="collapsed" aria-controls="collapse_<?php echo $i;?>" aria-expanded="false" href="#collapse_<?php echo $i;?>" data-parent="#accordion" data-toggle="collapse">
						<?php echo __('Class : '); ?> <?php echo __($classes['class_name']);?></a>
				
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
																	echo '</span><span class="caret"></span></button>';
																	echo '<ul role="menu" class="dropdown-menu">';
																			
																	echo "<li>".$this->Html->link(__('Edit'),array('controller'=>'Classroute','action' => 'updateclassroute', $this->Setting->my_simple_crypt($p_data['route_id'],'e')),array('class'=>''))."</li>";
																	echo "<li>".$this->Form->button( __('Delete'),['action'=>'#','url'=>$this->request->base.'/'.$this->name.'/delete/'.$p_data['route_id'],'class'=>'sa-warning'])."</li>";														
																	
																	echo "<ul>";
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