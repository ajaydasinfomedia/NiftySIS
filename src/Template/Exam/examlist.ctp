<?php
use Cake\Routing\Router;
?>
<!--- start checkbox js ---->
<script>
	$(function(){

	/* $('.ch_pend').click(function() { */
	$('#exa').click(function() {
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
					url:"<?php echo Router::url(array('controller'=>'Exam','action'=>'exammultidelete'));?>",
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
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Exam List'),['controller'=>'Exam','action' => 'examlist'],['escape' => false]);?>
		</li>
		<li class="">
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Exam'),['controller'=>'Exam','action' => 'addexam'],['escape' => false]);?>
		</li>
		<li class="">
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg'))  . __(' Exam Time Table'),['controller'=>'Exam','action' => 'examtimetable'],['escape' => false]);?>  
		</li> 
	</ul>
</div>
<?php
if(isset($row))
{
	if(!empty($row->toArray()))
	{
		$stud_date = $this->Setting->getfieldname('date_format');
	?>
<div class="panel-body">	
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr><th><input type="checkbox" id="select_all" name="select_all" /></th>
						<th> <?php echo __('Exam Title'); ?> </th>
						<th> <?php echo __('Class'); ?> </th>
						<th> <?php echo __('Section'); ?> </th>
						<th> <?php echo __('Term'); ?> </th>
						<th> <?php echo __('Exam Start Date'); ?> </th>
						<th> <?php echo __('Exam End Date'); ?> </th>
						<th> <?php echo __('Exam Comment'); ?> </th>
						<th> <?php echo __('Action'); ?> </th>
					</tr>
				</thead>
				<tfoot>
					<tr><th></th>
						<th> <?php echo __('Exam Title'); ?> </th>
						<th> <?php echo __('Class'); ?> </th>
						<th> <?php echo __('Section'); ?> </th>
						<th> <?php echo __('Term'); ?> </th>
						<th> <?php echo __('Exam Start Date'); ?> </th>
						<th> <?php echo __('Exam End Date'); ?> </th>
						<th> <?php echo __('Exam Comment'); ?> </th>
						<th> <?php echo __('Action'); ?> </th>
					</tr>
				</tfoot>
				<tbody>

					<?php foreach($row as $fetch_data): ?>
					<tr>
					
						<td> 
						<p style='display:none;'><?php echo $fetch_data['exam_id'];?></p>
						<input type="checkbox" class="checkbox ch_pend" name="id[]" dataid="<?php echo $fetch_data['exam_id'];  ?>"> </td>
						<td><?php echo $fetch_data['exam_name']; ?></td>
						<td><?php echo $this->Setting->get_class_id($fetch_data['class_id']); ?></td>
						<td><?php echo $this->Setting->section_name($fetch_data['section_id']); ?></td>
						<td><?php echo $this->Setting->term_name($fetch_data['term_id']); ?></td>	
						<td><?php echo date($stud_date,strtotime($fetch_data['exam_date'])); ?></td>
						<td><?php echo date($stud_date,strtotime($fetch_data['exam_end_date'])); ?></td>
						<td><?php echo $fetch_data['exam_comment']; ?></td>
						<td>
						<?php
						echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-eye fa-lg')) . __(''),['controller' => 'Exam', 'action' => 'viewexamtimetable', $this->Setting->my_simple_crypt($fetch_data['exam_id'],'e')],['escape' => false],['class'=>'btn btnview btn-primary']).	
						"&nbsp;".
						$this->Html->link(__('Edit'),array('controller'=>'Exam','action' => 'updateexam', $this->Setting->my_simple_crypt($fetch_data['exam_id'],'e')),array('class'=>'btn btnview btn-info')).
						"&nbsp;".
						$this->Form->button( __('Delete'),['action'=>'#','url'=>$this->request->base.'/'.$this->name.'/delete/'.$fetch_data['exam_id'],'class'=>'btn btn-danger sa-warning']);
						
						if($fetch_data['syllabus'] == NULL || $fetch_data['syllabus'] == '')
						{
							echo '';
						}else{
						$file = WWW_ROOT.'syllabus'.'/'.$fetch_data['syllabus'];
						$file1 =$this->request->webroot.'syllabus/'.$fetch_data['syllabus'];
						echo "&nbsp";
						echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-download fa-lg')) . __('Download Syllabus'),['action' => 'readfile',$fetch_data['syllabus']],['escape' => false,'class'=>'btn btn-default']);

						?>
						<a href="<?php echo $file1;?>" target="_blank" class="btn btn-default"><i class="fa fa-eye"></i><?php echo " ".__('View Syllabus'); ?></a> 						
						<?php
						}
						?></td>
					
					</tr>
				<?php endforeach; ?>
				</tbody>
				</table>
				<tr>
					<td><button type="button" class="btn btn-danger" id="exa"> <?php echo __('Delete Selected'); ?> </button></td>
				</tr>
		</div>
	</div>
</div>
<?php
	}
	else{
		?>
		<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Exam Data Available');?></h4></div>
<?php		
	}
}
?>
<style>
.fa.fa-eye.fa-lg{
	color: #FFFFFF;
	background-color: #337ab7;
    width: 38px;
    height: 32px;
    vertical-align: top;
    text-align: center;
    line-height: 30px;
    padding: 0;
}
</style>