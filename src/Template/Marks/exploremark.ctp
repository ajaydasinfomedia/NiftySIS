<script>
$( document ).ready(function(){
	
    $("#class_id").change(function(){
	var class_id = $(this).val();
     
	 $('.ajaxdata').html();
       $.ajax({
       type: 'POST',
       url: '<?php echo $this->Url->build(["controller" => "Student","action" => "view2"]);?>',
	
       data : {id : class_id},

	     
       success: function (data)
       {            
			
			$('.ajaxdata').html(data);
			console.log(data);
			  
				
   },
error: function(e) {
       alert("An error occurred: " + e.responseText);
       console.log(e.responseText);	
}

       });

       });
   });

</script>

<div class="row">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-pencil fa-lg')) . __('Manage Marks'),['controller' => 'Marks', 'action' => 'addmarks'],['escape' => false]);?>
		</li>
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-upload fa-lg')) . __('Export Marks'),['controller' => 'Marks', 'action' => 'exploremark'],['escape' => false]);?>
		</li>
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-align-left fa-lg')) . __('Add Multiple Subject Marks'),['controller' => 'Marks', 'action' => 'addmultiplemark'],['escape' => false]);?>
		</li>
	</ul>	
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addmarks']]);?>
			
				<div class="form-group">
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
								echo @$this->Form->select('',array('options'=>$exam),array('name'=>'exam_id','value'=>$e_id,'class'=>'form-control validate[required,maxSize[50]]'));
							}
							else{
								echo @$this->Form->select('',array('options'=>$exam),array('name'=>'exam_id','class'=>'form-control validate[required,maxSize[50]]','empty'=> __('Select Exam')));
							}
							
						?>
						
					</div>
					
					
					<div class="col-md-3 col-sm-6 col-xs-12">
						
						<?php echo $this->Form->label(__('Select Class'));?><span style="color:red;"><?php echo " *"; ?></span>
						
						<?php 
							
							foreach($class_id as $class_data):
							{
								$class[$class_data['class_id']]=$class_data['class_name'];
							}
							endforeach; 
						if(isset($_POST['manage_mark']))
						{
							echo @$this->Form->select('',array('options'=>$class),array('name'=>'class_id','value'=>$c_id,'class'=>'form-control validate[required,maxSize[50]]','id'=>'class_id'));
						}
						else{
							echo @$this->Form->select('',array('options'=>$class),array('name'=>'class_id','class'=>'form-control validate[required,maxSize[50]]','id'=>'class_id','empty'=> __('Select Class')));
						}
							
						?>
				  </div>

					<div class="col-md-3 col-sm-6 col-xs-12">
						
						<?php echo $this->Form->label(__('Select Section'));?><span style="color:red;"><?php echo " *"; ?></span>	
						<select class="form-control validate[required,maxSize[50]] ajaxdata" name="section" id="dep">
							<option value=""><?php echo __('Select Section'); ?></option>
						</select>
					</div>		
				
					<div class="col-md-3 col-sm-6 col-xs-12 button-possition">
						<?php echo $this->Form->label(__(''));?>
						<?php echo $this->Form->input(__('Export Marks'),array('type'=>'submit','name'=>'export_marks','class'=>'btn btn-info'));?>

					</div>
					
				</div>
			
			<?php $this->Form->end(); ?>
			</div>
			</div>