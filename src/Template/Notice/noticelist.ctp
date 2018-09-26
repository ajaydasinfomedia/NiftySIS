<?php
use Cake\Routing\Router;
?>
<!--- start checkbox js ---->
<script>
	$(function(){

	/* $('.ch_pend').click(function() { */
	$('#note').click(function() {
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
					url:"<?php echo Router::url(array('controller'=>'Notice','action'=>'noticemultidelete'));?>",
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
		jQuery(document).ready(function() {
		jQuery('#examlist').DataTable({
				responsive: true,
				"order":[[0,"desc"]],
			});
		
		jQuery('body').on('click', '.viewdetail', function() {
			var get_id=jQuery(this).attr('id');

			$.ajax({

					type:'POST',
					url:'<?php echo $this->Url->build(["controller"=>"Notice","action"=>"view"]); ?>',
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
<!-- end  -->

	<div class="modal fade " id="myModal1" role="dialog">
    <div class="modal-dialog modal-md"  >

      <div class="modal-content">
        <div class="modal-header" >
			<span type="button" class="" data-dismiss="modal"><?php echo __("Notice Details");?></span>
			<a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
        </div>
        <div class="modal-body" >
		
		
        </div>
        
        </div>
      </div>
      
    </div>
  



				<!-- -->



<div class="row schooltitle">			
				<ul role="tablist" class="nav nav-tabs panel_tabs">
					  <li class="active">
					  
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Notice List'),array('controller'=>'Notice','action' => 'noticelist'),array('escape' => false));
						?>
						
						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->
						  
					  </li>
					  
					   <li class="">
					  
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Notice'),array('controller'=>'Notice','action' => 'addnotice'),array('escape' => false));
						?>
						
						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->
						  
					  </li>
				</ul>
</div>
<?php
if(isset($rows))
{
	if(!empty($rows->toArray()))
	{
	?>
<div class="panel-body">	
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr><th><input type="checkbox" id="select_all" name="select_all" /></th>
						<th><?php echo __('Notice Title'); ?> </th>
						<th><?php echo __('Notice Comment'); ?> </th>
						<th><?php echo __('Notice Start Date'); ?> </th>
						<th><?php echo __('Notice End Date'); ?> </th>
						<th><?php echo __('Notice For'); ?> </th>
						<th><?php echo __('Class'); ?> </th>
						<th><?php echo __('Action'); ?> </th>
					</tr>
				</thead>
				
				<tfoot>
					<tr><th></th>
						<th><?php echo __('Notice Title'); ?> </th>
						<th><?php echo __('Notice Comment'); ?> </th>
						<th><?php echo __('Notice Start Date'); ?> </th>
						<th><?php echo __('Notice End Date'); ?> </th>
						<th><?php echo __('Notice For'); ?> </th>
						<th><?php echo __('Class'); ?> </th>
						<th><?php echo __('Action'); ?> </th>
					</tr>
				</tfoot>
				<tbody>

					<?php foreach($rows as $fetch_data): 

						$stud_date = $this->Setting->getfieldname('date_format');

					?>
					
					<tr>
						<td>
						<p style='display:none;'><?php echo $fetch_data['notice_id'];?></p>
						<input type="checkbox" class="checkbox ch_pend" name="id[]" dataid="<?php echo $fetch_data['notice_id'];  ?>"> </td>
						<td><?php echo $fetch_data['notice_title'].''.$this->Form->input('',['type'=>'hidden','id'=>'noticeid','class'=>'noticeid','value'=>$fetch_data['notice_id']]); ?></td>
						<Td><?php echo $fetch_data['notice_comment']; ?></td>
						<td><?php echo date($stud_date,strtotime($fetch_data['notice_start_date'])); ?></td>
						<td><?php echo date($stud_date,strtotime($fetch_data['notice_end_date'])); ?></td>
						<td><?php echo $fetch_data['notice_for'];?></td>
						<td><?php 
								$print_something='';

								if($fetch_data['which_class'] == 'All'){
									$print_something= $fetch_data['which_class'];
								}else{

										foreach ($class_row as $fetch_data_class) {
											if($fetch_data['which_class'] == $fetch_data_class['class_id']){

											$print_something=$fetch_data_class['class_name'];
										}
									}
								}					
								echo $print_something;	

						

						?></td>
						<td>

	<button type="button" id="<?php echo $fetch_data['notice_id']; ?>" data-toggle="modal" data-target="#myModal1" class="btn btn-primary viewdetail">
								<?php echo __('View'); ?> </button>




	<?php 
	echo $this->Html->link(__('Edit'),array('controller'=>'Notice','action' => 'updatenotice', $this->Setting->my_simple_crypt($fetch_data['notice_id'],'e')),array('class'=>'btn btnview btn-info'))
	."&nbsp;".
	$this->Form->button( __('Delete'),['action'=>'#','url'=>$this->request->base.'/'.$this->name.'/delete/'.$fetch_data['notice_id'],'class'=>'btn btn-danger sa-warning']);
	?></td>
					
					</tr>
				<?php 

				endforeach; ?>
				</tbody>
				</table>
				<tr>
					<td><button type="button" class="btn btn-danger" id="note"><?php echo __('Delete Selected'); ?></button></td>
				</tr>

		</div>
	</div>
</div>
<?php
	}
	else{
		?>
		<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Notice Data Available');?></h4></div>
<?php		
	}
}
?>

