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
					data={t_id:get_id	};	


					jQuery.ajax({
					type:"POST",
					url:"<?php echo Router::url(array('controller'=>'Teacher','action'=>'teachermultidelete'));?>",
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


<script>
		$(document).ready(function() {
		$('#teacherlist').DataTable({
			responsive: true,
			"order":[[0,"desc"]],
			});
		} );
	</script>
<div class="row schooltitle">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Teacher List'),['controller' => 'Teacher', 'action' => 'teacherlist'],['escape' => false]);?>
		</li>
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Teacher'),['controller' => 'Teacher', 'action' => 'addteacher'],['escape' => false]);?>
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
			<table id="teacherlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th><input name="select_all" value="all" id="select_all" type="checkbox" /></th> 
						<th><?php echo __('Photo');?></th>
						<th><?php echo __('Teacher Name');?></th>
						<th><?php echo __('Class Name');?></th>
						<th><?php echo __('Subject Name');?></th>
						<th><?php echo __('Email');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th></th>
						<th><?php echo __('Photo');?></th>
						<th><?php echo __('Teacher Name');?></th>
						<th><?php echo __('Class Name');?></th>
						<th><?php echo __('Subject Name');?></th>
						<th><?php echo __('Email');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
				</tfoot>
				<tbody>
					<tr>
						
							<?php 
								$a=0;
								foreach($it as $it2):
								{
								
									$name=$it2['first_name']." ".$it2['last_name'];
									?>
									<td> 
									<p style='display:none;'><?php echo $it2['user_id'];?></p>
									<input type="checkbox" class="checkbox ch_pend" name="id[]" dataid="<?php echo $it2['user_id'];  ?>"> </td>
									<?php
									echo "<td>" .$this->Html->image($it2['image'],array('height'=>'50px','width'=>'50px','class'=>'profileimg')) . "</td>";
									echo "<td>" .__($name). "</td>";
									echo "<td>" .__($this->Setting->get_teacher_class_list($it2['user_id'])) . "</td>";
									echo "<td>" .__($subname[$a]) . "</td>";
									echo "<td>" .__($it2['email']) . "</td>";	
									echo "<td>".
									$this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-eye')). __('View Details'),['action'=>'#','data-toggle'=>'modal','data-target'=>'#myModalview','viewID'=>$it2['user_id'],'class'=>'btn btn-primary viewdetail'],['escape' => false]).
									"&nbsp;".
									$this->Html->link(__('Edit'),array('action' => 'updateteacher', $this->Setting->my_simple_crypt($it2['user_id'],'e')),array('class'=>'btn btnview btn-info'))
									."&nbsp;".
									$this->Form->button( __('Delete'),['action'=>'#','url'=>$this->request->base.'/'.$this->name.'/delete/'.$it2['user_id'],'class'=>'btn btn-danger sa-warning']).
									"&nbsp;".
									$this->Html->link($this->Html->tag('i'," ", array('class' => 'fa fa-eye')).__(' View Attendance'),array('controller'=>'Teacher','action' => 'teacherattendance', $this->Setting->my_simple_crypt($it2['user_id'],'e')),array('class'=>'btn btnview btn-default','escape' => false))
									."</td>";
									
									$a=$a+1;
								}
								
							?>
					</tr>
					<?php endforeach;
							?>
				</tbody>
				</table>
					<tr>
						<td><button type="button" class="btn btn-danger" id="abc"><?php echo __('Delete Selected'); ?> </button></td>
					</tr>
		</div>
	</div>
</div>
<?php
	}
	else{
		?>
		<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Teacher Data Available');?></h4></div>
<?php		
	}
}
?>
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