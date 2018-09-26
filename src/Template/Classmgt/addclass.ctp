<div class="row">
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Class List'),['controller' => 'Classmgt', 'action' => 'classlist'],['escape' => false]);?>
		</li>
		<li class="active">		
			<?php echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Class'),['controller' => 'Classmgt', 'action' => 'addclass'],['escape' => false]);?>
		</li>
	</ul>	
</div>

<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('formID',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addclass']]);?>				

				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter Class Name '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'class_name','class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=> (__('Enter Class Name '))));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Numeric Class Name '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<?php echo $this->Form->input('',array('type'=>'number','min'=>'1','name'=>'class_num_name','class'=>'validate[required] number_class'));?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Student Capacity in Section '));?></div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<?php echo $this->Form->input('',array('type'=>'number','min'=>'1','max'=>'1000','name'=>'class_capacity','class'=>'number_class'));?>
							</div>
				</div>
				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12"></div>
							<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input(__('Add Class'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
							</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>
<style>
.number_class{width: 100%;}
.input.number>label{
	display: none;
}
</style>	
