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
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-pencil fa-lg')) . __('Manage Marks'),['controller' => 'Comman', 'action' => 'addmarks'],['escape' => false]);?>
		</li>
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-upload fa-lg')) . __('Export Marks'),['controller' => 'Comman', 'action' => 'exploremark'],['escape' => false]);?>
		</li>
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-align-left fa-lg')) . __('Add Multiple Subject Marks'),['controller' => 'Comman', 'action' => 'addmultiplemark'],['escape' => false]);?>
		</li>
	</ul>	
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addmarks']]);?>
			
				<div class="form-group">
					<div class="col-md-3 col-sm-6 col-xs-12">
						
						<?php echo $this->Form->label('Select Exam');?><span style="color:red;"><?php echo " *"; ?></span>
						
						<?php 
													
							if(isset($_POST['manage_mark']))
							{
								echo @$this->form->select("exam_id",$exam_id,["default"=>$e_id,"empty"=>__("Select Exam"),"class"=>"form-control validate[required,maxSize[50]]",'id'=>'exam_id']);	
							}
							else{
								echo @$this->form->select("exam_id",$exam_id,["default"=>"","empty"=>__("Select Exam"),"class"=>"form-control validate[required,maxSize[50]]",'id'=>'exam_id']);	
							}
							
						?>
						
					</div>
					
					
					<div class="col-md-3 col-sm-6 col-xs-12">
						
						<?php echo $this->Form->label('Select Class');?><span style="color:red;"><?php echo " *"; ?></span>
						
						<?php 

						if(isset($_POST['manage_mark']))
						{
							echo @$this->form->select("class_id",$class_id,["default"=>$c_id,"empty"=>__("Select Class"),"class"=>"form-control validate[required,maxSize[50]]",'id'=>'class_id']);
						}
						else{
							echo @$this->form->select("class_id",$class_id,["default"=>"","empty"=>__("Select Class"),"class"=>"form-control validate[required,maxSize[50]]",'id'=>'class_id']);
						}
							
						?>
				  </div>	
					
					<div class="col-md-3 col-sm-6 col-xs-12">
						
						<?php echo $this->Form->label('Select Section');?><span style="color:red;"><?php echo " *"; ?></span>	
						<select class="form-control validate[required,maxSize[50]] ajaxdata" name="section" id="dep">
							<option value=""><?php echo __('Select Section'); ?></option>
						</select>
					</div>
				
				
					<div class="col-md-3 col-sm-6 col-xs-12 button-possition">
						<?php echo $this->Form->label('');?>
						<?php echo $this->Form->input(__('Export Marks'),array('type'=>'submit','name'=>'export_marks','class'=>'btn btn-info'));?>

					</div>
					
				</div>
			
			<?php $this->Form->end(); ?>
			</div>