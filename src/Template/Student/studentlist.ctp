<?php
use Cake\Routing\Router;
?>

<script>
	$(function(){

	$('#abc').click(function() {
		
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
					data={i_id:get_id};	
					
					jQuery.ajax({
					type:"POST",
					url:"<?php echo Router::url(array('controller'=>'Student','action'=>'multidelete'));?>",
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
			$('#studentlist').DataTable({
				responsive: true,
				"order":[[0,"desc"]],
			});
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

    $('body').on('click', '.save1', function() {
	
		$('#myModal .modal-body').html("");
		
      var str = $(this).attr("print");
	 
       $.ajax({
       type: 'POST',
       url: '<?php echo $this->Url->build([
			"controller" => "Student",
			"action" => "result"]);?>',
	
		data : {id:str},
		success: function (data)
		{            
 
			  $('#myModal .modal-body').html(data);
				
		},
		error: function(e) {
			   alert("An error occurred: " + e.responseText);
			   console.log(e);	
		}

       });

       });
	      

   });

</script>
<script>

$( document ).ready(function(){	

    $('body').on('click', '.save', function() {
		
		$('.modal-body1').html("");	   
		var str = $(this).attr("print");
	 
		$.ajax({
		type: 'POST',
		url: '<?php echo $this->Url->build([
			"controller" => "Student",
			"action" => "studentparent"]);?>',
	
		data : {id:str},
		success: function (data)
		{            
			$('.modal-body1').html(data);
		},
		error: function(e) {
			   alert("An error occurred: " + e.responseText);
			   console.log(e);	
		}
		});
	});
	
	jQuery('body').on('click', '.viewdetail', function() {

		var get_id=jQuery(this).attr('viewID');

		$.ajax({
			type:'POST',
			url:'<?php echo $this->Url->build(["controller"=>"User","action"=>"userinfo"]); ?>',
			data:{id:get_id},

			success:function(getdata){
				$("#myModalview .modal-body").html(getdata);
			},
			beforeSend:function(){
				$("#myModalview .modal-body").html("<center><h2 class=text-muted><b>Loading...</b></h2></center>");
			},
			error:function(e){
				
				console.log(e);
			},
		});
	});
	
	$("#class_id").change(function(){
		var class_id = $(this).val();
		
		$('.ajaxdata').html();
			$.ajax({
			type: 'POST',
			url: '<?php echo $this->Url->build(["controller" => "Student","action" => "view2"]);?>',
			data : {id : class_id},
	     
			success: function (data)
			{            			
				$('.ajaxdata').html(data);
				console.log(data);  				
			},
			error: function(e) {
				   alert("An error occurred: " + e.responseText);
				   console.log(e.responseText);	
			}
		});
	});
});
</script>

<div class="row schooltitle">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Student List'),['controller' => 'Student', 'action' => 'studentlist'],['escape' => false]);?>
		</li>
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add New Student'),['controller' => 'Student', 'action' => 'addstudent'],['escape' => false]);?>
		</li>
	</ul>	
</div>
<div class="panel-body" style="padding-top: 30px;">	
	<form method="post">
		<div class="col-md-3 col-sm-3 col-xs-12">		
		<?php echo $this->Form->label('Select Class');?>
		<?php					
		if(isset($_POST['filter_class']))
		{
			echo @$this->form->select("select_stud",$class_data,["default"=>$cls_id,"empty"=>__("Select Class"),"class"=>"form-control validate[required]","id"=>"class_id"]);
		
		}
		else{
			echo @$this->form->select("select_stud",$class_data,["default"=>"","empty"=>__("Select Class"),"class"=>"form-control validate[required]","id"=>"class_id"]);
		}
		?>							
		</div>
		<div class="col-md-3 col-sm-3 col-xs-12">	
			<?php echo $this->Form->label(__('Select Section'));?>	
			<select class="form-control ajaxdata" name="section" id="dep">
			<?php if(isset($sec_id)){?>
				<option value="<?php echo $sec_id; ?>"><?php echo $this->Setting->get_class_section($sec_id); ?></option>
					<?php } 
				else
					echo "<option value=''>"?> <?php echo __('Select Section'); ?> <?php "</option>";
			?>
			</select>
		</div>
		<div class="col-md-3 col-sm-3 col-xs-12 button-list-possition">
			<?php echo $this->Form->label(__(''));?>
			<div class="submit">
				<input class="btn btn-info" type="submit" name="filter_class" value="Go">
			</div>
		</div>
	</form>
</div>
<?php
if(isset($it))
{
	if(!empty($it->toArray()))
	{
	?>
<div class="panel-body">	
	<div class="table-responsive" style="padding-top: 0px;">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="studentlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr>				
						<th><input type="checkbox" id="select_all" name="select_all" /></th> 
						<th><?php echo __('Photo'); ?></th>
						<th><?php echo __('Student Name');?></th>
						<th><?php echo __('Student ID');?></th>
						<th><?php echo __('Class Name');?></th>
						<th><?php echo __('Section Name');?></th>
						<th><?php echo __('Status');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>					
						<th></th>
						<th><?php echo __('Photo');?></th>
						<th><?php echo __('Student Name');?></th>
						<th><?php echo __('Student ID');?></th>
						<th><?php echo __('Class Name');?></th>
						<th><?php echo __('Section Name');?></th>
						<th><?php echo __('Status');?></th>
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
									$result_cnt = $this->Setting->user_mark_count($it2['user_id'],$it2['classname']);
									$parent_cnt = $this->Setting->user_parent_count($it2['user_id']);
									
									$name=$it2['first_name']." ".$it2['last_name'];
									?>
									<td> 
									<p style='display:none;'><?php echo $it2['user_id'];?></p>
									<input type="checkbox" class="checkbox ch_pend" name="id[]" dataid="<?php echo $it2['user_id'];  ?>"> </td>
									<?php
									echo "<td>" .$this->Html->image($it2['image'],array('height'=>'50px','width'=>'50px','class'=>'profileimg')) . "</td>";
									echo "<td>" .__($name). "</td>";
									echo "<td>" .__($it2['studentID_prefix'].$it2['studentID']) . "</td>";
									echo "<td>" .__($this->Setting->get_class_id($it2['classname'])) . "</td>";
									echo "<td>" .__($this->Setting->get_class_section($it2['classsection'])) . "</td>";
									echo "<td>" .__($it2['status']) . "</td>";
									echo "<td>".
									$this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-eye')). __('View Details'),['action'=>'#','data-toggle'=>'modal','data-target'=>'#myModalview','viewID'=>$it2['user_id'],'class'=>'btn btn-primary viewdetail'],['escape' => false]).
									"&nbsp;".
									$this->Html->link(__('Edit'),array('action' => 'updatestudent', $this->Setting->my_simple_crypt($it2['user_id'],'e')),array('class'=>' btn btnview btn-info')).
									"&nbsp;".
									$this->Form->button( __('Delete'),['action'=>'#','url'=>$this->request->base.'/'.$this->name.'/delete/'.$it2['user_id'],'class'=>'btn btn-danger sa-warning'])." "; 
									
									if($it2['status'] == "Not Approved"){ echo $this->Html->link(__('Approve'),array('action' => 'approve', $it2['user_id']),array('class'=>'btn btnview btn-success'))." ";}
									
									if($result_cnt >= 1){ echo $this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-bar-chart')). __('View Result'),['action'=>'result','data-toggle'=>'modal','data-target'=>'#myModal','print'=>$it2['user_id'],'class'=>'btn btn-default save1'],['escape' => false])." ";}
									
									if($parent_cnt >= 1){ echo $this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-user')). __('View Parent'),['action'=>'studentparent','data-toggle'=>'modal','data-target'=>'#myModal1','print'=>$it2['user_id'],'class'=>'btn btn-default save'],['escape' => false])." ";}
									
									if($it2['exam_hall_receipt'] == 1)
									{
										echo $this->Html->link(__('Exam Receipt'),array('action' => 'studentexamlist', $this->Setting->my_simple_crypt($it2['user_id'],'e')),array('class'=>' btn btnview btn-success'))." ";
										/* $exam_receipt_data = $this->Setting->student_exam_receipt($it2['user_id']);
										foreach($exam_receipt_data as $data)
										{
											$exam_name = $this->Setting->get_exam_data($data['exam_id'],'exam_name');
											echo $this->Html->link($exam_name." ". __('Exam Receipt'),array('action' => 'studentreceipt', $this->Setting->my_simple_crypt($data['receipt_id'],'e')),array('class'=>' btn btnview btn-success'))." ";
										} */
									}
									
									echo $this->Html->link($this->Html->tag('i'," ", array('class' => 'fa fa-eye')).__(' View Attendance'),array('controller'=>'Student','action' => 'studentattendance', $this->Setting->my_simple_crypt($it2['user_id'],'e')),array('class'=>'btn btnview btn-default','escape' => false)).
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
		<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Student Data Available');?></h4></div>
<?php		
	}
}
?>
<div class="modal fade " id="myModalview" role="dialog">
	<div class="modal-dialog modal-md"  >
		<div class="modal-content">
			<div class="modal-header" >
				<span type="button" class="" data-dismiss="modal"><?php echo __("Student Details");?></span>
				<a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
			</div>
			<div class="modal-body" style="float: left;width: 100%;background-color: #FFFFFF;"></div>
		</div>
	</div>
</div>
<?php $heading = $this->Setting->getfieldname('school_name');?>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      
	  <div class="modal-header"> 
			<a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
			<h4 id="myLargeModalLabel" class="modal-title"><?php echo $heading; ?></h4>
		</div>
	  
      <div class="modal-body">
		
      </div>
      
	  <div class="modal-footer">
        <button type="button" class="btn btn-default" data-toggle="collapse" data-dismiss="modal"><?php echo __('Close'); ?></button>
      </div>
    </div>

  </div>
</div>

<div id="myModal1" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      
	  <div class="modal-header"> 
			<a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
			<h4 id="myLargeModalLabel" class="modal-title"><?php echo __('Parent of Student'); ?></h4>
		</div>
	  
      <div class="modal-body1">
		
      </div>
      
	  <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close'); ?></button>
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
