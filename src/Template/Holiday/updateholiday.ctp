<script>
$(document).ready(function(){
	$("#date_of_birth20").datepicker({
		numberOfMonths: 1,
		minDate: new Date(),
		dateFormat: 'yy-mm-dd',  

		onSelect: function() {
		var date = $('#date_of_birth20').datepicker('getDate');  
		date.setDate(date.getDate());

		$("#date_of_birth21").datepicker("option","minDate", date);
		}
   });
   $("#date_of_birth21").datepicker({     
		numberOfMonths: 1,
		minDate: new Date(),
		dateFormat: 'yy-mm-dd',  
		onSelect: function() {
		}
	}); 
});
</script>

<div class="row">			
				<ul role="tablist" class="nav nav-tabs panel_tabs">
					  <li class="active">
							
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-pencil-square-o fa-lg')) . __('Edit Holiday'),array('controller'=>'Holiday','action' => 'updateholiday',$this->Setting->my_simple_crypt($row['holiday_id'],'e')),array('escape' => false));
						?>
						
						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->
						  
					  </li>
					  <li>
							
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Holiday'),array('controller'=>'Holiday','action' => 'addholiday'),array('escape' => false));
						?>
						
						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->
						  
					  </li>
					  
				</ul>
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'updateholiday']]);?>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Holiday Title : '));?><span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'holiday_title','value'=>$row['holiday_title'],'class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=> __('Enter Holiday Title ')));?>
							</div>
				</div>
				
				
									
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Description : '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'description','value'=>$row['description'],'type'=>'textarea','class'=>'form-control validate[maxSize[500]]','PlaceHolder'=> __('Enter Description ')));?>
							</div>
				</div>
			
				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Start Date : '));?><span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12 datepickericon">
								<?php echo $this->Form->input('',array('name'=>'date','value'=> date("Y-m-d", strtotime($row['date'])),'id'=>'date_of_birth20','class'=>'form-control validate[required]','PlaceHolder'=> __('Enter Exam date ')));?>
							</div>
				</div>
				
					<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('End Date : '));?><span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12 datepickericon">
								<?php echo $this->Form->input('',array('name'=>'end_date','value'=> date("Y-m-d", strtotime($row['end_date'])),'id'=>'date_of_birth21','class'=>'form-control validate[required]','PlaceHolder'=> __('Enter Exam date ')));?>
							</div>
				</div>
				

			
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('');?></div>
							<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
							<?php echo $this->Form->input(__('Edit Holiday'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
							</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>			