<?php
use Cake\Routing\Router;
?>
<!--- start checkbox js ---->
<script>
	$(function(){

	/* $('.ch_pend').click(function() { */
	$('#del_news').click(function() {
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
					url:"<?php echo Router::url(array('controller'=>'News','action'=>'newsmultidelete'));?>",
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
						<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('News List'),array('controller'=>'News','action' => 'newslist'),array('escape' => false));?>						  
					 </li>
					 <li class="">						
						<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add News'),array('controller'=>'News','action' => 'addnews'),array('escape' => false));?>
					
						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->					  
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
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th><input type="checkbox" id="select_all" name="select_all" /></th>
						<th> <?php echo __('News List'); ?> </th>
						<th> <?php echo __('Description'); ?> </th>
						<th> <?php echo __('Start Date'); ?> </th>
						<th> <?php echo __('End Date'); ?> </th>
						<th> <?php echo __('Document'); ?> </th>
						<th> <?php echo __('Action'); ?> </th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th></th>
						<th> <?php echo __('News List'); ?> </th>
						<th> <?php echo __('Description'); ?> </th>
						<th> <?php echo __('Start Date'); ?> </th>
						<th> <?php echo __('End Date'); ?> </th>
						<th> <?php echo __('Document'); ?> </th>
						<th> <?php echo __('Action'); ?> </th>
					</tr>
				</tfoot>
				<tbody>

					<?php foreach($it as $get_data): 
					
					$stud_date = $this->Setting->getfieldname('date_format');
					
					?>
					<tr>
						<td>
						<p style='display:none;'><?php echo $get_data['news_id'];?></p>
						<input type="checkbox" class="checkbox ch_pend" name="id[]" dataid="<?php echo $get_data['news_id'];  ?>"> </td>
						<td><?php echo $get_data['news_title']; ?></td>
						<td><?php echo $get_data['news_desc']; ?></td>
						<td><?php echo date($stud_date,strtotime($get_data['news_start_date'])); ?></td>
						<td><?php echo date($stud_date,strtotime($get_data['news_end_date'])); ?></td>
						<td>
						<?php
						if($get_data['news_document'] == NULL || $get_data['news_document'] == '')
						{
							echo '';
						}else{
						$file = WWW_ROOT.'document'.'/'.$get_data['news_document'];
						$file1 =$this->request->webroot.'document/'.$get_data['news_document'];
						echo "&nbsp";
						echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-download fa-lg')) . __('Download'),['controller' => 'news','action' => 'readfile',$get_data['news_document']],['escape' => false,'class'=>'btn btn-default']);
						?>
						<a href="<?php echo $file1;?>" target="_blank" class="btn btn-default"><?php echo __('View'); ?></a>
						<?php
						}
						?>
						</td>
						<td>
						<?php 
						echo $this->Html->link(__('Edit'),array('controller'=>'News','action' => 'addnews',$this->Setting->my_simple_crypt($get_data['news_id'],'e')),array('class'=>'btn btnview btn-info')) 
						."&nbsp;".
						$this->Form->button( __('Delete'),['action'=>'#','url'=>$this->request->base.'/'.$this->name.'/delete/'.$get_data['news_id'],'class'=>'btn btn-danger sa-warning']);
						?></td>
					
					</tr>
				<?php endforeach; ?>
				</tbody>
				</table>
				<tr>
					<td><button type="button" class="btn btn-danger" id="del_news"> <?php echo __('Delete Selected'); ?> </button></td>
				</tr>
		</div>
	</div>
</div>
<?php
	}
	else{
		?>
		<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No News Data available');?></h4></div>
<?php		
	}
}
?>