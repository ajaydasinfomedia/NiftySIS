<?php
use Cake\Routing\Router;
?>
<!--- start checkbox js ---->
<script>
	$(function(){

	/* $('.ch_pend').click(function() { */
	$('#hall').click(function() {
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
					data={h_id:get_id	};	


					jQuery.ajax({
					type:"POST",
					url:"<?php echo Router::url(array('controller'=>'Hall','action'=>'hallmultidelete'));?>",
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
		$('#gradelist').DataTable({
				responsive: true,
				"order":[[0,"desc"]],
			});
		} );
	</script>
<div class="row schooltitle">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">
		<li class="active">
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Exam Hall List'),array('controller'=>'Hall','action' => 'halllist'),array('escape' => false));?>
		</li>
		<li class="">
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Exam Hall'),array('controller'=>'Hall','action' => 'addhall'),array('escape' => false));?>
		</li>
		<li class="">
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Exam Hall Receipt'),array('controller'=>'Hall','action' => 'examhallreceipt'),array('escape' => false));?>
		</li>
	</ul>
</div>
<?php
if(isset($row))
{
	if(!empty($row->toArray()))
	{
	?>
<div class="panel-body">	
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="gradelist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr><th><input type="checkbox" id="select_all" name="select_all" /></th>
						<th> <?php echo __('Exam Hall Name'); ?> </th>
						<th> <?php echo __('Exam Hall Numeric Value'); ?> </th>
						<th> <?php echo __('Description'); ?> </th>
						<th> <?php echo __('Action'); ?> </th>
					</tr>
				</thead>
				<tfoot>
					<tr><th></th>
						<th> <?php echo __('Exam Hall Name'); ?> </th>
						<th> <?php echo __('Exam Hall Numeric Value'); ?> </th>
						<th> <?php echo __('Description'); ?> </th>
						<th> <?php echo __('Action'); ?> </th>
					</tr>
				</tfoot>
				<tbody>

					<?php foreach($row as $get_data): ?>
					<tr>
						<td>
						<p style='display:none;'><?php echo $get_data['hall_id'];?></p>
						<input type="checkbox" class="checkbox ch_pend" name="id[]" dataid="<?php echo $get_data['hall_id'];  ?>"> </td>
						<td><?php echo $get_data['hall_name']; ?></td>
						<td><?php echo $get_data['number_of_hall']; ?></td>
						<td><?php echo $get_data['description'];?></td>
						<td>
						<?php 
						echo $this->Html->link(__('Edit'),array('controller'=>'Hall','action' => 'updatehall',$this->Setting->my_simple_crypt($get_data['hall_id'],'e')),array('class'=>'btn btnview btn-info')) 
						."&nbsp;".
						$this->Form->button( __('Delete'),['action'=>'#','url'=>$this->request->base.'/'.$this->name.'/delete/'.$get_data['hall_id'],'class'=>'btn btn-danger sa-warning']);
						?></td>
					
					</tr>
					<?php endforeach; ?>	
				</tbody>
				</table>
				<tr>
					<td><button type="button" class="btn btn-danger" id="hall"> <?php echo __('Delete Selected'); ?> </button></td>
				</tr>
		</div>
	</div>
</div>
<?php
	}
	else{
		?>
		<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Exam Hall Data Available');?></h4></div>
<?php		
	}
}
?>