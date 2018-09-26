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
			
			$('#loadingmessage').show();
			
			jQuery.ajax({
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Hall','action'=>'assgnexamhall'));?>",
				data:data,
				async:false,
				success: function(response){
					if(response != 'false')
					{
						jQuery("#studentexamhall1").append(response);
						
						$.each( id_array, function( key, value ) {
							jQuery('#studentexamhall tr#'+value).remove();
						});
						
					}
				},
				beforeSend:function(){
					$('#loadingmessage').show();
				},
				complete:function(){
					$('#loadingmessage').hide();
				}
			});	
		}
	});
	
	$("body").on('click','.btn_del',function(){
		
		var userid=$(this).attr('dataid');
		var exam_id = $(".exam_id").val();

		$('#loadingmessage').show();
		$.ajax({
			type:'POST',
			url:"<?php echo Router::url(array('controller'=>'Hall','action'=>'removeexamhall'));?>",
			data:{userid:userid,exam_id:exam_id},
			success:function(data){
				if(data != 'false')
				{
					jQuery("#studentexamhall").append(data);
					jQuery('#studentexamhall1 tr#'+userid).remove();
					$('#loadingmessage').hide();
				}
			}
		});
	});
	
	$('#studentexamhall').DataTable({
		responsive: true,
		bPaginate: false,
		bFilter: false, 
		bInfo: false,
		bSortable: false,
		aaSorting: false,			
	});
	$('#studentexamhall1').DataTable({
		responsive: true,
		bPaginate: false,
		bFilter: false, 
		bInfo: false,
		bSortable: false,
		aaSorting: false,	
	});
});
</script>

<input type="hidden" value="<?php echo $exam_id;?>" name="exam_id" class="exam_id" />
<div id='loadingmessage' style='display:none'>
  <center><img src='../img/loading.gif' width='120px'></center>
</div>
<div class="form-group">
	<div class="col-md-6">
		<div class="col-md-6">
			<div class="row">
				<table class="table info" style="border: 1px solid #dddddd;border-collapse: separate;text-align: center;margin-bottom: 0px;">
					<thead></thead>
					<tfoot></tfoot>
					<tbody>
						<tr>
							<td style="border-top: medium none;border-right: 1px solid #dddddd;"><b><?php echo __('Class');?></b></td>
							<td style="border-top: medium none;"><?php echo $this->Setting->get_class_id($class_id);?></td>							
						</tr>
						<tr>
							<td style="border-right: 1px solid #dddddd;"><b><?php echo __('Section');?></b></td>
							<td><?php echo $this->Setting->get_class_section($section_id);?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-md-6">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12 exam_hall"><?php echo $this->Form->label(__('Exam Hall '));?> <span class="require-field">*</span></div>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<select class="form-control validate[required]" id="hall_id" name="hall_id">
						<option value=""> <?php echo __('Select Assign Exam Hall '); ?> </option>
						<?php
						foreach($hall_data as $fetch_data)
						{
							$selected = ($fetch_data['hall_id'] == $hall_id)?'selected':'';
						?>
							<option value="<?php echo $fetch_data['hall_id'];?>" <?php echo $selected;?>><?php echo $fetch_data['hall_name'];?></option>
						<?php
						}
						?>
					</select>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="form-group">
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<?php
			
			if(isset($student_data) || isset($student_data1))
			{
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
				<?php
				}
				else{
					?>
					<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Student Available');?></h4></div>
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
				<?php
				}
				else{
					?>
					<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Student Available');?></h4></div>
				<?php
				}
			echo "</div>";
			}
			else
			{
				?>
				<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Student Available');?></h4></div>
			<?php		
			}
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
.table>thead>tr>th:first-child{
	padding-left: 18px;
}
.table>thead>tr>th
{	
	border-bottom: 1px solid #000000;
}
.table>tfoot>tr>th
{	
	border-top: 1px solid #000000;
}
table.dataTable thead .sorting{
	background: none;
}
.dataTables_empty{
	display: none;
}
</style>