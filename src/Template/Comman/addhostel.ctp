<script>
	$(document).ready(function(){
	});
</script>
<?php
$hostel_name = isset($row['hostel_name'])?$row['hostel_name']:'';
$hostel_type = isset($row['hostel_type'])?$row['hostel_type']:'';
$hostel_desc = isset($row['hostel_desc'])?$row['hostel_desc']:'';
?>
<div class="row">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">
		<li class="">
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Hostel List'),array('controller'=>'Comman','action' => 'hostellist'),array('escape' => false));?>
		</li>
		<li class="active">
			<?php  
			if(isset($edit))
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Edit Hostel'),array('controller'=>'Comman','action' => 'addhostel',$row['hostel_id']),array('escape' => false));
			else
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Hostel'),array('controller'=>'Comman','action' => 'addhostel'),array('escape' => false));
			?>
		</li>
		<li class="">
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Room List'),array('controller'=>'Comman','action' => 'roomlist'),array('escape' => false));?>
		</li>
		<li class="">
			<?php  
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Room'),array('controller'=>'Comman','action' => 'addroom'),array('escape' => false));
			?>
		</li>
		<li class="">
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Beds List'),array('controller'=>'Comman','action' => 'bedslist'),array('escape' => false));?>
		</li>
		<li class="">
			<?php  
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Beds'),array('controller'=>'Comman','action' => 'addbeds'),array('escape' => false));
			?>
		</li>
	</ul>
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addhostel']]);?>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Hostel Name '));?><span class="require-field">*</span></div>
					<div class="col-md-10 col-sm-10 col-xs-12">
						<?php echo $this->Form->input('',array('name'=>'hostel_name','value'=>$hostel_name,'class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=> __('Enter Hostel Name ')));?>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Hostel Type '));?><span class="require-field">*</span></div>
					<div class="col-md-10 col-sm-10 col-xs-12 export_model">
						<?php
						echo $this->Form->input('',array('name'=>'hostel_type','value'=>$hostel_type,'class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=> __('Enter Hostel Type ')));
						?>					
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Description '));?><span class="require-field">*</span></div>
					<div class="col-md-10 col-sm-10 col-xs-12 export_model">
						<?php echo $this->Form->input('',array('name'=>'hostel_desc','value'=>$hostel_desc,'type'=>'textarea','class'=>'form-control validate[required,maxSize[500]]','PlaceHolder'=>__('Enter Description ')));?>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('');?></div>
					<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
					<?php 
					if(isset($edit))
						echo $this->Form->input(__('Edit Hostel'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));
					else
						echo $this->Form->input(__('Add Hostel'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));
					?>
					</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>			