<?php
use Cake\Routing\Router;
?>
<!--- start checkbox js ---->
<script>
	$(function(){

	/* $('.ch_pend').click(function() { */
	$('#del_hostel').click(function() {
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
					url:"<?php echo Router::url(array('controller'=>'Hostel','action'=>'hostelmultidelete'));?>",
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
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Hostel List'),array('controller'=>'Hostel','action' => 'hostellist'),array('escape' => false));?>
		</li>
		<li class="">
			<?php  
			if(isset($edit))
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Edit Hostel'),array('controller'=>'Hostel','action' => 'addhostel',$row['hostel_id']),array('escape' => false));
			else
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Hostel'),array('controller'=>'Hostel','action' => 'addhostel'),array('escape' => false));
			?>
		</li>
		<li class="">
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Room List'),array('controller'=>'Hostel','action' => 'roomlist'),array('escape' => false));?>
		</li>
		<li class="">
			<?php  
			if(isset($edit))
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Edit Room'),array('controller'=>'Hostel','action' => 'addroom',$row['room_id']),array('escape' => false));
			else
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Room'),array('controller'=>'Hostel','action' => 'addroom'),array('escape' => false));
			?>
		</li>
		<li class="">
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Beds List'),array('controller'=>'Hostel','action' => 'bedslist'),array('escape' => false));?>
		</li>
		<li class="">
			<?php  
			if(isset($edit))
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Edit Beds'),array('controller'=>'Hostel','action' => 'addbeds',$row['beds_id']),array('escape' => false));
			else
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Beds'),array('controller'=>'Hostel','action' => 'addbeds'),array('escape' => false));
			?>
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
						<th><input type="checkbox" id="select_all" name="select_all" /></th>
						<th> <?php echo __('Hostel Name'); ?> </th>
						<th> <?php echo __('Hostel Type'); ?> </th>
						<th> <?php echo __('Description'); ?> </th>
						<th> <?php echo __('Action'); ?> </th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th></th>
						<th> <?php echo __('Hostel Name'); ?> </th>
						<th> <?php echo __('Hostel Type'); ?> </th>
						<th> <?php echo __('Description'); ?> </th>
						<th> <?php echo __('Action'); ?> </th>
					</tr>
				</tfoot>
				<tbody>

					<?php foreach($it as $get_data): 
					
					$stud_date = $this->Setting->getfieldname('date_format');
					
					?>
					<tr>
						<td><input type="checkbox" class="checkbox ch_pend" name="id[]" dataid="<?php echo $get_data['hostel_id'];  ?>"> </td>
						<td><?php echo $get_data['hostel_name']; ?></td>
						<td><?php echo $get_data['hostel_type']; ?></td>
						<td><?php echo $get_data['hostel_desc']; ?></td>
						<td>
						<?php 
						echo $this->Html->link(__('Edit'),array('controller'=>'Hostel','action' => 'addhostel',$this->Setting->my_simple_crypt($get_data['hostel_id'],'e')),array('class'=>'btn btnview btn-info')) 
						."&nbsp;".
						$this->Form->button( __('Delete'),['action'=>'#','url'=>$this->request->base.'/'.$this->name.'/delete/'.$get_data['hostel_id'],'class'=>'btn btn-danger sa-warning']);
						?></td>
					
					</tr>
				<?php endforeach; ?>
				</tbody>
				</table>
				<tr>
					<td><button type="button" class="btn btn-danger" id="del_hostel"> <?php echo __('Delete Selected'); ?> </button></td>
				</tr>
		</div>
	</div>
</div>
<?php
	}
	else{
		?>
		<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Hostel Data available');?></h4></div>
<?php		
	}
}
?>