<?php
use Cake\Routing\Router;
?>
<script>
$(document).ready(function(){
	$('#homeworklist').DataTable({responsive: true});
});
</script>
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
$(function()
{		
	$('#abc').click(function() 
	{		
		if($(".ch_pend").is(":checked")) 
		{	
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
					data={i_id:get_id};	

					jQuery.ajax({
						type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Homework','action'=>'multidelete'));?>",
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

<div class="row schooltitle">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Homework List'),['controller' => 'Homework', 'action' => 'homeworklist'],['escape' => false]);?>
		</li>
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Homework'),['controller' => 'Homework', 'action' => 'addhomework'],['escape' => false]);?>
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
			<table id="homeworklist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr>				
						<th><input type="checkbox" id="select_all" name="select_all" /></th> 
						<th><?php echo __('Homework Title'); ?></th>
						<th><?php echo __('Class Name');?></th>
						<th><?php echo __('Subject Name');?></th>
						<th><?php echo __('Created Date');?></th>
						<th><?php echo __('Submission Date');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>					
						<th></th>
						<th><?php echo __('Homework Title'); ?></th>
						<th><?php echo __('Class Name');?></th>
						<th><?php echo __('Subject Name');?></th>
						<th><?php echo __('Created Date');?></th>
						<th><?php echo __('Submission Date');?></th>
						<th><?php echo __('Action');?></th>
					</tr>															
				</tfoot>
				<tbody>
					<tr>						
						<?php 
						
						$stud_date = $this->Setting->getfieldname('date_format');
						
						foreach($it as $it2):
						
							
						?>
							<td> 
								<input type="checkbox" class="checkbox ch_pend" name="id[]" dataid="<?php echo $it2['homework_id'];  ?>"> 
							</td>
							<td><?php echo $it2['title'];?></td>
							<td><?php echo $this->Setting->get_class_id($it2['class_id']);?></td>
							<td><?php echo $this->Setting->get_subject_name($it2['subject_id']);?></td>
							<td><?php echo date($stud_date,strtotime($it2['created_date']));?></td>
							<td><?php echo date($stud_date,strtotime($it2['submission_date']));?></td>
						<?php
							echo "<td>".$this->Html->link(__('Edit'),array('action' => 'addhomework', $this->Setting->my_simple_crypt($it2['homework_id'],'e')),array('class'=>'btn btnview btn-info'))
							."&nbsp;".
							$this->Form->button( __('Delete'),['action'=>'#','url'=>$this->request->base.'/'.$this->name.'/delete/'.$it2['homework_id'],'class'=>'btn btn-danger sa-warning'])
							."&nbsp;".
							$this->Html->link($this->Html->tag('i'," ", array('class' => 'fa fa-eye')).__(' View Submission'),array('action' => 'viewsubmission', $this->Setting->my_simple_crypt($it2['homework_id'],'e')),array('class'=>'btn btnview btn-default','escape' => false));
							
							if($it2['syllabus'] == NULL || $it2['syllabus'] == '')
							{
								echo '';
							}else{
							$file = WWW_ROOT.'syllabus'.'/'.$it2['syllabus'];
							$file1 =$this->request->webroot.'syllabus/'.$it2['syllabus'];
							echo "&nbsp";
							echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-download fa-lg')) . __('Download Syllabus'),['action' => 'readfile',$it2['syllabus']],['escape' => false,'class'=>'btn btn-default']);
							
							?>
							<a href="<?php echo $file1;?>" target="_blank" class="btn btn-default"><i class="fa fa-eye"></i><?php echo " ".__('View Syllabus'); ?></a> 						
							<?php
							}
							echo "</td>";
						
						?>
					</tr>
					<?php endforeach;?>			
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
		<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Homework Data available');?></h4></div>
<?php		
	}
}
?>