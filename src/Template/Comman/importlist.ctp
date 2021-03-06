<?php
use Cake\Routing\Router;
?>
<!--- start checkbox js ---->
<script>
	$(function(){

	/* $('.ch_pend').click(function() { */
	$('#del_export').click(function() {
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
					url:"<?php echo Router::url(array('controller'=>'Export','action'=>'exportmultidelete'));?>",
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
					<li class="active">
						<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Import List'),array('controller'=>'Comman','action' => 'importlist'),array('escape' => false));?>
					</li>
					<li class="">
						<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('New Import'),array('controller'=>'Comman','action' => 'addimport'),array('escape' => false));?>
					</li>
					<li class="">							
						<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Export List'),array('controller'=>'Comman','action' => 'exportlist'),array('escape' => false));?>						  
					 </li>
					 <li class="">						
						<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('New Export'),array('controller'=>'Comman','action' => 'addexport'),array('escape' => false));?>
					
						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->					  
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
						
						<th> <?php echo __('Import Title'); ?> </th>
						<th> <?php echo __('Import Model'); ?> </th>
						<th> <?php echo __('Date'); ?> </th>
						<th> <?php echo __('Action'); ?> </th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						
						<th> <?php echo __('Import Title'); ?> </th>
						<th> <?php echo __('Import Model'); ?> </th>
						<th> <?php echo __('Date'); ?> </th>
						<th> <?php echo __('Action'); ?> </th>
					</tr>
				</tfoot>
				<tbody>

					<?php foreach($it as $get_data): 
					
					$stud_date = $this->Setting->getfieldname('date_format');
					
					?>
					<tr>
						
						<td><?php echo $get_data['import_title']; ?></td>
						<td><?php echo $get_data['import_model']; ?></td>
						<td><?php echo date($stud_date,strtotime($get_data['created_date'])); ?></td>
						<td>
						<?php 
						echo $this->Html->link(__('Edit'),array('action' => '#'),array('class'=>'edit-term btn btnview btn-info','id'=>'view_section','data-toggle'=>'modal','data-target'=>'#load_modal','data-id'=>$get_data['export_id']));						
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
		<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Data available for Import');?></h4></div>
<?php		
	}	
}
?>
<div class="modal modal-white in custom-model" id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<script>
jQuery("body").on("click", ".edit-term", function(){
	var term_id  = jQuery(this).attr('data-id') ;		

	var curr_data = {					
			class_section_id:term_id,			
			dataType: 'json'
			};
			
			jQuery.ajax({
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Comman','action'=>'importedit'));?>",
				data:curr_data,
				async:false,
				success: function(response){ 								   
					jQuery('.modal-content').html(response);
					
				},
				beforeSend:function(){
							jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
						},
				error: function(e) {
						console.log(e);
						 }
			});			
});
</script>