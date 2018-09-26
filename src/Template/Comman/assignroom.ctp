<script>
	$(document).ready(function(){		 
		$('body').on('click', '#save', function() {
			 var str = $('#category_name').val();
			 $.ajax({
				type: 'POST',
				url: '<?php echo $this->Url->build(["controller" => "Hostel","action" => "adddata"]);?>',
				data : {category_name:str},
				success: function (data)
				{
						  // alert(data);return false;
					if(data != 'false')
					{
						$('#tab1').append('<tr class="del-'+data+'"><td class="text-center">'+str+'</td><td class="text-center"><a href="#" class="del" id="'+data+'" class="btn btn-success"><button class="btn btn-danger btn-xs">X</button></a></td><tr>');
						$('#room_category').append('<option value='+data+'>'+str+'</option>');
					}
					$('#category_name').val("");
				},
				error: function(e) {
				alert("An error occurred: " + e.responseText);
				console.log(e);
				}	
			});
		});
		$("body").on('click','.del',function(){
		   var id=$(this).attr('id');
		   $.ajax({
			   type:'POST',
			   url:'<?php echo $this->Url->build(['controller'=>'Hostel','action'=>'category_delete']);?>',
			   data:{category_id:id},
			   success:function(data){
					$('body .del-'+id).fadeOut(300);
					$('option.'+id).hide();
				}
		   }) ;
		});
	});
</script>
<?php
$bed_unique_id = isset($row['bed_unique_id'])?$row['bed_unique_id']:'';
$room_unique_id = isset($row['room_unique_id'])?$row['room_unique_id']:'';
$bed_desc = isset($row['bed_desc'])?$row['bed_desc']:'';
?>
<div class="row">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">
		<li class="">
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Hostel List'),array('controller'=>'Comman','action' => 'hostellist'),array('escape' => false));?>
		</li>
		
		<li class="">
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Room List'),array('controller'=>'Comman','action' => 'roomlist'),array('escape' => false));?>
		</li>
		<li class="active">
			<?php  
			if(isset($edit))
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Edit Room'),array('controller'=>'Comman','action' => 'addroom',$this->Setting->my_simple_crypt($row['room_id'],'e')),array('escape' => false));
			else
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Assign Room'),array('controller'=>'Comman','action' => 'assignroom',$this->Setting->my_simple_crypt($roomid,'e')),array('escape' => false));
			?>
		</li>
		<li class="">
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Beds List'),array('controller'=>'Comman','action' => 'bedslist'),array('escape' => false));?>
		</li>
		
	</ul>
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addhostel']]);
				$i=0;	
			if(isset($cls))
			{
				foreach($cls as $cls)
				{		
					$stud_date = $this->Setting->getfieldname('date_format');
				?>
					<script>
						$(document).ready(function() {
							$('.datepicker').datepicker({
								defaultDate: null,
								changeMonth: true,
								changeYear: true,
								yearRange:'-75:+10',
								dateFormat: 'yy-mm-dd'
							 });
		 
							$('#assigndate_<?php echo $i ; ?>').hide();
							$("#Assign_bed").prop("disabled", true);
							$('.students_list_<?php echo $i ;?>').change(function () {
								 var optionSelected = $(this).find("option:selected");
								 var valueSelected  = optionSelected.val();
								  
								 if(valueSelected=='0'){
								  $('#assigndate_<?php echo $i ; ?>').hide();
								  $("#Assign_bed").prop("disabled", true);
								 }else{
								  $('#assigndate_<?php echo $i ; ?>').show();
								  $("#Assign_bed").prop("disabled", false);
								 }
					
							 });
						});
					</script>
					<input type="hidden" name="room_unique_id[]" value="<?php echo $cls['room_unique_id'];?>">
					<div class="form-group">
						<label class="col-md-2 col-sm-2 col-xs-12 control-label" for="bed_unique_id"><?php echo __('Bed Unique ID');?><span class="require-field"></span></label>
						<div class="col-md-2 col-sm-2 col-xs-12">
							<input id="bed_unique_id_<?php echo $i;?>" class="form-control validate[required]" type="text" value="<?php echo $cls['bed_unique_id'];?>" name="bed_unique_id[]" readonly>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12">
							<select name="student_id[]" id="students_list_<?php echo $i ;?>" class="form-control students_list_<?php echo $i ;?>">
								<?php
								$student = $this->Setting->hostel_room_student_bed_unique_id($cls['bed_unique_id']);
								if($student['student_id'])
								{
								?>
									<option value="<?php echo $student['student_id']; ?>" ><?php echo $this->Setting->get_user_id($student['student_id']); ?></option>
									<?php 
								}
								else
								{?>
									<option value="0"><?php echo __('Select Student');?></option>
									<?php foreach($stud as $stud1)
									{
									?>
										<option value="<?php echo $stud1->user_id; ?>" ><?php echo $this->Setting->get_user_id($stud1->user_id); ?></option>
										<?php 
										
									}
								}
								?>
							</select>
						</div>
						<?php
						if($student['student_id'])
						{
						?>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<input id="assign_date_<?php echo $i ;?>"  value="<?php  echo date('Y-m-d', strtotime($student['assign_date'])); ?>" class="form-control text-input" type="text" name="assign_date[]" readonly>
							</div>
						<?php
						}
						else
						{?>
						<div class="col-md-2 col-sm-2 col-xs-12" id="assigndate_<?php echo $i ;?>" name='assigndate' class="assigndate_<?php echo $i;?>" >
							<input id="assign_date_<?php echo $i;?>"  value="<?php  echo date('Y-m-d'); ?>" class="datepicker form-control text-input" type="text" name="assign_date[]">
						</div>
						<?php
						}
						
						if($student['student_id'])
						{
						?>
						<div class="col-md-2 col-sm-2 col-xs-12">
							<label class="col-md-2 col-sm-2 col-xs-12 control-label" for="available" style="background-color:red;width:80%;margin: 0;line-height: 34px;text-align:center;color:white"><?php echo __('Occupied');?></label>
						</div>
						
						<?php
						}
						else
						{?>
						<div class="col-md-2 col-sm-2 col-xs-12">
							<label class="col-md-2 col-sm-2 col-xs-12 control-label" for="available" style="background-color:green;width:80%;line-height: 34px;margin: 0;text-align:center;color:white"><?php echo __('Available');?></label>
						</div>
						<?php
						}
						?>
					</div>
				<?php
					$i++;
				}
				?>
				
			<?php 
			}
			else{
				?>
				<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Bed Available');?></h4></div>
		<?php		
			}
			$this->Form->end(); 
			?>
        
		</div>
</div>		