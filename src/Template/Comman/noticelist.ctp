<script>
		jQuery(document).ready(function() {
		jQuery('#examlist').DataTable({responsive: true});
		
		jQuery('body').on('click', '.viewdetail', function() {	

			var get_id=jQuery(this).attr('id');

			$.ajax({

					type:'POST',
					url:'<?php echo $this->Url->build(["controller"=>"User","action"=>"view"]); ?>',
					data:{id:get_id},

					success:function(getdata){
						$(".modal-body").html(getdata);
					},

					beforeSend:function(){
						$(".modal-body").html("<center><h2 class=text-muted><b>Loading...</b></h2></center>");
					},

					error:function(e){
						alert("An error ocurred:"+e.responseText);
						console.log(e);
					},

			});

		});
		
		});
	</script>




				<!--  -->

	<div class="modal fade " id="myModal1" role="dialog">
    <div class="modal-dialog modal-md"  >

      <div class="modal-content">
        <div class="modal-header" >
			<span type="button" class="" data-dismiss="modal"><?php echo __("Notice Details");?></span>
			<a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
        </div>
        <div class="modal-body" style="padding-top: 0px;">
		
		
        </div>
        
        </div>
      </div>
      
    </div>
	
				<!-- -->



<div class="row schooltitle">			
				<ul role="tablist" class="nav nav-tabs panel_tabs">
					<li class="active"> 
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Notice List'),array('controller'=>'Comman','action' => 'noticelist'),array('escape' => false));?>
					 </li> 
				</ul>
</div>
<div class="panel-body">	
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th> <?php echo __('Notice Title'); ?> </th>
						<th> <?php echo __('Notice Comment'); ?> </th>
						<th> <?php echo __('Notice Start Date'); ?> </th>
						<th> <?php echo __('Notice End Date'); ?> </th>
						<th> <?php echo __('Notice For'); ?> </th>
						<th> <?php echo __('Action'); ?> </th>
					</tr>
				</thead>
				
				<tfoot>
					<tr>
						<th> <?php echo __('Notice Title'); ?> </th>
						<th> <?php echo __('Notice Comment'); ?> </th>
						<th> <?php echo __('Notice Start Date'); ?> </th>
						<th> <?php echo __('Notice End Date'); ?> </th>
						<th> <?php echo __('Notice For'); ?> </th>
						<th> <?php echo __('Action'); ?> </th>
					</tr>
				</tfoot>
				<tbody>

					<?php 
						$stud_date = $this->Setting->getfieldname('date_format');
						if($role=='student')
						{
							foreach($notice_data as $fetch_data):  ?>
							
							<tr>
								<td><?php echo $fetch_data['notice_title'].''.$this->Form->input('',['type'=>'hidden','id'=>'noticeid','class'=>'noticeid','value'=>$fetch_data['notice_id']]); ?></td>
								<Td><?php echo $fetch_data['notice_comment']; ?></td>
								<td><?php echo date($stud_date,strtotime($fetch_data['notice_start_date'])); ?></td>
								<td><?php echo date($stud_date,strtotime($fetch_data['notice_end_date'])); ?></td>
								<td>
								<?php 
									
									if($fetch_data['notice_for']=='student')
										echo __('Student');
									if($fetch_data['notice_for']=='all')
										echo __('All');
							
								?>																		
								</td>							
								<td>
								<button type="button" id="<?php echo $fetch_data['notice_id']; ?>" data-toggle="modal" data-target="#myModal1" class="btn btn-primary viewdetail"> <?php echo __('View'); ?> </button>
								</td>
							</tr>
						<?php endforeach; 
						}
						if($role=='supportstaff')
						{
							foreach($notice_data_staff as $fetch_data):  ?>
							
							<tr>
								<td><?php echo $fetch_data['notice_title'].''.$this->Form->input('',['type'=>'hidden','id'=>'noticeid','class'=>'noticeid','value'=>$fetch_data['notice_id']]); ?></td>
								<Td><?php echo $fetch_data['notice_comment']; ?></td>
								<td><?php echo date($stud_date,strtotime($fetch_data['notice_start_date'])); ?></td>
								<td><?php echo date($stud_date,strtotime($fetch_data['notice_end_date'])); ?></td>
								<td>
								<?php 
									
									if($fetch_data['notice_for']=='support Staff')
										echo __('Support Staff');
									if($fetch_data['notice_for']=='all')
										echo __('All');
							
								?>																		
								</td>							
								<td>
								<button type="button" id="<?php echo $fetch_data['notice_id']; ?>" data-toggle="modal" data-target="#myModal1" class="btn btn-primary viewdetail"> <?php echo __('View'); ?> </button>
								</td>
							</tr>
						<?php endforeach; 
						}
						if($role=='teacher')
						{
							foreach($rows as $fetch_data):  ?>
							
							<tr>
								<td><?php echo $fetch_data['notice_title'].''.$this->Form->input('',['type'=>'hidden','id'=>'noticeid','class'=>'noticeid','value'=>$fetch_data['notice_id']]); ?></td>
								<Td><?php echo $fetch_data['notice_comment']; ?></td>
								<td><?php echo date($stud_date,strtotime($fetch_data['notice_start_date'])); ?></td>
								<td><?php echo date($stud_date,strtotime($fetch_data['notice_end_date'])); ?></td>
								<td><?php echo __(ucwords($fetch_data['notice_for']));?></td>			
								<td>
								<button type="button" id="<?php echo $fetch_data['notice_id']; ?>" data-toggle="modal" data-target="#myModal1" class="btn btn-primary viewdetail"> <?php echo __('View'); ?> </button>
								</td>
							</tr>
						<?php endforeach;
						}
							if($role ==  'parent'){
								foreach ($notice_parent as $fetch_data) {
								?>

									<tr>
								<td><?php echo $fetch_data['notice_title'].''.$this->Form->input('',['type'=>'hidden','id'=>'noticeid','class'=>'noticeid','value'=>$fetch_data['notice_id']]); ?></td>
								<Td><?php echo $fetch_data['notice_comment']; ?></td>
								<td><?php echo date($stud_date,strtotime($fetch_data['notice_start_date'])); ?></td>
								<td><?php echo date($stud_date,strtotime($fetch_data['notice_end_date'])); ?></td>
								<td><?php echo __(ucwords($fetch_data['notice_for']));?></td>			
								<td>
								<button type="button" id="<?php echo $fetch_data['notice_id']; ?>" data-toggle="modal" data-target="#myModal1" class="btn btn-primary viewdetail"> <?php echo __('View'); ?> </button>
								</td>
							</tr>

								<?php
								}
							}
						?>


				</tbody>
				</table>


		</div>
	</div>
</div>