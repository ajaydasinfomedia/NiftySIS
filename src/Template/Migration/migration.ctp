<div class="row">
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li class="active">
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-graduation-cap fa-lg')) . __('Migration'),['controller' => 'Migration', 'action' => 'migration'],['escape' => false]);?>
		</li>
	</ul>	
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addmarks']]);?>
			
				<div class="form-group">
					<div class="col-md-3 col-sm-6 col-xs-12">
						
						<?php echo $this->Form->label(__('Select Current Class'));?><span style="color:red;"><?php echo " *"; ?></span>
						
						<?php 							
						foreach($class_id as $class_data):
						{
							$class[$class_data['class_id']]=$class_data['class_name'];
						}
						endforeach; 
						
						if(isset($_POST['manage_mark']))
						{
							echo @$this->Form->select('',$class,array('name'=>'current_class','value'=>$c_id,'class'=>'form-control validate[required,maxSize[50]]','id'=>'class_id'));
						}
						else{
							echo @$this->Form->select('',$class,array('name'=>'current_class','class'=>'form-control validate[required,maxSize[50]]','id'=>'class_id','empty'=> __('Select Class')));
						}							
						?>
					</div>
					<div class="col-md-3 col-sm-6 col-xs-12">
						
						<?php echo $this->Form->label(__('Select Next Class'));?><span style="color:red;"><?php echo " *"; ?></span>
						
						<?php 
							
						foreach($class_id as $class_data):
						{
							$class[$class_data['class_id']]=$class_data['class_name'];
						}
						endforeach; 
						
						if(isset($_POST['manage_mark']))
						{
							echo @$this->Form->select('',$class,array('name'=>'next_class','value'=>$c_id,'class'=>'form-control validate[required,maxSize[50]]','id'=>'class_id'));
						}
						else{
							echo @$this->Form->select('',$class,array('name'=>'next_class','class'=>'form-control validate[required,maxSize[50]]','id'=>'class_id','empty'=> __('Select Class')));
						}
							
						?>
					</div>
					<div class="col-md-3 col-sm-6 col-xs-12">
						
						<?php echo $this->Form->label(__('Select Exam'));?><span style="color:red;"><?php echo " *"; ?></span>
						
						<?php 
						
							foreach($exam_id as $exam_data):
							{
								$exam[$exam_data['exam_id']]=$exam_data['exam_name'];
							}
							endforeach; 
							
							if(isset($_POST['manage_mark']))
							{
								echo @$this->Form->select('',$exam,array('name'=>'exam_id','value'=>$e_id,'class'=>'form-control validate[required,maxSize[50]]'));
							}
							else{
								echo @$this->Form->select('',$exam,array('name'=>'exam_id','class'=>'form-control validate[required,maxSize[50]]','empty'=> __('Select Exam')));
							}
							
						?>
						
					</div>
					<div class="col-md-2 col-sm-2 col-xs-12">
						
						<?php echo $this->Form->label(__('Passing Marks'));?><span style="color:red;"><?php echo " *"; ?></span>
						
						<?php echo $this->Form->input('',array('type'=>'number','name'=>'passing_marks','class'=>'form-control validate[required,custom[onlyNumberSp],maxSize[4]]','PlaceHolder'=> __('Marks'),'min'=>0,'max'=>1000));?>
						
					</div>
					
					<div class="col-md-2 col-sm-6 col-xs-12">
						<?php echo $this->Form->label('');?>
						<?php echo $this->Form->input(__('GO'),array('type'=>'submit','name'=>'migration','class'=>'btn btn-info'));?>

					</div>
					
				</div>			
		<?php $this->Form->end(); ?>
	</div>
</div>
<style>
.input.number>label{
	display: none;
}
</style>