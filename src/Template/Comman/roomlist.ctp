<?php
use Cake\Routing\Router;
?>
<!--- start checkbox js ---->
<script>
	$(function(){

	/* $('.ch_pend').click(function() { */
	$('#del_room').click(function() {
			/* if($(this).is(":checked")) { */
			if($(".ch_pend").is(":checked")) {	

			swal({   
				title: "Are You Sure?",
				text: "Are you sure you want to delete this?",   
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#297FCA",   
				confirmButtonText: "Yes, delete!",
				cancelButtonText: "No, cancel it!",	
				closeOnConfirm: false,
				closeOnCancel: false
			}, function(isConfirm){
				if (isConfirm)
				{
					swal("Deleted!", "Your records has been deleted.", "success");		
	
					var get_id = $('.ch_pend:checked').map(function() {
					return this.attributes.dataid.textContent;
					}).get()
					get_id = JSON.stringify(get_id);
					data={e_id:get_id	};	

					jQuery.ajax({
					type:"POST",
					url:"<?php echo Router::url(array('controller'=>'Hostel','action'=>'roommultidelete'));?>",
					data:data,
					async:false,
					success: function(response){
							location.reload();

					},
					error: function (e) {
					},
					beforeSend:function(){
					$(this).hide();
					},
					complete:function(e){
					console.log(e.responseText);
					
					}
					});	
				}
				else {	 
					swal("Cancelled", "Not removed!", "error"); 
				}
			});
			}
	  });

	});
</script>

<!--- checkbox js ---->
<script>
$(document).ready(function(){
    $('#select_all').on('click',function(){
        if(this.checked){
            $('.checkbox').each(function(){
                this.checked = true;
            });
        }else{
             $('.checkbox').each(function(){
                this.checked = false;
            });
        }
    });
    
    $('.checkbox').on('click',function(){
        if($('.checkbox:checked').length == $('.checkbox').length){
            $('#select_all').prop('checked',true);
        }else{
            $('#select_all').prop('checked',false);
        }
    });
});
</script>
<!--- end checkbox js ---->


<script>
		$(document).ready(function() {
			$('#exportlist').DataTable({responsive: true});
		} );
	</script>
<div class="row schooltitle">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">
		<li class="">
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Hostel List'),array('controller'=>'Comman','action' => 'hostellist'),array('escape' => false));?>
		</li>
		
		<li class="active">
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Room List'),array('controller'=>'Comman','action' => 'roomlist'),array('escape' => false));?>
		</li>
		
		<li class="">
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Beds List'),array('controller'=>'Comman','action' => 'bedslist'),array('escape' => false));?>
		</li>
		
	</ul>
</div>
<?php
if(isset($it))
{
	if(!empty($it->toArray()))
	{
		$stud_date = $this->Setting->getfieldname('date_format');
	?>
<div class="panel-body">	
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="exportlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr>
						
						<th> <?php echo __('Room Unique ID'); ?> </th>
						<th> <?php echo __('Hostel Name'); ?> </th>
						<th> <?php echo __('Room Category'); ?> </th>
						<th> <?php echo __('Bed capacity'); ?> </th>
						<th> <?php echo __('Availability'); ?> </th>
						<th> <?php echo __('Description'); ?> </th>
						<th> <?php echo __('Action'); ?> </th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						
						<th> <?php echo __('Room Unique ID'); ?> </th>
						<th> <?php echo __('Hostel Name'); ?> </th>
						<th> <?php echo __('Room Category'); ?> </th>
						<th> <?php echo __('Bed capacity'); ?> </th>
						<th> <?php echo __('Availability'); ?> </th>
						<th> <?php echo __('Description'); ?> </th>
						<th> <?php echo __('Action'); ?> </th>
					</tr>
				</tfoot>
				<tbody>

					<?php foreach($it as $get_data): 
					
					$stud_date = $this->Setting->getfieldname('date_format');
					$room_cnt = 0;
					?>
					<tr>
						
						<td><?php echo $get_data['room_unique_id']; ?></td>
						<td><?php echo $this->Setting->hostel_name($get_data['hostel_id']); ?></td>
						<td><?php echo $this->Setting->hostel_room_category_name($get_data['room_category']); ?></td>
						<td><?php echo $get_data['beds_capacity']; ?></td>
						<?php 
						$room_cnt = $this->Setting->hostel_room_count_bed_unique_id($get_data['room_unique_id']);
						if($get_data['beds_capacity'] == $room_cnt)
						{
						?>
						<td><label style='margin: 0;line-height: 25px;background-color:red;color:white;padding:0px 10px;'><?php echo __('Occupied');?></label></td>
						<?php
						}
						else 
						{?>
							<td><label style='margin: 0;line-height: 25px;background-color:green;color:white;padding:0px 10px;'><?php echo __('Available');?></label></td>
						<?php 
						}
						?>
						<td><?php echo $get_data['room_desc']; ?></td>
						<td>
						<?php
						$user_id=$this->request->session()->read('user_id');
						$room = $this->Setting->hostel_room_student_room_unique_id($user_id);
						if($get_data['room_unique_id'] == $room['room_unique_id']){		
						echo $this->Html->link(__('View or Assign Room'),array('controller'=>'Comman','action' => 'assignroom',$this->Setting->my_simple_crypt($get_data['room_id'],'e')),array('class'=>'btn btnview btn-default'));
						}
						?></td>
					
					</tr>
				<?php endforeach; ?>
				</tbody>
				</table>
				
		</div>
	</div>
</div>
<?php
	}
	else{
		?>
		<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Room Data Available');?></h4></div>
<?php		
	}
}
?>