<?php 
use Cake\Routing\Router;
$stud_date = $this->Setting->getfieldname('date_format');
?>
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
	
	$('#abc').on('click',function(){
		var hall_id = $('#hall_id').val();
		if(hall_id == ''){
			alert("Select Exam Hall");
			return false;
		}
		if($(".ch_pend").is(":checked")) 
		{			
			var id_array = $('.ch_pend:checked').map(function() {
				return this.attributes.dataid.textContent;
			}).get()
			get_id = JSON.stringify(id_array);
			var exam_id = $(".exam_id").val();
			var sub_id = $(".sub_id").val();
			data={h_id:get_id,exam_id:exam_id,hall_id:hall_id,sub_id:sub_id};	

			jQuery.ajax({
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Hall','action'=>'assignstudexamhall'));?>",
				data:data,
				async:false,
				success: function(response){
					if(response != 'false')
					{
						jQuery("#studentexamhall1").append(response);
						
						$.each( id_array, function( key, value ) {
							jQuery('#studentexamhall tr#'+value).hide();
						});
					}
				},				
				complete:function(e){
					console.log(e.responseText);
				}
			});	
		}
	});
	
	$("body").on('click','.btn_del',function(){
		
		var userid=$(this).attr('dataid');
		var exam_id = $(".exam_id").val();
		
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

				$.ajax({
					type:'POST',
					url:"<?php echo Router::url(array('controller'=>'Hall','action'=>'removeexamhall'));?>",
					data:{userid:userid,exam_id:exam_id},
					success:function(data){
						if(data != 'false')
						{
							jQuery("#studentexamhall").append(data);
							jQuery('#studentexamhall1 tr#'+userid).hide();
						}
					}
				});
			}
			else {	 
				swal("Cancelled", "Not removed!", "error"); 
			}
		});
	});
});
</script>

<input type="hidden" value="<?php echo $exam_id;?>" name="exam_id" class="exam_id" />
<input type="hidden" value="<?php echo $sub_id;?>" name="sub_id" class="sub_id" />

<div class="form-group">
	<div class="col-md-6">
		<div class="col-md-12">
			<div class="row">
				<table class="table info" style="border: 1px solid #dddddd;text-align: center;margin-bottom: 0px;">
					<thead>
						<tr>
							<th style="text-align: center;border-top: medium none;border-right: 1px solid #dddddd;"><?php echo __('Class');?></th>
							<th style="text-align: center;border-right: 1px solid #dddddd;"><?php echo __('Section');?></th>
							<th style="text-align: center;border-right: 1px solid #dddddd;"><?php echo __('Exam Date');?></th>
							<th style="text-align: center;border-right: 1px solid #dddddd;"><?php echo __('Start Time');?></th>
							<th style="text-align: center;border-right: 1px solid #dddddd;"><?php echo __('End Time');?></th>
						</tr>
					</thead>
					<tfoot></tfoot>
					<tbody>
						<tr>						
							<td style="border-right: 1px solid #dddddd;"><?php echo $this->Setting->get_class_id($class_id);?></td>																	
							<td style="border-right: 1px solid #dddddd;"><?php echo $this->Setting->get_class_section($section_id);?></td>
							<td style="border-right: 1px solid #dddddd;"><?php 
								$result = array();
								$result = $this->Setting->fetch_exam_time_table_data($exam_id,$sub_id);
								if(!empty($result))
									echo date($stud_date, strtotime($result['exam_date']));?>
							</td>
							<td style="border-right: 1px solid #dddddd;"><?php 
								$result = array();
								$result = $this->Setting->fetch_exam_time_table_data($exam_id,$sub_id);
								if(!empty($result))
									echo $result['start_time'];?>
							</td>
							<td><?php 
								$result = array();
								$result = $this->Setting->fetch_exam_time_table_data($exam_id,$sub_id);
								if(!empty($result))
									echo $result['end_time'];?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="form-group">
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<?php
			echo "<div class='col-md-6 col-sm-6 col-xs-12'>";
			echo "<h4>".__('Not Assign Exam Hall Student List')."</h4>";
			if(isset($student_data))
			{
			?>
			<table id="studentexamhall" class="table" cellspacing="0" width="100%">	
			<thead>
				<tr>
					<th><input type="checkbox" id="select_all" name="select_all" /></th> 
					<th><?php echo __('Student Name');?></th>
					<th><?php echo __('Student ID');?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>					
					<th></th>
					<th><?php echo __('Student Name');?></th>
					<th><?php echo __('Student ID');?></th>
			</tfoot>
			<tbody>					
				<?php 
				foreach($student_data as $it2)
				{
				?>
				<tr id="<?php echo $it2['user_id'];?>">
					<td> 
						<p style='display:none;'><?php echo $it2['user_id'];?></p>
						<input type="checkbox" class="checkbox ch_pend" name="id[]" dataid="<?php echo $it2['user_id'];  ?>"> 
					</td>
					<td><?php echo $it2['first_name']." ".$it2['last_name'];?></td>
					<td><?php echo $it2['studentID_prefix'].$it2['studentID'];?></td>
				</tr>
				<?php
				}
				?>
			</tbody>
			</table>
			<tr>
				<td>											
					<button type="button" class="btn btn-success" id="abc"><?php echo __('Assign Exam Hall');?> </button>
				</td>
			</tr>
		<?php } 
			else{
				?>
				<div id='main-wrapper'><h4 class='text-danger'><?php echo __('Not Available Any Student');?></h4></div>
			<?php		
			}
			echo "</div>";
			echo "<div class='col-md-6 col-sm-6 col-xs-12'>";
			echo "<h4>".__('Assign Exam Hall Student List')."</h4>";
			if(isset($student_data1))
			{
			?>
			<table id="studentexamhall1" class="table" cellspacing="0" width="100%">	
			<thead>
				<tr>
					<th></th> 
					<th><?php echo __('Student Name');?></th>
					<th><?php echo __('Student ID');?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>					
					<th></th>
					<th><?php echo __('Student Name');?></th>
					<th><?php echo __('Student ID');?></th>
			</tfoot>
			<tbody>					
				<?php 
				foreach($student_data1 as $it2)
				{
				?>
				<tr id="<?php echo $it2['user_id'];?>">
					<td>
						<button type="button" class="btn btn-danger btn-xs btn_del" dataid="<?php echo $it2['user_id'];  ?>">X</button>
					</td>
					<td><?php echo $it2['first_name']." ".$it2['last_name'];?></td>
					<td><?php echo $it2['studentID_prefix'].$it2['studentID'];?></td>
				</tr>
				<?php
				}
				?>
			</tbody>
			</table>
		<?php } 
			else{
				?>
				<div id='main-wrapper'><h4 class='text-danger'><?php echo __('Not Assign Any Student Exam Hall');?></h4></div>
			<?php		
			}
			echo "</div>";
			?>
		</div>
	</div>
</div>

<style>
.table.info td, 
.table.info>tbody>tr>td,
.table.info>thead>tr>th,
.table.info > tfoot > tr > th{
	padding: 8px;
}
.table td, 
.table>tbody>tr>td,
.table>thead>tr>th,
.table > tfoot > tr > th{
	padding: 12px;
}
.table>thead>tr>th{
	border-bottom: medium none;
}
td .btn{
	margin-bottom: 0px;
    font-size: 9px;
    padding: 0px 6px;
	font-weight: bold;
}
input[type=checkbox]{
	margin: 0px;
}
.exam_hall label{
	padding: 7px;
}
</style>