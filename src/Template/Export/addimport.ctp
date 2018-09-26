<?php
$import_title = isset($row['import_title'])?$row['import_title']:'';
?>
<div class="row">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">
		<li class="">
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Import List'),array('controller'=>'Export','action' => 'importlist'),array('escape' => false));?>
		</li>
		<li class="active">
			<?php  
			if(isset($edit))
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Update Import'),array('controller'=>'Export','action' => 'addimport',$row['import_id']),array('escape' => false));
			else
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('New Import'),array('controller'=>'Export','action' => 'addimport'),array('escape' => false));
			?>
		</li>
		<li class="">
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Export List'),array('controller'=>'Export','action' => 'exportlist'),array('escape' => false));?>
		</li>
		<li class="">
			<?php  
			echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('New Export'),array('controller'=>'Export','action' => 'addexport'),array('escape' => false));
			?>
		</li>
	</ul>
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addimport']]);?>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float" style="line-height:28px;"><?php echo $this->Form->label(__('Import Title '));?><span class="require-field">*</span></div>
					<div class="col-md-10 col-sm-10 col-xs-12">
						<?php echo $this->Form->input('',array('name'=>'import_title','value'=>$import_title,'class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=> __('Enter Import Title ')));?>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float" style="line-height:38px;"><?php echo $this->Form->label(__('Student CSV File ')); ?></div>
					<div class="col-md-3 col-sm-6 col-xs-12" style="line-height:38px;">
						<input class="" type="file" name="student_csv_file" value="Select CSV File" style="vertical-align: middle;display: inline-flex;">
					</div>				
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float" style="line-height:38px;"><?php echo $this->Form->label(__('Teacher CSV File ')); ?></div>
					<div class="col-md-3 col-sm-6 col-xs-12" style="line-height:38px;">
						<input class="" type="file" name="teacher_csv_file" value="Select CSV File" style="vertical-align: middle;display: inline-flex;">
					</div>				
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float" style="line-height:38px;"><?php echo $this->Form->label(__('Parent CSV File ')); ?></div>
					<div class="col-md-3 col-sm-6 col-xs-12" style="line-height:38px;">
						<input class="" type="file" name="parent_csv_file" value="Select CSV File" style="vertical-align: middle;display: inline-flex;">
					</div>			
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float" style="line-height:38px;"><?php echo $this->Form->label(__('Support Staff CSV File ')); ?></div>
					<div class="col-md-3 col-sm-6 col-xs-12" style="line-height:38px;">
						<input class="" type="file" name="staff_csv_file" value="Select CSV File" style="vertical-align: middle;display: inline-flex;">
					</div>				
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float" style="line-height:38px;"><?php echo __(''); ?></div>
					<div class="col-md-2 col-sm-2 col-xs-12">
						<?php echo $this->Form->input(__('Import Data'),array('type'=>'submit','name'=>'import_csv_file','class'=>'btn btn-success'));?>
					</div>
					
					<div class="col-md-2 col-sm-2 col-xs-12">						
						<a class="btn btn-primary submit" href="<?php echo $this->request->webroot; ?>csv/import.csv"><?php echo __('Sample Download');?></a>
					</div>	
				</div>	
				
			<?php $this->Form->end(); ?>
        
		</div>
</div>			