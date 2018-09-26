<div class="row">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">
		<li>
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Exam Hall List'),array('controller'=>'Hall','action' => 'halllist'),array('escape' => false));?>
		</li>
		<li class="active">
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Exam Hall'),array('controller'=>'Hall','action' => 'addhall'),array('escape' => false));?>
		</li>
		<li class="">
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Exam Hall Receipt'),array('controller'=>'Hall','action' => 'examhallreceipt'),array('escape' => false));?>
		</li>
	</ul>
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['controller'=>'Hall','action'=>'addhall']]);?>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Exam Hall Name '));?> <span class="require-field">*</span></div>
					<div class="col-md-10 col-sm-10 col-xs-12">
						<?php echo $this->Form->input('',array('name'=>'hall_name','class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=> __('Enter Exam Hall Name ')));?>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Numeric Value '));?> <span class="require-field">*</span></div>
					<div class="col-md-10 col-sm-10 col-xs-12">
						<?php echo $this->Form->input('',array('name'=>'number_of_hall','class'=>'form-control validate[required,custom[onlyNumberSp],maxSize[5]]','PlaceHolder'=> __('Enter Exam Hall Numeric Value ')));?>
					</div>
				</div>								
					
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Description '));?></div>
					<div class="col-md-10 col-sm-10 col-xs-12">
						<?php echo $this->Form->input('',array('name'=>'description','type'=>'textarea','class'=>'form-control validate[maxSize[500]]','PlaceHolder'=> __('Enter Exam Hall Description ')));?>
					</div>
				</div>
			
			
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('');?></div>
					<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
						<?php echo $this->Form->input(__('Add Exam Hall'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
					</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>			