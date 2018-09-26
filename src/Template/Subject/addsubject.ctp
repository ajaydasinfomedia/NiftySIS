<!--- section ajax ----->
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
<!--- end section ajax ----->

<div class="row">	
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Subject List'),['controller' => 'Subject', 'action' => 'subjectlist'],['escape' => false]);?>
		</li>
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Subject'),['controller' => 'Subject', 'action' => 'addsubject'],['escape' => false]);?>
		</li>		
	</ul>	
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addsubject']]);?>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter Subject '));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div class="col-md-3 col-sm-6 col-xs-12">
						<?php echo $this->Form->input('',array('name'=>'sub_code','class'=>'form-control validate[required,maxSize[20]]','PlaceHolder'=> __('Enter Subject Code ')));?>
					</div>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<?php echo $this->Form->input('',array('name'=>'sub_name','class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=> __('Enter Subject Name ')));?>
					</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Class '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<select class="form-control validate[required,maxSize[50]]" name="class_id" id="savedata">
								<option value=""><?php echo __('Select Class'); ?></option>
									<?php foreach($it as $it2):?><option value="<?php echo $it2['class_id'];?>"><?php echo $it2['class_name'];?></option> <?php endforeach;?>
								</select>
							</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Section '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<select class="form-control validate[required,maxSize[50]] ajaxdata" name="section" id="dep">
								<option value=""><?php echo __('Select Section'); ?></option>
								</select>
							</div>
				</div>
				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Teacher '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<select class="form-control validate[required,maxSize[50]]" name="teacher_id">
									<?php foreach($it1 as $it2):?><option value="<?php echo $it2['user_id'];?>"><?php echo $it2['first_name'];?></option> <?php endforeach;?>
								</select>
							</div>
				</div>

				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Edition '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'edition','class'=>'form-control validate[maxSize[50]]','PlaceHolder'=> __('Enter Edition ')));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter Author Name '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'author_name','class'=>'form-control validate[maxSize[50]]','PlaceHolder'=> __('Enter Author Name ')));?>
								
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Syllabus '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('type'=>'file','name'=>'syllabus'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12"></div>
							<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input(__('Add Subject'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
							</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>			