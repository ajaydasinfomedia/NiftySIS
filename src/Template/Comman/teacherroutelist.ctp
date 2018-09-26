
<script>
		$(document).ready(function() {
		$('#clasroutelist').DataTable({responsive: true});
		} );
	</script>
<div class="row schooltitle">
	<ul role="tablist" class="nav nav-tabs panel_tabs">	
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Route List'),['controller' => 'Comman', 'action' => 'classroutelist'],['escape' => false]);?>
		</li>
		
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-calendar fa-lg')) . __('Teacher Time Table'),['controller' => 'Comman', 'action' => 'teacherroutelist'],['escape' => false]);?>
		</li>
	</ul>
</div>
<div class="panel panel-white">
    <div class="panel-body">
        <div id="accordion" class="panel-group" aria-multiselectable="true" role="tablist">
			<?php 
				$i=0;
				$get_current_user_id=$this->request->session()->read('user_id');	
			?>
				<div class="panel panel-default">
			
					<div id="heading_<?php echo $i;?>" class="panel-heading" role="tab">
					<h4 class="panel-title">
					<a class="collapsed" aria-controls="collapse_<?php echo $i;?>" aria-expanded="false" href="#collapse_<?php echo $i;?>" data-parent="#accordion" data-toggle="collapse">
					<?php echo __('Teacher : '); ?> <?php echo __($this->Setting->get_user_id($get_current_user_id));?> </a>
				
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
								<th width="100"><?php echo $period;?></th>
									<td>
									<?php
											foreach($class_route as $period_data)
											{ 	
												if(array_key_exists($key2,$period_data))
												{																
													foreach($period_data[$key2] as $p_data)
														{
															if($p_data['teacher'] ==  $get_current_user_id && $p_data['day'] == $period )
															{
																echo '<div class="btn-group m-b-sm">';
																echo '<button class="btn btn-primary dropdown-toggle" aria-expanded="false" data-toggle="dropdown"><span class="period_box" id='.$p_data['route_id'].'>'.__($p_data['subject']);
																echo '<span class="time"> ('.__(substr_replace($p_data['stime'], ' ', -3, -2)).'- '.__(substr_replace($p_data['etime'], ' ', -3, -2)).')(Class : '.$p_data['class_name'].') </span>';
																echo '</span></button>';
																
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
	</div>
</div>
</div>