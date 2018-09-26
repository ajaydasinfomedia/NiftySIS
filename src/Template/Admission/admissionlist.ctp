<?php
use Cake\Routing\Router;
?>

<script>
	$(function(){

	/* $('.ch_pend').click(function() { */
	$('#abc').click(function() {
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
					data={a_id:get_id};

					jQuery.ajax({
					type:"POST",
					url:"<?php echo Router::url(array('controller'=>'Admission','action'=>'admissionmultidelete'));?>",
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

<script>
		$(document).ready(function() {
		$('#admissionlist').DataTable({responsive: true});
		} );
	jQuery('#delete_selected').on('click', function(){
		 var c = confirm('Are you sure to delete?');
		if(c){
			jQuery('#frm-example').submit();
		}
		
	});
	
</script>

<script>

$( document ).ready(function(){

    $('body').on('click', '.save', function() {
	   
	   $('.modal-body').html("");
	   var str = $(this).attr("data-id"); 
       $.ajax({
       type: 'POST',
       url: '<?php echo $this->Url->build([
"controller" => "Admission",
"action" => "approve"]);?>',
	
       data : {id:str},
       success: function (data)
       {            

			  $('.modal-body').html(data);
				
   },
error: function(e) {
       alert("An error occurred: " + e.responseText);
       console.log(e);	
}

       });

       });
	      

   });

</script>

<div class="row schooltitle">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Admission List'),['controller' => 'Admission', 'action' => 'admissionlist'],['escape' => false]);?>
		</li>
		<li class="">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus fa-lg')) . __('Admission Form'),['controller' => 'Admission', 'action' => 'registration'],['escape' => false]);?>
		</li>

	</ul>	
</div>
<?php
if(isset($it))
{
	if(!empty($it->toArray()))
	{
	?>
<div class="panel-body">	
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="admissionlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr><th><input type="checkbox" id="select_all" name="select_all" /></th> 
						<th><?php echo __('Student Name');?></th>
						<th><?php echo __('Gender');?></th>
						<th><?php echo __('Address');?></th>
						<th><?php echo __('Phone');?></th>
						<th><?php echo __('Student Email');?></th>
						<th><?php echo __('Previous School');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th></th>
						<th><?php echo __('Student Name');?></th>
						<th><?php echo __('Gender');?></th>
						<th><?php echo __('Address');?></th>
						<th><?php echo __('Phone');?></th>
						<th><?php echo __('Student Email');?></th>
						<th><?php echo __('Previous School');?></th>
						<th><?php echo __('Action');?></th>
					</tr>	
				</tfoot>
				<tbody>
					<tr>
						
							<?php 
							$i = 0;
							
							$result_cnt = 0;
								foreach($it as $it2):
								{
									
									$name=$it2['first_name']." ".$it2['last_name'];
									?>
									<td> <input type="checkbox" class="checkbox ch_pend" name="id[]" dataid="<?php echo $it2['admission_id'];  ?>"> </td>
									<?php
									echo "<td>" .__($name). "</td>";
									echo "<td>" .__(($it2['gender'] == 'male')?'Male':'Female') . "</td>";
									echo "<td>" .__($it2['address']) . "</td>";
									echo "<td>" .__($it2['mobile_no']) . "</td>";
									echo "<td>" .__($it2['email']) . "</td>";
									echo "<td>" .__($this->Setting->get_admission_value($it2['preschoolname'])) . "</td>";
									echo "<td>".										
										/* $this->Html->link(__('View Details'),array('action' => 'userdetails', $this->Setting->my_simple_crypt($it2['admission_id'],'e')),array('class'=>' btn btnview btn-primary'))."&nbsp;". */
										$this->Form->button($this->Html->tag('i',"&nbsp;", array('', $it2['admission_id'])). __('Approve'),['action'=>'approve','data-toggle'=>'modal','data-id'=>$it2['admission_id'],'data-target'=>'#myModal','class'=>'btn btn-default save'],['escape' => false])." ";						
									"</td>";
								$i++;
								}
								
							?>
					</tr>
					<?php endforeach;
							?>
										
				</tbody>
					
				</table>
					<tr>
						<td><button type="button" class="btn btn-danger" id="abc"><?php echo __('Delete Selected');?> </button></td>
					</tr>
		</div>
	</div>
</div>
<?php
	}
	else{
		?>
		<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Admission Data Available');?></h4></div>
<?php		
	}
}
?>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      
	  <div class="modal-header"> 
			<a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
			<h4 id="myLargeModalLabel" class="modal-title">
			<?php 
			$school_name = $this->Setting->getfieldname('school_name');
			echo $school_name; 
			?>
			</h4>
		</div>
	  
      <div class="modal-body">
		
		
      </div>
	  <div>
	  <legend></legend>
	  </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-default" data-toggle="collapse" data-dismiss="modal"><?php echo __('Close'); ?></button>
      </div>
    </div>

  </div>
</div>

<script>
$(document).ready(function(){
	$('.panel-title').click(function(){
		
		$('.panel-collapse.collapse').css('display','none');
		$('.panel-title').closest('.panel-collapse.collapse').css('display','block');
	
	});
	
});

</script>