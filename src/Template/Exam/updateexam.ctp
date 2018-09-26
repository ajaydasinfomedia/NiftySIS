<?php 
$dates = $this->Setting->Exam_Dates();
?>
<input type='hidden' value='<?php echo json_encode($dates);?>' id='dates'>
<script>
$(document).ready(function(){
	var disabledDates = $("#dates").val();
	
	$("#exam_date").datepicker({
		numberOfMonths: 1,
		minDate: new Date(),
		dateFormat: 'yy-mm-dd',  
		beforeShowDay: function(date){
			var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
			return [ disabledDates.indexOf(string) == -1 ]
		},  

		onSelect: function() {
			var date = $('#exam_date').datepicker('getDate');  
			date.setDate(date.getDate());
			$("#exam_end_date").datepicker("option","minDate", date);
		}
   });
   $("#exam_end_date").datepicker({     
		numberOfMonths: 1,
		minDate: new Date(),
		dateFormat: 'yy-mm-dd', 
		beforeShowDay: function(date){
			var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
			return [ disabledDates.indexOf(string) == -1 ]
		},	
		onSelect: function() {
		}
	});
	
	$("#faile").change(function(){
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
			console.log(data);		
		},
		error: function(e) {
			   alert("An error occurred: " + e.responseText);
			   console.log(e.responseText);	
		}
		});
	});
	
	$('body').on('click', '#save', function() {
		 var str = $('#term_name').val();
		 $.ajax({
			type: 'POST',
			url: '<?php echo $this->Url->build(["controller" => "Exam","action" => "adddata"]);?>',
			data : {term_name:str},
			success: function (data)
			{					
				if(data != 'false')
				{
					$('#tab1').append('<tr class="del-'+data+'"><td class="text-center">'+str+'</td><td class="text-center"><a href="#" class="del" id="'+data+'" class="btn btn-success"><button class="btn btn-danger btn-xs">X</button></a></td><tr>');
					$('#term_id').append('<option value='+data+'>'+str+'</option>');
					$('#term_name').val("");
				}
				$('#term_name').val("");
			},
			error: function(e) {
			alert("An error occurred: " + e.responseText);
			console.log(e);
			}	
		});
	});
	$("body").on('click','.del',function(){
		
	   var id=$(this).attr('id');
	   
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
				swal("Deleted!", "Term has been deleted.", "success");

				$.ajax({
				   type:'POST',
				   url:'<?php echo $this->Url->build(['controller'=>'Exam','action'=>'term_delete']);?>',
				   data:{term_id:id},
				   success:function(data){
						$("#term_id option[value="+id+"]").remove();
						$('body .del-'+id).fadeOut(300);
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
<?php
$class_id = isset($row['class_id'])?$row['class_id']:0;
$section_id = isset($row['section_id'])?$row['section_id']:0;
$term_id = isset($row['term_id'])?$row['term_id']:0;
?>
<div class="row">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">
		<li class="active">
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-pencil-square-o fa-lg')) . __('Edit List'),['controller'=>'Exam','action' => 'updateexam',$this->Setting->my_simple_crypt($row['exam_id'],'e')],['escape' => false]);?>
		</li>		  
		<li>				
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Exam'),['controller'=>'Exam','action' => 'addexam'],['escape' => false]);?>
		</li>
		<li class="">
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg'))  . __(' Exam Time Table'),['controller'=>'Exam','action' => 'examtimetable'],['escape' => false]);?>  
		</li> 
	</ul>
</div>
<div class="modal fade" id="myModal" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header" >
				<a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
				<div id="result1"></div>
				<h4 align="center"><b><u> <?php echo __('Exam Terms'); ?> </u></b></h4>
			</div>
			<div class="modal-body" >
				<table class="table" id="tab1" align="center">
					<tr>
						<td class="text-center"><?php echo __('Exam Terms Name');?></td>
						<td class="text-center"><?php echo __('Action');?></td>
					</tr>
					<?php
					foreach($term_data as $fetch)
					{
					?>
					<tr class="del-<?php echo $fetch['term_id'];?>">
						<td class="text-center"><?php echo $fetch['term_name']; ?></td>
						<td class="text-center">
							<a href="#" class="del" id="<?php echo $fetch['term_id']; ?>" class="btn btn-success">
								<button class="btn btn-danger btn-xs"> <?php echo __('X'); ?> </button>
							</a>
						</td>
						<input type="hidden" value="" name="delname" class="delid">
					</tr>
					<?php
					}
					?>
				</table>
			</div>
			<div class="modal-footer">	   
				<center>
				<div class="row">
					<div class="col-md-3 col-sm-6 col-xs-12">
						<label class="col-sm-12 control-label validate[required]" for="birth_date" id="post_name" value="catagory">
						<?php echo __('Exam Term Name');?> <span class="require-field">*</span></label>
					</div>
					<div class="col-md-5 col-sm-5 col-xs-12">
						<?php echo $this->Form->input('',array('class'=>'form-control validate[required,maxSize[50]]','id'=>'term_name'));?>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-12">
						<button type="submit" id="save" class="btn btn-success"> <?php echo __('Save Term'); ?> </button>
					</div>
					<div class="col-md-1 col-sm-1 col-xs-12">
						<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close');?></button>
					</div>
				</div>
				</center>
			<div id="message_board"></div>			
			</div>
		</div>
	</div>
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'updateexam']]);?>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Exam Name '));?><span class="require-field">*</span></div> 
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'exam_name','value'=>$row['exam_name'],'class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=>'Enter Exam Name'));?>
							</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Class Name '));?> <span class="require-field">*</span></div>
					<div class="col-md-10 col-sm-10 col-xs-12">
						<select class="form-control validate[required,maxSize[50]]" name="class_name" id="faile">
							<option value=" "><?php echo __('Select Class Name'); ?> </option>
							<?php foreach($class_data as $class_info):
							$selected_vl = ($class_info['class_id'] == $class_id)?"selected":"";
							?>
							<option value="<?php echo $class_info['class_id'];?>" <?php echo $selected_vl;?>><?php echo $class_info['class_name']; ?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Section Name '));?> <span class="require-field">*</span></div>
					<div class="col-md-10 col-sm-10 col-xs-12">
						<select class="form-control validate[required,maxSize[50]] ajaxdata" name="section" id="dep">
							<?php if(isset($section_id)){?>
							<option value="<?php echo $section_id; ?>"><?php echo $this->Setting->get_class_section($section_id); ?></option>
								<?php } 
							else
								echo "<option>" ?> <?php echo __('Select Section'); ?> <?php "</option>";
							?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Exam Terms '));?><span class="require-field">*</span></div>
					<div class="col-md-8 col-sm-8 col-xs-12 export_model">
						<select class="form-control validate[required,maxSize[50]]" id="term_id" name="term_id">
							<option value=""> <?php echo __('Select Exam Terms'); ?> </option>
							<?php
							foreach($term_data as $fetch_data)
							{
								$selected = ($fetch_data['term_id'] == $term_id)?'selected':'';
							?>
							<option value="<?php echo $fetch_data['term_id'];?>" class="room_cat_<?php echo $fetch_data['term_name'];?>" <?php echo $selected;?>>
							<?php echo $fetch_data['term_name'];?></option>
							<?php
							}
							?>
						</select>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-12 label_float">
						<button type="button" name="" data-toggle="modal" data-target="#myModal" class="btn btn-success"> <?php echo __('Add or Remove'); ?> </button>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Passing Marks '));?> <span class="require-field">*</span></div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<?php echo $this->Form->input('passmarks',array('value'=>$row['pass_mark'],'type'=>'number','min'=>'10','name'=>'pass_mark','class'=>'form-control validate[required,maxSize[5]] passmarks','PlaceHolder'=> __('Passing Marks of Exam'),'min'=>0,'max'=>1000));?>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Total Marks '));?> <span class="require-field">*</span></div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<?php echo $this->Form->input('totalmarks',array('value'=>$row['total_mark'],'type'=>'number','min'=>'20','name'=>'total_mark','class'=>'form-control validate[required,maxSize[5]] totalmarks','PlaceHolder'=> __('Total Marks of Exam'),'min'=>0,'max'=>1000));?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Exam Start Date '));?><span class="require-field">*</span></div>
					<div class="col-md-4 col-sm-4 col-xs-12 datepickericon">
						<?php echo $this->Form->input('',array('name'=>'exam_date','value'=> date("Y-m-d", strtotime($row['exam_date'])),'id'=>'exam_date','class'=>'form-control validate[required]','PlaceHolder'=>'Enter Exam date'));?>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Exam End Date '));?> <span class="require-field">*</span> </div>
					<div class="col-md-4 col-sm-4 col-xs-12 datepickericon">
						<?php echo $this->Form->input('',array('name'=>'exam_end_date','value'=> date("Y-m-d", strtotime($row['exam_end_date'])),'id'=>'exam_end_date','class'=>'form-control validate[required]','PlaceHolder'=> __('Exam End Date ')));?>
					</div>
				</div>	
					
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Exam Comment '));?></div>
					<div class="col-md-10 col-sm-10 col-xs-12">
						<?php echo $this->Form->input('',array('name'=>'exam_comment','value'=>$row['exam_comment'],'type'=>'textarea','class'=>'form-control validate[maxSize[150]]','PlaceHolder'=>'Enter Exam Comment '));?>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Exam Syllabus '));?></div>
					<div class="col-md-10 col-sm-10 col-xs-12">
						<?php 
						if(isset($edit))
						{
							echo $this->Form->input('',array('type'=>'hidden','value'=>$row['syllabus'],'name'=>'file2'));
							echo $this->Form->input('',array('type'=>'file','name'=>'syllabus'));
						}
						else
							echo $this->Form->input('',array('type'=>'file','name'=>'syllabus'));
						?>
					</div>
				</div>
			
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('');?></div>
					<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
					<?php echo $this->Form->input(__('Edit Exam'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
					</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>			
<script>
$(".totalmarks").focusout(function(){
	var totalmarks =  $(this).val();

	var passmarks = document.getElementById("passmarks").value;
	if (parseInt(totalmarks) >= parseInt(passmarks))
	{
		
	} else {
		alert("Select Greater than Passing Marks");
		$(this).val("");
		return false;
	}
});
</script>
<style>
.input.number > label{
	display: none;
}
</style>