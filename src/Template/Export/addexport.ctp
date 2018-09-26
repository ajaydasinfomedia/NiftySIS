<script>
	$(document).ready(function(){
	});
</script>
<?php
$export_title = isset($row['export_title'])?$row['export_title']:'';
$export_model = isset($row['export_model'])?$row['export_model']:array('');
?>
<div class="row">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">
		<li class="">
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Import List'),array('controller'=>'Export','action' => 'importlist'),array('escape' => false));?>
		</li>
		<li class="">
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('New Import'),array('controller'=>'Export','action' => 'addimport'),array('escape' => false));?>
		</li>
		<li class="">
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Export List'),array('controller'=>'Export','action' => 'exportlist'),array('escape' => false));?>
		</li>
		<li class="active">
			<?php  
			if(isset($edit))
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Edit Export'),array('controller'=>'Export','action' => 'addexport',$this->Setting->my_simple_crypt($row['export_id'],'e')),array('escape' => false));
			else
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('New Export'),array('controller'=>'Export','action' => 'addexport'),array('escape' => false));
			?>
		</li>
	</ul>
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addexport']]);?>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Export Title '));?><span class="require-field">*</span></div>
					<div class="col-md-10 col-sm-10 col-xs-12">
						<?php echo $this->Form->input('',array('name'=>'export_title','value'=>$export_title,'class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=> __('Enter Export Title ')));?>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Export Model '));?><span class="require-field">*</span></div>
					<div class="col-md-10 col-sm-10 col-xs-12 export_model">
						<?php
						if(isset($edit))
							$d=explode(',',$export_model);
						else
							$d=array('');
						
						echo $this->Form->input('', array('class'=>'validate[minCheckbox[1]] checkbox',
						'name'=>'export_model','type' => 'select','multiple' => 'checkbox',
						'options' => array(
											'student' => __('Student'),
											'teacher' => __('Teacher'),
											'parent'=>__('Parent'),
											'staff'=>__('Support Staff'),
										  ),
										  'default' => $d)
								); ?>
					</div>
				</div>
			
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('');?></div>
					<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
					<?php 
					if(isset($edit))
						echo $this->Form->input(__('Edit Export'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));
					else
						echo $this->Form->input(__('New Export'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));
					?>
					</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>			