<?php
use Cake\Routing\Router;
?>
<!--- start checkbox js ---->
<script>
	$(function(){

	/* $('.ch_pend').click(function() { */
	$('#fee').click(function() {
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
					data={f_id:get_id	};	


					jQuery.ajax({
					type:"POST",
					url:"<?php echo Router::url(array('controller'=>'Feepayment','action'=>'feepaymentmultidelete'));?>",
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

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Fee Type List'),array('controller'=>'Feepayment','action' => 'feetypelist'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>

					   <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Fee Type'),array('controller'=>'Feepayment','action' => 'addfeetype'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>


					     <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Fees List'),array('controller'=>'Feepayment','action' => 'feelist'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>
					  
					     <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Generate Invoice'),array('controller'=>'Feepayment','action' => 'invoice'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>
				</ul>
</div>
<?php

if(isset($feesrecord))
{
	if(!empty($feesrecord->toArray()))
	{
		$currency = $this->Setting->getfieldname('currency_code');
		$currency_symbol = $this->Setting->get_currency_symbole($currency);
	?>
<div class="panel-body">
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="examlist" class="table" cellspacing="0" width="100%">
				<thead>
					<tr><th><input type="checkbox" id="select_all" name="select_all" /></th>
						<th> <?php echo __('Fee Type'); ?> </th>
						<th> <?php echo __('Class'); ?> </th>
						<th> <?php echo __('Section'); ?> </th>
						<th> <?php echo __('Amount'); ?> </th>
						<th> <?php echo __('Description'); ?> </th>
                        <th> <?php echo __('Action'); ?> </th>
					</tr>
				</thead>
				<tfoot>
					<tr><th></th>
                        <th> <?php echo __('Fee Type'); ?> </th>
						<th> <?php echo __('Class'); ?> </th>
						<th> <?php echo __('Section'); ?> </th>
						<th> <?php echo __('Amount'); ?> </th>
						<th> <?php echo __('Description'); ?> </th>
                        <th> <?php echo __('Action'); ?> </th>
					</tr>
				</tfoot>
				<tbody>

					<?php foreach($feesrecord as $fetch){
									
														
                                                  foreach($class_record as $class){
                                                          if($fetch['class_id'] == $class['class_id']){

                                              foreach($category_record as $fetch_category){
                                                  if($fetch['fees_title_id'] == $fetch_category['category_id']){
													  
												foreach($section_record as $section){
                                                    if($fetch['section'] == $section['class_section_id']){	  
                                        ?>

					<tr>
						<td>
						<p style='display:none;'><?php echo $fetch['fees_id'];?></p>
						<input type="checkbox" class="checkbox ch_pend" name="id[]" dataid="<?php echo $fetch['fees_id'];  ?>"> </td>
						<td><?php echo $fetch_category['category_type'];  ?></td>
						<td><?php echo $class['class_name']; ?></td>
						<td><?php echo $section['section_name']; ?></td>
						<td><?php echo $currency_symbol." ".$fetch['fees_amount']; ?></td>
						<td><?php echo $fetch['description']; ?></td>
						<td>
						<?php 
						echo $this->Html->link(__('Edit'),array('controller'=>'Feepayment','action' => 'addfeetype',$this->Setting->my_simple_crypt($fetch['fees_id'],'e')),array('class'=>'btn btnview btn-info'))
						."&nbsp;".
						$this->Form->button( __('Delete'),['action'=>'#','url'=>$this->request->base.'/'.$this->name.'/feedelete/'.$fetch['fees_id'],'class'=>'btn btn-danger sa-warning']);
						?></td>

					</tr>
				<?php
										}
                                       }
                                      }
                                     }
									}
									}
                                 }  ?>
				</tbody>
				</table>
				<tr>
					<td><button type="button" class="btn btn-danger" id="fee"> <?php echo __('Delete Selected'); ?> </button></td>
				</tr>
		</div>
	</div>
</div>
<?php
	}
	else{
		?>
		<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Fee Type data Available');?></h4></div>
<?php		
	}
}
?>