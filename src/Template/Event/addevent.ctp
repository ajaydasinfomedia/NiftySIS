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
<?php
$event_title = isset($row['event_title'])?$row['event_title']:'';
$event_desc = isset($row['event_desc'])?$row['event_desc']:'';
$start_date = isset($row['start_date'])?$row['start_date']:'';
$end_date = isset($row['end_date'])?$row['end_date']:'';
$event_for = isset($row['event_for'])?$row['event_for']:'';
?>
<div class="row">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">
		<li class="">							
		<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Event List'),array('controller'=>'Event','action' => 'eventlist'),array('escape' => false));?>					  
		</li>
		<li class="active">		
		<?php  
		if(isset($edit))
			echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Edit Event'),array('controller'=>'Event','action' => 'addevent',$this->Setting->my_simple_crypt($row['event_id'],'e')),array('escape' => false));
		else
			echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Event'),array('controller'=>'Event','action' => 'addevent'),array('escape' => false));					
		?>
		</li>
	</ul>
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addnews']]);?>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Event Title '));?><span class="require-field">*</span></div>
					<div class="col-md-10 col-sm-10 col-xs-12">
						<?php echo $this->Form->input('',array('name'=>'event_title','value'=>$event_title,'class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=> __('Enter Event Title ')));?>
					</div>
				</div>
								
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Description '));?></div>
					<div class="col-md-10 col-sm-10 col-xs-12">
						<?php echo $this->Form->input('',array('name'=>'event_desc','value'=>$event_desc,'type'=>'textarea','class'=>'form-control validate[maxSize[500]]','PlaceHolder'=> __('Enter Description ')));?>
					</div>
				</div>
			
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Start Date '));?><span class="require-field">*</span></div>
					<div class="col-md-10 col-sm-10 col-xs-12 datepickericon">
						<?php 
						if(isset($edit))
							$start_date = date("Y-m-d", strtotime($start_date));
						else
							$start_date = '';
						echo $this->Form->input('',array('name'=>'start_date','value'=>$start_date,'id'=>'date_of_birth20','class'=>'form-control validate[required]','PlaceHolder'=> __('Enter News date ')));?>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('End Date '));?><span class="require-field">*</span></div>
					<div class="col-md-10 col-sm-10 col-xs-12 datepickericon">
						<?php 
						if(isset($edit))
							$end_date = date("Y-m-d", strtotime($end_date));
						else
							$end_date = '';
						echo $this->Form->input('',array('name'=>'end_date','value'=>$end_date,'id'=>'date_of_birth21','class'=>'form-control validate[required]','PlaceHolder'=> __('Enter News date ')));?>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Event For '));?></div>
					<div class="col-md-10 col-sm-10 col-xs-12">
					<?php
						$options=['all'=> __('All'),
							'teacher'=> __('Teacher'),
							'student'=> __('Student'),
							'parent'=> __('Parent'),
							'supportstaff'=> __('Support Staff')
							];
						echo $this->Form->select('',$options,['value'=>$event_for,'class'=>'form-control select validate[required]','name'=>'event_for']);
					?>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('');?></div>
					<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
					<?php 
					if(isset($edit))
						echo $this->Form->input(__('Edit Event'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));
					else
						echo $this->Form->input(__('Add Event'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));
					?>
					</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>			