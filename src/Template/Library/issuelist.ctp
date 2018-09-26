<?php
use Cake\Routing\Router;
?>
<!--- start checkbox js ---->
<script>
	$(function(){

	/* $('.ch_pend').click(function() { */
	$('#issue').click(function() {
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
					data={i_id:get_id	};	


					jQuery.ajax({
					type:"POST",
					url:"<?php echo Router::url(array('controller'=>'Library','action'=>'issuemultidelete'));?>",
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
					  <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Member List'),array('controller'=>'Library','action' => 'memberlist'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>

					   <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Book List'),array('controller'=>'Library','action' => 'booklist'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>


					     <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Book'),array('controller'=>'Library','action' => 'addbook'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>

					     <li class="active">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Issue List'),array('controller'=>'Library','action' => 'issuelist'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>

					     <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Issue Book'),array('controller'=>'Library','action' => 'issuebook'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>
				</ul>
</div>

<?php
if(isset($lib_info))
{
	if(!empty($lib_info->toArray()))
	{
	?>
<div class="panel-body">	
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th><input type="checkbox" id="select_all" name="select_all" /></th>
						<th><?php echo __('Student ID');?></th>
						<th> <?php echo __('Student Name'); ?> </th>
						<th> <?php echo __('Book Name'); ?> </th>
						<th> <?php echo __('Issue Date'); ?> </th>
                        <th> <?php echo __('Return Date'); ?> </th>
                        <th> <?php echo __('Period'); ?> </th>
						<th> <?php echo __('Action'); ?> </th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th></th>
						<th><?php echo __('Student ID');?></th>
						<th> <?php echo __('Student Name'); ?> </th>
						<th> <?php echo __('Book Name'); ?> </th>
						<th> <?php echo __('Issue Date'); ?> </th>
                        <th> <?php echo __('Return Date'); ?> </th>
                        <th> <?php echo __('Period'); ?> </th>
						<th> <?php echo __('Action'); ?> </th>
					</tr>
				</tfoot>
				<tbody>
                        
                    <?php
                        foreach($lib_info as $lib_book_row):
                            
                            foreach($fetch_category as $category_period):
                                
                    
                                if($lib_book_row['period'] == $category_period['category_id']){
             
                                        foreach($user_info as $get_user_info):
                                        if($lib_book_row['student_id'] == $get_user_info['user_id']){
                            
							$stud_date = $this->Setting->getfieldname('date_format');
                    ?>

					<tr>
						<td>
						<p style='display:none;'><?php echo $lib_book_row['id'];?></p>
						<input type="checkbox" class="checkbox ch_pend" name="id[]" dataid="<?php echo $lib_book_row['id'];  ?>"> </td>
						<td><?php echo $this->Setting->get_studentID($get_user_info['user_id']); ?></td>
						<td><?php echo $get_user_info['first_name'].' '.$get_user_info['last_name']; ?></td>
						<td><?php 
                                $get_book_id=$lib_book_row['book_id'];
                            
                                $trav=explode(",",$get_book_id);
                                            
                                            $get_book_arr=array();
                                                for($i=0;$i<count($trav);$i++){
                                        
                                                        foreach($book_info as $get_book):
                                            
                                                            if($get_book['id'] == $trav[$i]){
                                                                    $get_book_arr[]=$get_book['book_name'];
                                                            }
                                        
                                        endforeach;
           
                                    }        
                                            $arrtostr=implode(",",$get_book_arr);
                                            echo $arrtostr;
                                            
            
                            ?></td>
						<td><?php echo __(date($stud_date,strtotime($lib_book_row['issue_date']))); ?></td>
                        <td><?php echo __(date($stud_date,strtotime($lib_book_row['end_date']))); ?></td>
                        <td><?php echo $category_period['category_type']; ?></td>
                        
						<td>
						<?php 
						echo $this->Html->link(__('Edit'),array('controller'=>'Library','action' => 'issuebook',$this->Setting->my_simple_crypt($lib_book_row['id'],'e')),array('class'=>'btn btnview btn-info'))
						."&nbsp;".
						$this->Form->button( __('Delete'),['action'=>'#','url'=>$this->request->base.'/'.$this->name.'/deleteissuebook/'.$lib_book_row['id'],'class'=>'btn btn-danger sa-warning']);
						?></td>
					
					</tr>
                    <?php
                            }
                            endforeach;
                         
                              
                                }
                            endforeach;
                    endforeach
                    ?>
				
				</tbody>
				</table>
				<tr>
					<td><button type="button" class="btn btn-danger" id="issue"><?php echo __('Delete Selected'); ?></button></td>
				</tr>
		</div>
	</div>
</div>
<?php
	}
	else{
		?>
		<div id='main-wrapper'><h4 class='text-danger'><?php echo __('Not Available Any Issue');?></h4></div>
<?php		
	}
}
?>