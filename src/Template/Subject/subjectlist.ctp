<?php
use Cake\Routing\Router;
?>
<script>
	$(function(){

	/* $('.ch_pend').click(function() { */
	$('#sub').click(function() {
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
					data={s_id:get_id	};	


					jQuery.ajax({
					type:"POST",
					url:"<?php echo Router::url(array('controller'=>'Subject','action'=>'subjectmultidelete'));?>",
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
		$('#subjectlist').DataTable({
				responsive: true,
				"order":[[0,"desc"]],
			});
		} );
	</script>
<div class="row schooltitle">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Subject List'),['controller' => 'Subject', 'action' => 'subjectlist'],['escape' => false]);?>
		</li>
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Subject'),['controller' => 'Subject', 'action' => 'addsubject'],['escape' => false]);?>
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
			<table id="subjectlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr><th><input name="select_all" value="all" id="select_all" type="checkbox" /></th> 
						<th><?php echo __('Subject Code');?></th>
						<th><?php echo __('Subject Name');?></th>
						<th><?php echo __('Teacher Name');?></th>
						<th><?php echo __('Class Name');?></th>
						<th><?php echo __('Section Name');?></th>
						<th><?php echo __('Author Name');?></th>
						<th><?php echo __('Edition');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
				</thead>
				<tfoot>
					<tr><th></th>
						<th><?php echo __('Subject Code');?></th>
						<th><?php echo __('Subject Name');?></th>
						<th><?php echo __('Teacher Name');?></th>
						<th><?php echo __('Class Name');?></th>
						<th><?php echo __('Section Name');?></th>
						<th><?php echo __('Author Name');?></th>
						<th><?php echo __('Edition');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
				</tfoot>
				<tbody>
					<tr>
							<?php 
									$a=0;
								foreach($it as $it4):
								{
									?>
									<td> 
									<p style='display:none;'><?php echo $it4['subid'];?></p>
									<input type="checkbox" class="checkbox ch_pend" name="id[]" dataid="<?php echo $it4['subid'];  ?>"> </td>
									<?php
									echo "<td>" . $it4['sub_code'] . "</td>";
									echo "<td>" . $it4['sub_name'] . "</td>";
									echo "<td>" . $id[$a] . "</td>";
									echo "<td>" . $id1[$a] . "</td>";
									echo "<td>" . $id2[$a] . "</td>";
									echo "<td>" . $it4['author_name'] . "</td>";
									echo "<td>" . $it4['edition'] . "</td>";
									echo "<td>".$this->Html->link(__('Edit'),array('action' => 'updatesubject', $this->Setting->my_simple_crypt($it4['subid'],'e')),array('class'=>'btn btnview btn-info')).
									"&nbsp;".
									$this->Form->button( __('Delete'),['action'=>'#','url'=>$this->request->base.'/'.$this->name.'/delete/'.$it4['subid'],'class'=>'btn btn-danger sa-warning']);
								
									if($it4['syllabus'] == NULL || $it4['syllabus'] == '')
									{
										echo '';
									}else{
									$file = WWW_ROOT.'syllabus'.'/'.$it4['syllabus'];
									$file1 =$this->request->webroot.'syllabus/'.$it4['syllabus'];
									echo "&nbsp";
									echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-download fa-lg')) . __('Download'),['action' => 'readfile',$it4['syllabus']],['escape' => false,'class'=>'btn btn-default']);
									
									?>
									<a href="<?php echo $file1;?>" target="_blank" class="btn btn-default"><?php echo __('View'); ?></a> 						
						</td>
						<?php
						}
									$a=$a+1;
							?>
						</tr>
					<?php
					 } endforeach; 
					 ?>
					
							
				</tbody>
				</table>
				<tr>
						<td><button type="button" class="btn btn-danger" id="sub"><?php echo __('Delete Selected'); ?> </button></td>
					</tr>
		</div>
	</div>
</div>
<?php
	}
	else{
		?>
		<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Subject Data Available');?></h4></div>
<?php		
	}
}
?>