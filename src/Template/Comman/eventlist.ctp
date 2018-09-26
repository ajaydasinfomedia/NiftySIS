<?php
use Cake\Routing\Router;
?>
<!--- start checkbox js ---->
<script>
	$(function(){

	/* $('.ch_pend').click(function() { */
	$('#del_event').click(function() {
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
					data={n_id:get_id	};	


					jQuery.ajax({
					type:"POST",
					url:"<?php echo Router::url(array('controller'=>'Event','action'=>'eventmultidelete'));?>",
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
		$('#examlist').DataTable({
				responsive: true,
				"order":[[0,"desc"]],
			});
		} );
	</script>
<div class="row schooltitle">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">
		<li class="active">							
		<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Event List'),array('controller'=>'Comman','action' => 'eventlist'),array('escape' => false));?>					  
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
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr>
						
						<th> <?php echo __('Event List'); ?> </th>
						<th> <?php echo __('Description'); ?> </th>
						<th> <?php echo __('Start Date'); ?> </th>
						<th> <?php echo __('End Date'); ?> </th>
						
					</tr>
				</thead>
				<tfoot>
					<tr>
						
						<th> <?php echo __('Event List'); ?> </th>
						<th> <?php echo __('Description'); ?> </th>
						<th> <?php echo __('Start Date'); ?> </th>
						<th> <?php echo __('End Date'); ?> </th>
						
					</tr>
				</tfoot>
				<tbody>

					<?php foreach($it as $get_data): 
					
					$stud_date = $this->Setting->getfieldname('date_format');
					
					?>
					<tr>
						
						<td>
						<p style='display:none;'><?php echo $get_data['event_id'];?></p>
						<?php echo $get_data['event_title']; ?></td>
						<td><?php echo $get_data['event_desc']; ?></td>
						<td><?php echo date($stud_date,strtotime($get_data['start_date'])); ?></td>
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
	else{
		?>
		<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Event Data Available.');?></h4></div>
<?php		
	}
}
?>