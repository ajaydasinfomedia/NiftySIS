<?php
use Cake\Routing\Router;

$user_id=$this->request->session()->read('user_id');
$get_role=$this->Setting->get_user_role($user_id);			
?>
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
					url:"<?php echo Router::url(array('controller'=>'Comman','action'=>'exammultidelete'));?>",
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
	$('#examlist').DataTable({responsive: true});
});
</script>
<div class="row schooltitle">			
				<ul role="tablist" class="nav nav-tabs panel_tabs">
					  <li class="active">
					  
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Exam List'),array('controller'=>'Comman','action' => 'examlist'),array('escape' => false));
						?>
						
						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->
						  
					  </li>
					  <?php
						if($get_role == 'teacher'){?>
					  <li class="">
					  
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Exam'),['controller'=>'Comman','action' => 'addexam'],['escape' => false]);
						?>
						
						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->
						  
					  </li>
					 <li class="">
						<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg'))  . __(' Exam Time Table'),['controller'=>'Comman','action' => 'examtimetable'],['escape' => false]);?>  
					</li>
						<?php } ?>
				</ul>
</div>
<div class="panel-body">	
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th> <?php echo __('Exam Title'); ?> </th>
						<th> <?php echo __('Class'); ?> </th>
						<th> <?php echo __('Section'); ?> </th>
						<th> <?php echo __('Term'); ?> </th>
						<th> <?php echo __('Exam Start Date'); ?> </th>
						<th> <?php echo __('Exam End Date'); ?> </th>
						<th> <?php echo __('Exam Comment'); ?> </th>
						<?php
						if($get_role == 'student' || $get_role == 'teacher'){?>
						<th> <?php echo __('Action'); ?> </th>
						<?php } ?>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th> <?php echo __('Exam Title'); ?> </th>
						<th> <?php echo __('Class'); ?> </th>
						<th> <?php echo __('Section'); ?> </th>
						<th> <?php echo __('Term'); ?> </th>
						<th> <?php echo __('Exam Start Date'); ?> </th>
						<th> <?php echo __('Exam End Date'); ?> </th>
						<th> <?php echo __('Exam Comment'); ?> </th>
						<?php
						if($get_role == 'student' || $get_role == 'teacher'){?>
						<th> <?php echo __('Action'); ?> </th>
						<?php } ?>
					</tr>
				</tfoot>
				<tbody>
					<?php 
					$stud_date = $this->Setting->getfieldname('date_format');
					if(isset($row))
					{
					foreach($row as $fetch_data){ 
					?>
					<tr>
						<td><?php echo $fetch_data['exam_name']; ?></td>
						<td><?php echo $this->Setting->get_class_id($fetch_data['class_id']); ?></td>
						<td><?php echo $this->Setting->section_name($fetch_data['section_id']); ?></td>
						<td><?php echo $this->Setting->term_name($fetch_data['term_id']); ?></td>	
						<td><?php echo date($stud_date,strtotime($fetch_data['exam_date'])); ?></td>
						<td><?php echo date($stud_date,strtotime($fetch_data['exam_end_date'])); ?></td>
						<td><?php echo $fetch_data['exam_comment']; ?></td>
						<?php
						if($get_role == 'teacher'){?>
						<td>
						<?php 
						echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-eye fa-lg')) . __(''),['controller' => 'Comman', 'action' => 'viewexamtimetable', $this->Setting->my_simple_crypt($fetch_data['exam_id'],'e')],['escape' => false],['class'=>'btn btnview btn-primary']).	
						"&nbsp;".
						$this->Html->link(__('Edit'),array('controller'=>'Comman','action' => 'updateexam', $this->Setting->my_simple_crypt($fetch_data['exam_id'],'e')),array('class'=>'btn btnview btn-info')).
						"&nbsp;".
						$this->Form->button( __('Delete'),['action'=>'#','url'=>$this->request->base.'/'.$this->name.'/examdelete/'.$fetch_data['exam_id'],'class'=>'btn btn-danger sa-warning']);
						
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
						?>
						</td>
						<?php 
						}
						elseif($get_role == 'student')
						{?>
						<td>
						<?php
							echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-eye fa-lg')) . __(''),['controller' => 'Comman', 'action' => 'viewexamtimetable', $this->Setting->my_simple_crypt($fetch_data['exam_id'],'e')],['escape' => false],['class'=>'btn btnview btn-primary'])." ";
							
							$user_id=$this->request->session()->read('user_id');
							$receipt_id = $this->Setting->student_exam_receipt($user_id, $fetch_data['exam_id']);	
							if($receipt_id)
							{
								echo $this->Html->link(__('Print'),array('action' => 'studentreceipt', $this->Setting->my_simple_crypt($receipt_id,'e')),array('target'=>'_blank','class'=>' btn btnview btn-success'))." ";
								echo $this->Html->link(__('PDF'),array('action' => 'studentreceiptpdf', $this->Setting->my_simple_crypt($receipt_id,'e')),array('target'=>'_blank','id'=>'cmd','class'=>' btn btnview btn-success'))." ";
							}
						?>
						</td>	
						<?php 
						}
						?>	
					</tr>
					<?php }
					}
					?>
				</tbody>
				</table>
		</div>
	</div>
</div>
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