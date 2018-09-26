<script>
$( document ).ready(function(){
	
    $("#savedata").change(function(){
		
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
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Subject List'),['controller' => 'Subject', 'action' => 'subjectlist'],['escape' => false]);?>
		</li>
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-pencil-square-o fa-lg')) . __('Edit Subject'),['controller' => 'Subject', 'action' => 'updatesubject',$this->Setting->my_simple_crypt($it['subid'],'e')],['escape' => false]);?>
		</li>
	</ul>	
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('$it',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>

				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter Subject Name '));?><span style="color:red;"><?php echo " *"; ?></div>
					<div class="col-md-3 col-sm-6 col-xs-12">
						<?php echo $this->Form->input('',array('value'=>$it['sub_code'],'name'=>'sub_code','class'=>'form-control validate[required,maxSize[20]]','PlaceHolder'=> __('Enter Subject Code ')));?>
					</div>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<?php echo $this->Form->input('',array('value'=>$it['sub_name'],'name'=>'sub_name','class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=>'Enter Subject Name'));?>
					</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Class '));?><span style="color:red;"><?php echo " *"; ?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">				
								<?php 
									echo @$this->form->select("class_id",$it2,["default"=>$it['class_id'],"empty"=>__("Select Class"),"class"=>"form-control validate[required,maxSize[50]]","id"=>"savedata"]);
								?>
							</div>
				</div>
				
				<div class="form-group">
						<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Section '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php 
									echo @$this->form->select("class_section_id",$sect,["default"=>$it['section'],"empty"=>__("Select Section"),"class"=>"form-control validate[required,maxSize[50]] ajaxdata","name"=>"section"]);
								?>
								
								<!--- <select class="form-control validate[required] ajaxdata" name="section" id="dep">
									<option value="<?php echo $it['section']; ?>"><?php echo $it['section']; ?></option>
								</select>--->
						</div>
				</div>
				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Teacher '));?><span style="color:red;"><?php echo " *"; ?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php 
									echo @$this->form->select("user_id",$it1,["default"=>$it['teacher_id'],"name"=>"teacher_id","empty"=>__("Select Teacher"),"class"=>"form-control validate[required,maxSize[50]]"]);
								?>
								
							</div>
				</div>

				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Edition '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['edition'],'name'=>'edition','class'=>'form-control validate[maxSize[50]]','PlaceHolder'=>'Enter Edition'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter Author Name '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['author_name'],'name'=>'author_name','class'=>'form-control validate[maxSize[50]]','PlaceHolder'=>'Enter Author Name'));?>
								
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Syllabus '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('type'=>'hidden','value'=>$it['syllabus'],'name'=>'file2'));?>
								<?php echo $this->Form->input('',array('type'=>'file','name'=>'syllabus'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12"></div>
							<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input(__('Save Subject'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
							</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>			