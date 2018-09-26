<div class="row">			
				<ul role="tablist" class="nav nav-tabs panel_tabs">
                    
                    	  
					    <li class="">
							
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Grade List'),['controller'=>'Grade','action' => 'gradelist'],['escape' => false]);
						?>
						
						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->
						  
					  </li>
                    
					  <li class="active">
							
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Grade'),['controller'=>'Grade','action' => 'addgrade'],['escape' => false]);
						?>
						
						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->
						  
					  </li>
				
				</ul>
</div>
<div class="row">			
		<div class="panel-body">
			<div class="form-group">
				<h4 style="padding-left: 15px;color: #f22222;"><?php  echo __("Grade for 100 Marks Exam");?></h4>
			</div>
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['controller'=>'Grade','action'=>'addgrade']]);?>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Grade Name '));?><span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'grade_name','class'=>'form-control validate[required,maxSize[20]]','PlaceHolder'=> __('Enter Grade Name ')));?>
							</div>
				</div>
				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Grade Point '));?><span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'grade_point','class'=>'form-control validate[required,custom[onlyNumberSp],maxSize[5]]','PlaceHolder'=> __('Enter Grade Point ')));?>
							</div>
				</div>
				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('mark From '));?><span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'mark_from','class'=>'form-control validate[required,custom[onlyNumberSp],maxSize[5]]','PlaceHolder'=> __('Enter Grade From ')));?>
							</div>
				</div>
				
				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Mark up to '));?><span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'mark_upto','class'=>'form-control validate[required,custom[onlyNumberSp],maxSize[5]]','PlaceHolder'=> __('Enter Grade up to ')));?>
							</div>
				</div>
					
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Comment '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'grade_comment','type'=>'textarea','class'=>'form-control validate[maxSize[150]]','PlaceHolder'=> __('Enter Grade Comment ')));?>
							</div>
				</div>
			
			
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('');?></div>
							<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
							<?php echo $this->Form->input(__('Add Grade'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
							</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>			