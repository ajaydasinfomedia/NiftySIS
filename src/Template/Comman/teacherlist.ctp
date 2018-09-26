<script>
$(document).ready(function() {
	$('#teacherlist').DataTable({responsive: true});
	
	jQuery('body').on('click', '.viewdetail', function() {

		var get_id=jQuery(this).attr('viewID');

		$.ajax({
			type:'POST',
			url:'<?php echo $this->Url->build(["controller"=>"User","action"=>"userinfo"]); ?>',
			data:{id:get_id},

			success:function(getdata){
				$(".modal-body").html(getdata);
			},
			beforeSend:function(){
				$(".modal-body").html("<center><h2 class=text-muted><b>Loading...</b></h2></center>");
			},
			error:function(e){
				
				console.log(e);
			},
		});
	});
});
</script>
<div class="row schooltitle">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Teacher List'),['controller' => 'Comman', 'action' => 'teacherlist'],['escape' => false]);?>
		</li>
	</ul>	
</div>
<div class="panel-body">	
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="teacherlist" class="table table-striped" cellspacing="0" width="100%">
				<?php if($role == 'student' || $role == 'parent' || $role == 'supportstaff')
				{ ?>
					<thead>
					<tr>
						<th><?php echo __('Photo');?></th>
						<th><?php echo __('Teacher Name');?></th>
						<th><?php echo __('Email');?></th>
					</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?php echo __('Photo');?></th>
							<th><?php echo __('Teacher Name');?></th>
							<th><?php echo __('Email');?></th>
						</tr>
					</tfoot>
			<?php } if($role == 'teacher'){ ?>
				<thead>
					<tr>
						<th><?php echo __('Photo');?></th>
						<th><?php echo __('Teacher Name');?></th>
						<th><?php echo __('Email');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th><?php echo __('Photo');?></th>
						<th><?php echo __('Teacher Name');?></th>
						<th><?php echo __('Email');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
				</tfoot>
				<?php } ?>
				<tbody>
					<tr>
						
							<?php 
                                                        
                               $user_session_id=$this->request->session()->read('user_id');
								$a=0;
								if($role=='student')
								{
								foreach($it as $it2):
								{
								
									$name=$it2['first_name']." ".$it2['last_name'];
                                                                        ?>
                                            <td><?php echo $this->Html->image($it2['image'],array('height'=>'50px','width'=>'50px','class'=>'profileimg')); ?></td>
                                            <td><?php echo $name; ?> </td>
											<td><?php echo $it2['email']; ?></td>	
                                                                          
                                            <?php	
									$a=$a+1;
									
								} ?>
					</tr>
					<?php endforeach; 
					}
					if($role=='teacher')
								{
								foreach($it as $it2):
								{
								
									$name=$it2['first_name']." ".$it2['last_name'];
                                                                        ?>
                                            <td><?php echo $this->Html->image($it2['image'],array('height'=>'50px','width'=>'50px','class'=>'profileimg')); ?></td>
                                            <td><?php echo $name; ?> </td>
											<td><?php echo $it2['email']; ?></td>	
											<td><?php 
                                                                        
                                            if($user_session_id == $it2['user_id']){
                                               echo  
											   $this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-eye')). __('View Details'),['action'=>'#','data-toggle'=>'modal','data-target'=>'#myModalview','viewID'=>$it2['user_id'],'class'=>'btn btn-primary viewdetail'],['escape' => false]).
												"&nbsp;".
											   $this->Html->link(__('Edit'),array('action' => 'updateteacher', $this->Setting->my_simple_crypt($it2['user_id'],'e')),array('class'=>'btn btnview btn-info'))."&nbsp;".$this->Html->link($this->Html->tag('i'," ", array('class' => 'fa fa-eye')).__(' View Attendance'),array('controller'=>'Teacher','action' => 'teacherattendance', $this->Setting->my_simple_crypt($it2['user_id'],'e')),array('class'=>'btn btnview btn-default','escape' => false));
                                            }else{
                                            }
											?></td>
                                            <?php	
									$a=$a+1;
									
								} ?>
					</tr>
					<?php endforeach; 
					}
					if($role=='supportstaff')
								{
								foreach($it as $it2):
								{
								
									$name=$it2['first_name']." ".$it2['last_name'];
                                                                        ?>
                                            <td><?php echo $this->Html->image($it2['image'],array('height'=>'50px','width'=>'50px','class'=>'profileimg')); ?></td>
                                            <td><?php echo $name; ?> </td>
											<td><?php echo $it2['email']; ?></td>	
											
                                            <?php	
									$a=$a+1;
									
								} ?>
					</tr>
					<?php endforeach; 
					}
					if($role=='parent')
								{
								foreach($it as $it2):
								{
								
									$name=$it2['first_name']." ".$it2['last_name'];
                                                                        ?>
                                            <td><?php echo $this->Html->image($it2['image'],array('height'=>'50px','width'=>'50px','class'=>'profileimg')); ?></td>
                                            <td><?php echo $name; ?> </td>
											<td><?php echo $it2['email']; ?></td>	
											
                                            <?php	
									$a=$a+1;
									
								} ?>
					</tr>
					<?php endforeach; 
					}
							?>
				</tbody>
				</table>
		</div>
	</div>
</div>
<div class="modal fade " id="myModalview" role="dialog">
	<div class="modal-dialog modal-md"  >
		<div class="modal-content">
			<div class="modal-header" >
				<span type="button" class="" data-dismiss="modal"><?php echo __("Teacher Details");?></span>
				<a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
			</div>
			<div class="modal-body" style="float: left;width: 100%;background-color: #FFFFFF;"></div>
		</div>
	</div>
</div>